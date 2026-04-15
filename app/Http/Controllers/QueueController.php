<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    /**
     * Join the queue for an event.
     * Uses database locking to prevent race conditions and queue jumping.
     */
    public function join(Request $request, int $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        // Prevent joining if the event has already started
        if ($event->start_time && now()->greaterThan($event->start_time)) {
            return redirect()->route('events.index')
                ->with('error', 'Booking for this event has already closed.');
        }

        // Check if user already has a queue entry for this event
        $existing = Queue::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if ($existing) {
            return $this->handleExistingEntry($existing, $eventId);
        }

        // Use a transaction with locking to prevent race conditions
        $queueEntry = DB::transaction(function () use ($user, $event) {
            // Lock the event row so capacity calculation is atomic
            $lockedEvent = Event::lockForUpdate()->find($event->id);
            $availableSpots = $this->calculateAvailableSpots($lockedEvent);

            // Check if anyone is already on the waitlist (enforce fairness — no queue jumping)
            $hasWaitlist = Queue::where('event_id', $lockedEvent->id)
                ->whereIn('status', [Queue::STATUS_WAITLISTED, Queue::STATUS_NOTIFIED])
                ->exists();

            if ($availableSpots > 0 && !$hasWaitlist) {
                // Spots available and no waitlist — activate immediately
                return Queue::create([
                    'event_id'   => $lockedEvent->id,
                    'user_id'    => $user->id,
                    'status'     => Queue::STATUS_ACTIVE,
                    'expires_at' => now()->addMinutes(Queue::ACTIVE_MINUTES),
                ]);
            }

            if ($availableSpots > 0 && $hasWaitlist) {
                // Spots available but waitlist exists — user goes to back of line
                return Queue::create([
                    'event_id'   => $lockedEvent->id,
                    'user_id'    => $user->id,
                    'status'     => Queue::STATUS_QUEUED,
                    'expires_at' => null,
                ]);
            }

            // No spots available — check if event is fully sold out vs just holding
            $hasHolders = Queue::where('event_id', $lockedEvent->id)->holding()->exists();
            $hasPendingOrders = Order::where('event_id', $lockedEvent->id)
                ->where('status', 'pending')
                ->where(function ($q) {
                    $q->where('expired_at', '>', now())
                      ->orWhere(function ($q2) {
                          $q2->whereNull('expired_at')
                             ->where('created_at', '>', now()->subHour());
                      });
                })
                ->exists();

            // If there are holders or pending orders, tickets might free up — queue them
            // Otherwise they go directly to waitlist
            $status = ($hasHolders || $hasPendingOrders)
                ? Queue::STATUS_QUEUED
                : Queue::STATUS_WAITLISTED;

            return Queue::create([
                'event_id'   => $lockedEvent->id,
                'user_id'    => $user->id,
                'status'     => $status,
                'expires_at' => null,
            ]);
        });

        // If they got activated immediately, send them to checkout
        if ($queueEntry->status === Queue::STATUS_ACTIVE) {
            return redirect("/checkout?event_id={$eventId}");
        }

        // Otherwise, send them to the waiting room
        return redirect("/queue/{$eventId}");
    }

    /**
     * Display the waiting room for an event.
     */
    public function waitingRoom(int $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);

        $queueEntry = Queue::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if (!$queueEntry) {
            return redirect()->route('events.index')
                ->with('error', 'You need to join the queue first.');
        }

        // If they are active, redirect directly to checkout
        if ($queueEntry->status === Queue::STATUS_ACTIVE) {
            return redirect("/checkout?event_id={$eventId}");
        }

        // If they already purchased, redirect to their tickets
        if ($queueEntry->status === Queue::STATUS_PURCHASED) {
            return redirect('/tickets')->with('info', 'You have already purchased tickets for this event.');
        }

        // Calculate their position in line
        $position = Queue::where('event_id', $eventId)
            ->whereIn('status', [Queue::STATUS_QUEUED, Queue::STATUS_WAITLISTED])
            ->where('created_at', '<=', $queueEntry->created_at)
            ->count();

        return view('queue.waiting-room', compact('event', 'queueEntry', 'position'));
    }

    /**
     * API endpoint: return the current queue status for polling.
     */
    public function status(int $eventId)
    {
        $user = Auth::user();

        $queueEntry = Queue::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if (!$queueEntry) {
            return response()->json(['status' => 'not_found'], 404);
        }

        // Calculate position if still waiting
        $position = null;
        if (in_array($queueEntry->status, [Queue::STATUS_QUEUED, Queue::STATUS_WAITLISTED])) {
            $position = Queue::where('event_id', $eventId)
                ->whereIn('status', [Queue::STATUS_QUEUED, Queue::STATUS_WAITLISTED])
                ->where('created_at', '<=', $queueEntry->created_at)
                ->count();
        }

        return response()->json([
            'status'     => $queueEntry->status,
            'expires_at' => $queueEntry->expires_at?->toIso8601String(),
            'position'   => $position,
        ]);
    }

    /**
     * Handle a waitlist claim link (from email).
     * Transitions the user from 'notified' to 'active'.
     */
    public function claim(int $eventId)
    {
        $user = Auth::user();

        $queueEntry = Queue::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->where('status', Queue::STATUS_NOTIFIED)
            ->first();

        if (!$queueEntry) {
            return redirect("/queue/{$eventId}")
                ->with('error', 'No claimable ticket found. Your link may have expired.');
        }

        // Check if the 1-hour claim window has passed
        if ($queueEntry->expires_at && now()->greaterThan($queueEntry->expires_at)) {
            $queueEntry->update(['status' => Queue::STATUS_EXPIRED]);

            return redirect("/queue/{$eventId}")
                ->with('error', 'Your claim window has expired. The ticket has been offered to the next person.');
        }

        // Transition to active with a fresh 15-minute checkout timer
        $queueEntry->update([
            'status'     => Queue::STATUS_ACTIVE,
            'expires_at' => now()->addMinutes(Queue::ACTIVE_MINUTES),
        ]);

        return redirect("/checkout?event_id={$eventId}");
    }

    /**
     * Cancel a queue entry (user voluntarily leaves).
     * Triggers immediate promotion of the next person.
     */
    public function cancel(int $eventId)
    {
        $user = Auth::user();

        $queueEntry = Queue::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->whereIn('status', [
                Queue::STATUS_QUEUED,
                Queue::STATUS_ACTIVE,
                Queue::STATUS_WAITLISTED,
                Queue::STATUS_NOTIFIED,
            ])
            ->first();

        if ($queueEntry) {
            $wasHolding = in_array($queueEntry->status, Queue::HOLDING_STATUSES)
                || $queueEntry->status === Queue::STATUS_NOTIFIED;

            $queueEntry->update(['status' => Queue::STATUS_CANCELED]);

            // If they were holding a spot, immediately promote the next person
            if ($wasHolding) {
                $this->promoteNext($eventId);
            }
        }

        return redirect()->route('events.index')
            ->with('info', 'You have left the queue.');
    }

    /**
     * Immediately promote the next eligible person in line for an event.
     * Called when someone cancels and frees up a spot.
     */
    private function promoteNext(int $eventId): void
    {
        DB::transaction(function () use ($eventId) {
            $event = Event::lockForUpdate()->find($eventId);
            if (!$event) return;

            $availableSpots = $this->calculateAvailableSpots($event);
            if ($availableSpots <= 0) return;

            $next = Queue::where('event_id', $eventId)
                ->promotable()
                ->first();

            if (!$next) return;

            $wasWaitlisted = $next->status === Queue::STATUS_WAITLISTED;

            if ($wasWaitlisted) {
                $next->update([
                    'status'     => Queue::STATUS_NOTIFIED,
                    'expires_at' => now()->addMinutes(Queue::NOTIFIED_MINUTES),
                ]);

                // Send email notification
                $next->loadMissing(['user', 'event']);
                if ($next->user && $next->user->email) {
                    \Illuminate\Support\Facades\Mail::to($next->user->email)->send(
                        new \App\Mail\WaitlistTicketAvailable($next)
                    );
                }
            } else {
                $next->update([
                    'status'     => Queue::STATUS_ACTIVE,
                    'expires_at' => now()->addMinutes(Queue::ACTIVE_MINUTES),
                ]);
            }
        });
    }

    /**
     * Calculate available spots for an event (shared logic with ProcessEventQueues).
     */
    private function calculateAvailableSpots(Event $event): int
    {
        $purchasedTickets = $event->orders()
            ->where('status', 'completed')
            ->withCount('tickets')
            ->get()
            ->sum('tickets_count');

        $holdingCount = Queue::where('event_id', $event->id)->holding()->count();

        $notifiedCount = Queue::where('event_id', $event->id)->notified()->count();

        $pendingOrderCount = Order::where('event_id', $event->id)
            ->where('status', 'pending')
            ->where(function ($q) {
                $q->where('expired_at', '>', now())
                  ->orWhere(function ($q2) {
                      $q2->whereNull('expired_at')
                         ->where('created_at', '>', now()->subHour());
                  });
            })
            ->count();

        $occupied = $purchasedTickets + $holdingCount + $notifiedCount + $pendingOrderCount;

        return max(0, $event->quota - $occupied);
    }

    /**
     * Handle a user who already has a queue entry for this event.
     */
    private function handleExistingEntry(Queue $entry, int $eventId)
    {
        return match ($entry->status) {
            Queue::STATUS_ACTIVE, Queue::STATUS_PROCESSING
                => redirect("/checkout?event_id={$eventId}"),

            Queue::STATUS_PURCHASED
                => redirect('/tickets')->with('info', 'You have already purchased tickets for this event.'),

            Queue::STATUS_EXPIRED, Queue::STATUS_CANCELED
                => $this->rejoinQueue($entry, $eventId),

            default
                => redirect("/queue/{$eventId}"),
        };
    }

    /**
     * Allow a user with an expired/canceled entry to rejoin the queue.
     */
    private function rejoinQueue(Queue $entry, int $eventId)
    {
        $entry->update([
            'status'     => Queue::STATUS_QUEUED,
            'expires_at' => null,
        ]);

        return redirect("/queue/{$eventId}");
    }
}
