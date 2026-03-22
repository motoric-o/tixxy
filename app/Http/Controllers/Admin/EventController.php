<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $viewModel = new EventCrudViewModel($event);

        return view('admin.crud.form', $viewModel->toArray());
    }

    public function update($id, Request $request) {
        $event = Event::findOrFail($id);
        $event->update($request->all());

        return redirect('/admin/events')->with('success', 'Event updated successfully.');
    }
}
