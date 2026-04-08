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
    public function index(Request $request): View
    {
        $eventId = $request->input('event_id');

        $event = Event::with('category')->findOrFail($eventId);

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
            \App\Models\OrderDetail::create([
                'order_id' => $order->id,
                'event_ticket_type_id' => $ticketType->id,
                'quantity' => $request->qty,
            ]);
        }

        return redirect()->route('payment.show', ['id' => $order->id]);
    }
}
