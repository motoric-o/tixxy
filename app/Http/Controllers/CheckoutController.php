<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page for a given event.
     */
    public function index(Request $request): View
    {
        $eventId = $request->input('event_id');

        $event = Event::with('category')->findOrFail($eventId);

        return view('checkout', compact('event'));
    }
}
