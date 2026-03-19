<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $title = 'Events';
        $columns = [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'start_time', 'label' => 'Start Time'],
            ['key' => 'end_time', 'label' => 'End Time'],
            ['key' => 'quota', 'label' => 'Quota'],
            // ['key' => 'location', 'label' => 'Location'],
            ['key' => 'status', 'label' => 'Status'],
        ];
        $rows = Event::all();
        $createUrl = '/admin/events/create';
        $editUrl = '/admin/events';

        return view('admin.crud.index', compact(
            'title',
            'columns',
            'rows',
            'createUrl',
            'editUrl',
        ));
    }
}
