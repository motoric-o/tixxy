<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index(): View
    {
        $userId = Auth::user()->id;

        $tickets = Ticket::with('order.event')->whereHas('order', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                    })->get();
        
        return view('ticketList', compact('tickets'));
    }

    /**
     * Display the e-ticket detail page with QR code.
     */
    public function show(string $id): View
    {
        $userId = Auth::user()->id;

        $ticket = Ticket::with(['order.event.category', 'order.user'])
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->findOrFail($id);

        return view('ticketDetail', compact('ticket'));
    }
}
