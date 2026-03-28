<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\ViewModels\ManageEventViewModel;
use Illuminate\Http\Request;

class EventPanelController extends Controller
{
    public function index($id)
    {
        $event = Event::where('id', $id)->first();
        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel = new ManageEventViewModel($event, 'index', $categories);
        return view('admin.event', $viewModel->toArray());
    }

    public function update($id, Request $request) {
        $event = Event::where('id', $id)->first();
        $event->update($request->all());

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        $categories = Category::orderBy('name')->pluck('name', 'id');
        $viewModel = new ManageEventViewModel($event, 'index', $categories);
        return view('admin.event', $viewModel->toArray());
    }
}
