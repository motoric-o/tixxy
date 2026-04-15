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

        $event = Event::with(['category', 'eventTicketTypes.ticketType'])->findOrFail($eventId);
        
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

        if (\Carbon\Carbon::parse($event->start_time)->isPast()) {
            return redirect()->route('events.index')
                ->with('error', 'Booking for this event has already closed.');
        }

        return view('checkout', compact('event'));
    }
    /**
     * Process checkout form and create the Order.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*.event_ticket_type_id' => 'required|exists:event_ticket_types,id',
            'tickets.*.qty' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $totalQty = collect($request->tickets)->sum('qty');
        if ($totalQty > 10) {
            return back()->withErrors(['tickets' => 'You can only purchase a maximum of 10 tickets in a single order.'])->withInput();
        }

        $event = Event::with('eventTicketTypes')->findOrFail($id);

        if ($totalQty > $event->available_quota) {
            return back()->withErrors(['tickets' => "You requested $totalQty tickets, but only {$event->available_quota} are currently available."])->withInput();
        }
        
        $amount = 0;
        foreach ($request->tickets as $ticketInput) {
            $ticketType = $event->eventTicketTypes->firstWhere('id', $ticketInput['event_ticket_type_id']);
            if (!$ticketType) {
                return back()->withErrors(['tickets' => 'Invalid ticket type selected for this event.'])->withInput();
            }
            $amount += $ticketType->price * $ticketInput['qty'];
        }

        // Create the pending order
        $order = \App\Models\Order::create([
            'amount' => $amount,
            'status' => 'pending',
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'event_id' => $event->id,
        ]);
        $order->expired_at = now()->addHour();
        $order->save();

        foreach ($request->tickets as $ticketInput) {
            $ticketType = $event->eventTicketTypes->firstWhere('id', $ticketInput['event_ticket_type_id']);
            for ($i = 0; $i < $ticketInput['qty']; $i++) {
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
