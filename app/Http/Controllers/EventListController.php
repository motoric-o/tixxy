<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventListController extends Controller
{
    /**
     * Display the public events listing page with search & filtering.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();

        $query = Event::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%')
                  ->orWhere('location', 'ilike', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $events = $query->orderBy('start_time', 'asc')
                        ->paginate(6)
                        ->withQueryString();

        return view('events', compact('events', 'categories'));
    }
}
