<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\ViewModels\EventCrudViewModel;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');
        $rows = Event::where('user_id', Auth::id())
            ->with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                          ->orWhere('location', 'like', "%{$search}%")
                          ->orWhereHas('category', function ($sub) use ($search) {
                              $sub->where('name', 'like', "%{$search}%");
                          });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel  = new EventCrudViewModel($rows, 'index', $categories);

        return view('organizer.crud.index', $viewModel->toArray());
    }

    public function edit($id)
    {
        $event      = Event::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel  = new ManageEventViewModel($event, 'index', $categories);

        return view('organizer.event', $viewModel->toArray());
    }

    public function update($id, Request $request)
    {
        $event = Event::where('user_id', Auth::id())->findOrFail($id);
        $event->update($request->only([
            'title', 'description', 'location', 'start_time', 'end_time',
            'status', 'quota', 'category_id',
        ]));

        return redirect('/organizer/events')->with('success', 'Event updated successfully.');
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
            'user_id'     => Auth::id(),
        ]);

        return redirect('/organizer/events')->with('success', 'Event created successfully.');
    }

    public function destroy($id)
    {
        $event = Event::where('user_id', Auth::id())->findOrFail($id);
        $event->delete();

        return redirect('/organizer/events')->with('success', 'Event deleted successfully.');
    }
}
