<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;

class EventPanelController extends Controller
{
    public function index($id)
    {
        $event = Event::where('id', $id)->first();
        $viewModel = new ManageEventViewModel($event);
        return view('admin.event', $viewModel->toArray());
    }

    public function update($id, Request $request) {
        $event = Event::where('id', $id)->first();
        $event->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $viewModel = new ManageEventViewModel($event);
        return view('admin.event', $viewModel->toArray());
    }
}
