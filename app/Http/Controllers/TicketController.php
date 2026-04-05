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
        // Hardcode user ID = 1 (simulasi user login)
        $userId = Auth::user()->id;

        // Ambil tiket milik user tersebut, eager load order dan event untuk performa
        $tickets = Ticket::with('order.event')->whereHas('order', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                    })->get();
        
        return view('ticketList', compact('tickets'));

    }
}
