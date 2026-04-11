<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\ViewModels\TicketCrudViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['order.user', 'order.event'])
            ->when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('order.event', fn($sub) => $sub->where('user_id', Auth::id()));
            });

        // Search
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('qr_code_hash', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order.event', function($e) use ($search) {
                      $e->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filters
        if ($request->has('is_scanned') && $request->get('is_scanned') !== '') {
            $query->where('is_scanned', $request->get('is_scanned'));
        }

        $tickets = $query->latest()->paginate(10);
        
        // Map values for columns that need it
        $tickets->getCollection()->transform(function ($ticket) {
            $ticket->is_scanned_label = $ticket->is_scanned ? 'Scanned' : 'Active';
            return $ticket;
        });

        $viewModel = new TicketCrudViewModel($tickets, 'index');

        return view('admin.crud.index', $viewModel->toArray());
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit($id)
    {
        $ticket = Ticket::with(['order.user', 'order.event'])
            ->when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('order.event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);
            
        $viewModel = new TicketCrudViewModel($ticket, 'edit');

        return view('admin.crud.form', $viewModel->toArray());
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'is_scanned' => 'required|boolean',
        ]);

        $ticket = Ticket::when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('order.event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);
            
        $ticket->update($request->only('is_scanned'));

        return redirect('/manage/tickets')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy($id)
    {
        $ticket = Ticket::when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('order.event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);
            
        if ($ticket->is_scanned) {
            return redirect('/manage/tickets')->with('error', 'Ticket cannot be deleted because it has already been scanned.');
        }

        $ticket->delete();

        return redirect('/manage/tickets')->with('success', 'Ticket deleted successfully.');
    }
}
