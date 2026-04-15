<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use App\Models\User;
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
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        
        $sort = request('sort', 'start_time');
        $direction = request('direction', 'asc');

        $rows = Event::with(['category', 'organizer'])
            ->when(Auth::user()->role === 'organizer', fn($q) => $q->where('user_id', Auth::id()))
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
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('start_time', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('start_time', '<=', $dateTo);
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel  = new EventCrudViewModel($rows, 'index', $categories);

        return view('admin.crud.index', $viewModel->toArray());
    }

    public function create()
    {
        if (Auth::user()->role === 'organizer') abort(403, 'Unauthorized action.');
        
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel  = new EventCrudViewModel(null, 'create', $categories);

        return view('admin.crud.form', $viewModel->toArray());
    }

    public function store(Request $request)
    {
        if (Auth::user()->role === 'organizer') abort(403, 'Unauthorized action.');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'start_time'  => 'required|date_format:Y-m-d\TH:i|after_or_equal:today',
            'end_time'    => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'location'    => 'required|string|max:255',
            'description' => 'nullable|string',
            'quota'       => 'required|integer|min:1',
            'banner_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);


        $data['status'] = 'pending';
        $data['user_id'] = Auth::user()->role === 'admin' ? $request->user_id ?? Auth::id() : Auth::id();

        if ($request->hasFile('banner_path')) {
            $data['banner_path'] = $request->file('banner_path')->store('events/banners', 'public');
        }

        Event::create($data);
        
        return redirect('/manage/events')->with('success', 'Event created successfully.');
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'organizer') abort(403, 'Unauthorized action.');

        $event = Event::when(Auth::user()->role === 'organizer', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);

        if ($event->status === 'ongoing' || $event->orders()->exists()) {
            $reason = $event->status === 'ongoing' ? 'it is currently ongoing' : 'it has associated orders';
            return redirect('/manage/events')->with('error', "Event cannot be deleted because {$reason}.");
        }

        $event->delete();

        return redirect('/manage/events')->with('success', 'Event deleted successfully.');
    }
}
