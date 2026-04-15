<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\ViewModels\EventPerformanceViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventPerformanceController extends Controller
{
    /**
     * Show the performance analytics dashboard for a specific event.
     */
    public function index($id)
    {
        // Pass the full event list for the event-switcher dropdown
        $events = Event::orderBy('start_time', 'desc')->get(['id', 'title', 'status']);

        $viewModel = new EventPerformanceViewModel($events, $id, '30d');
        return view('admin.event-performance', $viewModel->toArray());
    }

    /**
     * Return AJAX JSON payload for the dashboard charts & metrics.
     *
     * @TODO Implement this method once the backend logic is ready.
     *       Expected query params: range (24h | 7d | 30d | all)
     */
    public function data(Request $request, $id)
    {
        $range = $request->get('range', 'all');
        $events = Event::orderBy('start_time', 'desc')->get(['id', 'title', 'status']);

        $viewModel = new EventPerformanceViewModel($events, $id, $range);

        return response()->json($viewModel->toArray());
    }
}
