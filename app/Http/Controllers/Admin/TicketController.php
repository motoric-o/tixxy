<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\ViewModels\TicketCrudViewModel;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['order.user', 'order.event']);

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('qr_code_hash', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order.event', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
        }

        // Filters
        if ($request->has('is_scanned') && $request->get('is_scanned') !== '') {
            $query->where('is_scanned', $request->get('is_scanned'));
        }

        $tickets = $query->latest()->paginate(10);
        
        // Map values for columns that need it
        $tickets->getCollection()->transform(function ($ticket) {
            $ticket->is_scanned = $ticket->is_scanned ? 'Scanned' : 'Active';
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
        $ticket = Ticket::with(['order.user', 'order.event'])->findOrFail($id);
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

        $ticket = Ticket::findOrFail($id);
        $ticket->update($request->only('is_scanned'));

        return redirect('/admin/tickets')->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect('/admin/tickets')->with('success', 'Ticket deleted successfully.');
    }
}
