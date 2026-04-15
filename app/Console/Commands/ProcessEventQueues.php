<?php

namespace App\Console\Commands;

use App\Mail\WaitlistTicketAvailable;
use App\Models\Event;
use App\Models\Order;
use App\Models\Queue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ProcessEventQueues extends Command
{
    protected $signature = 'queue:process-tickets';
    protected $description = 'Process event ticket queues: expire stale holds, promote waitlisted users, and send notifications.';

    public function handle(): int
    {
        $this->expireStaleEntries();
        $this->processEventCapacity();

        return self::SUCCESS;
    }

    private function expireStaleEntries(): void
    {
        $expired = Queue::expirable()->update(['status' => Queue::STATUS_EXPIRED]);

        if ($expired > 0) {
            $this->info("Expired {$expired} stale queue entries.");
        }
    }

    private function processEventCapacity(): void
    {
        $eventIds = Queue::whereIn('status', [
            Queue::STATUS_QUEUED,
            Queue::STATUS_WAITLISTED,
            Queue::STATUS_ACTIVE,
            Queue::STATUS_PROCESSING,
        ])->distinct()->pluck('event_id');

        foreach ($eventIds as $eventId) {
            DB::transaction(function () use ($eventId) {
                $event = Event::lockForUpdate()->find($eventId);
                if (!$event) return;

                $availableSpots = $this->calculateAvailableSpots($event);

                if ($availableSpots <= 0) {
                    Queue::where('event_id', $eventId)
                        ->where('status', Queue::STATUS_QUEUED)
                        ->update(['status' => Queue::STATUS_WAITLISTED]);
                    return;
                }

                $toPromote = Queue::where('event_id', $eventId)
                    ->promotable()
                    ->limit($availableSpots)
                    ->get();

                foreach ($toPromote as $entry) {
                    if ($entry->status === Queue::STATUS_WAITLISTED) {
                        $entry->update([
                            'status'     => Queue::STATUS_NOTIFIED,
                            'expires_at' => now()->addMinutes(Queue::NOTIFIED_MINUTES),
                        ]);
                        $this->notifyUser($entry);
                    } else {
                        $entry->update([
                            'status'     => Queue::STATUS_ACTIVE,
                            'expires_at' => now()->addMinutes(Queue::ACTIVE_MINUTES),
                        ]);
                    }
                }

                if ($toPromote->isNotEmpty()) {
                    $this->info("Promoted {$toPromote->count()} users for event #{$eventId}.");
                }
            });
        }
    }

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

    private function notifyUser(Queue $entry): void
    {
        $entry->loadMissing(['user', 'event']);

        if ($entry->user && $entry->user->email) {
            Mail::to($entry->user->email)->send(
                new WaitlistTicketAvailable($entry)
            );
            $this->info("Notified user #{$entry->user_id} for event #{$entry->event_id}.");
        }
    }
}
