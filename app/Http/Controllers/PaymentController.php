<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;


class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran untuk event tertentu.
     */
    public function show(string $id): View
    {
        // Mengambil data order berdasarkan ID
        $order = Order::with('event')->findOrFail($id);

        return view('payment', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Increased to 5MB for better UX
        ]);

        $expiryDate = $order->expired_at ?? $order->created_at->addHour();
        
        // Prevent upload if expired or not pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'This order is no longer pending and cannot accept payments.');
        }

        if (now()->greaterThanOrEqualTo($expiryDate)) {
            $order->update(['status' => 'canceled']);
            return redirect()->back()->with('error', 'Payment period has expired. This order has been canceled.');
        }

        if ($request->hasFile('payment_proof')) {
            try {
                $file = $request->file('payment_proof');
                $path = $file->store('payment_proofs', 'public');
                
                if (!$path) {
                    throw new \Exception('Failed to store file.');
                }

                $order->update([
                    'payment_proof' => $path,
                    // We don't change status to completed yet, it stays pending until admin confirms
                ]);

                return redirect()->back()->with('success', 'Payment proof submitted successfully! Please wait for organizer confirmation.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'No file was uploaded. Please try again.');
    }

    /**
     * Cancel a pending order and free associated resources.
     */
    public function cancel(string $id)
    {
        $order = Order::findOrFail($id);

        // Only the order owner can cancel
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only pending orders without payment proof can be canceled
        if ($order->status !== 'pending' || $order->payment_proof) {
            return redirect()->back()->with('error', 'This order cannot be canceled.');
        }

        $eventId = $order->event_id;

        DB::transaction(function () use ($order) {
            // Delete order details first (references ticket_id), then tickets
            $order->orderDetails()->delete();
            $order->tickets()->delete();

            // Mark order as canceled
            $order->update(['status' => 'canceled']);

            // Free the queue entry so the spot goes to someone else
            $queueEntry = Queue::where('user_id', $order->user_id)
                ->where('event_id', $order->event_id)
                ->first();

            if ($queueEntry) {
                $queueEntry->update(['status' => Queue::STATUS_CANCELED]);
            }
        });

        return redirect()->route('events.index')
            ->with('info', 'Your order has been canceled successfully.');
    }

    /**
     * Promote the next eligible person in the queue for an event.
     */
    private function promoteNext(int $eventId): void
    {
        DB::transaction(function () use ($eventId) {
            $event = \App\Models\Event::lockForUpdate()->find($eventId);
            if (!$event) return;

            // Calculate available spots
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

            $availableSpots = max(0, $event->quota - ($purchasedTickets + $holdingCount + $notifiedCount + $pendingOrderCount));
            if ($availableSpots <= 0) return;

            $next = Queue::where('event_id', $eventId)
                ->promotable()
                ->first();

            if (!$next) return;

            if ($next->status === Queue::STATUS_WAITLISTED) {
                $next->update([
                    'status'     => Queue::STATUS_NOTIFIED,
                    'expires_at' => now()->addMinutes(Queue::NOTIFIED_MINUTES),
                ]);
            } else {
                $next->update([
                    'status'     => Queue::STATUS_ACTIVE,
                    'expires_at' => now()->addMinutes(Queue::ACTIVE_MINUTES),
                ]);
            }
        });
    }
}

