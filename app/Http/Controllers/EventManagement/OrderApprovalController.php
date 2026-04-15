<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderApprovalController extends Controller
{
    public function approve($id)
    {
        $order = Order::with(['user', 'event', 'orderDetails.ticket', 'orderDetails.eventTicketType.ticketType'])
            ->when(Auth::user()->role === 'organizer', function ($q) {
                $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);

        return view('admin.approve', compact('order'));
    }

    public function handleApprove($id)
    {
        $order = Order::when(Auth::user()->role === 'organizer', function ($q) {
            $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
        })
            ->findOrFail($id);

        if (empty($order->payment_proof)) {
            return redirect()->route('manage.orders.event', $order->event_id)->with('error', 'Cannot approve order without payment proof.');
        }

        $order->update(['status' => 'completed']);

        // Send order confirmation email
        app(EmailController::class)->sendOrderEmail($id);

        return redirect()->route('manage.orders.event', $order->event_id)->with('success', 'Order #' . str_pad($id, 6, '0', STR_PAD_LEFT) . ' has been approved and confirmation email sent.');
    }

    public function handleDecline($id)
    {
        $order = Order::when(Auth::user()->role === 'organizer', function ($q) {
            $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
        })
            ->findOrFail($id);

        // Delete payment proof from storage
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Cancel the order entirely
        $order->update([
            'payment_proof' => null,
            'status' => 'canceled',
        ]);

        // Delete associated tickets and order details (they were never valid)
        $order->orderDetails()->delete();
        $order->tickets()->delete();

        // Cancel the user's queue entry and promote the next person
        $queueEntry = \App\Models\Queue::where('user_id', $order->user_id)
            ->where('event_id', $order->event_id)
            ->whereIn('status', [
                \App\Models\Queue::STATUS_PURCHASED,
                \App\Models\Queue::STATUS_PROCESSING,
            ])
            ->first();

        if ($queueEntry) {
            $queueEntry->update(['status' => \App\Models\Queue::STATUS_CANCELED]);

            // Immediately promote the next person in line
            $this->promoteNext($order->event_id);
        }

        return redirect()->route('manage.orders.event', $order->event_id)->with('success', 'Order #' . str_pad($id, 6, '0', STR_PAD_LEFT) . ' has been canceled. The ticket spot has been released.');
    }

    /**
     * Promote the next person in the queue when a spot opens up.
     */
    private function promoteNext(int $eventId): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($eventId) {
            $event = \App\Models\Event::lockForUpdate()->find($eventId);
            if (!$event) return;

            $next = \App\Models\Queue::where('event_id', $eventId)
                ->promotable()
                ->first();

            if (!$next) return;

            if ($next->status === \App\Models\Queue::STATUS_WAITLISTED) {
                $next->update([
                    'status'     => \App\Models\Queue::STATUS_NOTIFIED,
                    'expires_at' => now()->addMinutes(\App\Models\Queue::NOTIFIED_MINUTES),
                ]);

                $next->loadMissing(['user', 'event']);
                if ($next->user && $next->user->email) {
                    \Illuminate\Support\Facades\Mail::to($next->user->email)->send(
                        new \App\Mail\WaitlistTicketAvailable($next)
                    );
                }
            } else {
                $next->update([
                    'status'     => \App\Models\Queue::STATUS_ACTIVE,
                    'expires_at' => now()->addMinutes(\App\Models\Queue::ACTIVE_MINUTES),
                ]);
            }
        });
    }
}
