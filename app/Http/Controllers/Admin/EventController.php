<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;
use App\Models\Event;
use App\ViewModels\EventCrudViewModel;

class EventController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');
        $rows = Event::when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $viewModel = new EventCrudViewModel($rows);

        return view('admin.crud.index', $viewModel->toArray());
    }

    public function edit($id) {
        $event = Event::find($id);
        $viewModel = new ManageEventViewModel($event);

        return view('admin.event', $viewModel->toArray());
    }

    public function update($id, Request $request) {
        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect('/admin/events')->with('success', 'Event updated successfully.');
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quota' => 'required|integer|min:1',
        ]);

        Event::create(
            [
                'description'=> $request->description,
                'title'=> $request->title,
                'type'=> $request->type,
                'location'=> $request->location,
                'start_time'=> $request->start_time,
                'end_time'=> $request->end_time,
                'status'=> 'preparation',
                'quota'=> $request->quota,
            ]
        );

        return redirect('/admin/events')->with('success', 'Event created successfully.');
    }

    public function destroy($id) {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect('/admin/events')->with('success', 'Event deleted successfully.');
    }
}
