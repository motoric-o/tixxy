<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\ViewModels\EventCrudViewModel;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');
        $rows = Event::with('category')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhereHas('category', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel  = new EventCrudViewModel($rows, 'index', $categories);

        return view('admin.crud.index', $viewModel->toArray());
    }

    public function create() {
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel  = new EventCrudViewModel(null, 'create', $categories);

        return view('admin.crud.form', $viewModel->toArray());
    }

    public function edit($id)
    {
        $event = Event::with('eventTicketTypes.ticketType')->findOrFail($id);
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $ticketTypes = \App\Models\TicketType::orderBy('name')->get();
        $organizers = \App\Models\User::whereIn('role', ['organizer', 'admin'])->pluck('name', 'id');

        $ticketTypesData = $ticketTypes->map(function ($t) {
            return ['id' => (string) $t->id, 'name' => $t->name];
        })->values();

        $eventTicketTypesData = $event->eventTicketTypes->map(function ($tt) {
            return [
                'id'             => $tt->id,
                'ticket_type_id' => (string) $tt->ticket_type_id,
                'price'          => $tt->price,
                'capacity'       => $tt->capacity,
                'editMode'       => false,
                'isNew'          => false,
            ];
        })->values();

        $viewModel = new ManageEventViewModel($event, 'index', $categories, $ticketTypes, $organizers, $ticketTypesData, $eventTicketTypesData);

        return view('admin.event', $viewModel->toArray());
    }

    public function update($id, Request $request)
    {
        $event = Event::findOrFail($id);
        $event->update($request->only([
            'title', 'description', 'location', 'start_time', 'end_time',
            'status', 'quota', 'category_id',
        ]));

        return redirect('/admin/events')->with('success', 'Event updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_time'  => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',
            'end_time'    => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'quota'       => 'required|integer|min:1',
        ]);

        Event::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'location'    => $request->location,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => 'preparation',
            'quota'       => $request->quota,
        ]);

        return redirect('/admin/events')->with('success', 'Event created successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect('/admin/events')->with('success', 'Event deleted successfully.');
    }
}
