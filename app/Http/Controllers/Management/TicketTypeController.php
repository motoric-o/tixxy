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
        $rows = TicketType::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })->latest()->paginate(10);
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
        $ticketType->delete();
        return redirect('/manage/ticket-types')->with('success', 'Ticket Type deleted successfully.');
    }
}
