<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use App\ViewModels\TicketTypeCrudViewModel;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index() {
        $search = request('search');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        
        $sort = request('sort', 'id');
        $direction = request('direction', 'desc');

        $rows = TicketType::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->withCount('eventTicketTypes');

        if ($sort === 'event_ticket_types_count') {
             $rows = $rows->orderBy('event_ticket_types_count', $direction);
        } else {
             $rows = $rows->orderBy($sort, $direction);
        }

        $rows = $rows->paginate(10)->withQueryString();
        $ticketTypesVM = new TicketTypeCrudViewModel($rows, 'index');
        return view('admin.crud.index', $ticketTypesVM->toArray());
    }

    public function create() {
        $ticketTypesVM = new TicketTypeCrudViewModel(null, 'create');
        return view('admin.crud.form', $ticketTypesVM->toArray());
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:ticket_types,name',
        ]);

        TicketType::create([
            'name' => $request->name,
        ]);

        return redirect('/manage/ticket-types')->with('success', 'Ticket Type created successfully.');
    }

    public function edit($id) {
        $ticketType = TicketType::findOrFail($id);
        $ticketTypesVM = new TicketTypeCrudViewModel($ticketType, 'edit');
        return view('admin.crud.form', $ticketTypesVM->toArray());
    }

    public function update($id, Request $request) {
        $ticketType = TicketType::findOrFail($id);
        $ticketType->update($request->all());
        return redirect('/manage/ticket-types')->with('success', 'Ticket Type updated successfully.');
    }

    public function destroy($id) {
        $ticketType = TicketType::findOrFail($id);

        if ($ticketType->eventTicketTypes()->exists()) {
            return redirect('/manage/ticket-types')->with('error', 'Ticket Type cannot be deleted because it has event ticket types.');
        }

        $ticketType->delete();
        return redirect('/manage/ticket-types')->with('success', 'Ticket Type deleted successfully.');
    }
}
