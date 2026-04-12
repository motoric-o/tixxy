<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page for a given event.
     */
    public function index(Request $request): View|\Illuminate\Http\RedirectResponse
    {
        $eventId = $request->input('event_id');

        $event = Event::with('category')->findOrFail($eventId);
        
        // Prevent going back to checkout if there is already an active unpaid order
        $existingOrder = \App\Models\Order::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('event_id', $eventId)
            ->where('status', 'pending')
            ->whereNull('payment_proof')
            ->orderBy('id', 'desc')
            ->first();
            
        if ($existingOrder) {
            $expiry = $existingOrder->expired_at ?? $existingOrder->created_at->addHour();
            if (now()->lessThan($expiry)) {
                return redirect()->route('payment.show', $existingOrder->id)
                    ->with('info', 'You already have an active order for this event. Please complete your payment.');
            }
        }

        return view('checkout', compact('event'));
    }
    /**
     * Process checkout form and create the Order.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:5',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $event = Event::with('eventTicketTypes')->findOrFail($id);
        
        // Use the first available ticket type for pricing (for simplicity)
        $ticketType = $event->eventTicketTypes->first();
        $amount = $ticketType ? ($ticketType->price * $request->qty) : 0;

        // Create the pending order
        $order = \App\Models\Order::create([
            'amount' => $amount,
            'status' => 'pending',
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'event_id' => $event->id,
        ]);
        $order->expired_at = now()->addHour();
        $order->save();

        if ($ticketType) {
            for ($i = 0; $i < $request->qty; $i++) {
                // Generate a ticket immediately since it's required for the OrderDetail primary key
                $ticket = \App\Models\Ticket::create([
                    'order_id' => $order->id,
                ]);

                \App\Models\OrderDetail::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                    'event_ticket_type_id' => $ticketType->id,
                ]);
            }
        }

        return redirect()->route('payment.show', ['id' => $order->id]);
    }
}
