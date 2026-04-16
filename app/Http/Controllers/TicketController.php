<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index(): View
    {
        $userId = Auth::user()->id;

        $orders = Order::with([
                'event.category',
                'tickets',
                'orderDetails.eventTicketType.ticketType',
            ])
            ->where('user_id', $userId)
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ticketList', compact('orders'));
    }

    /**
     * Display the e-ticket detail page with QR code.
     */
    public function show(string $id): View
    {
        $userId = Auth::user()->id;

        $ticket = Ticket::with([
                'order.event.category', 
                'order.event.organizer', 
                'order.user', 
                'order.orderDetails.eventTicketType.ticketType'
            ])
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->findOrFail($id);

        return view('ticketDetail', compact('ticket'));
    }
}
