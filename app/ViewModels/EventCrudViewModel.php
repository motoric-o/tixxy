<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class EventCrudViewModel implements Arrayable
{
    private $events;
    private $action;

    public function __construct($events, $action = 'index')
    {
        $this->events = $events;
        $this->action = $action;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->action == 'index' ? 'Events' : 'Edit Event: ' . $this->events->title,
            'columns' => $this->columns(),
            'rows' => $this->events,
            'filters' => $this->filters(),
            'createUrl' => '/admin/events/create',
            'editUrl' => '/admin/events',
            'backUrl' => '/admin/events',
            'action' => $this->action,
            'item' => $this->events,
            'fields' => [
                ['name' => 'title', 'label' => 'Event Name', 'type' => 'text', 'required' => true],
                ['name' => 'start_time', 'label' => 'Start Time', 'type' => 'datetime-local', 'required' => true],
                ['name' => 'end_time', 'label' => 'End Time', 'type' => 'datetime-local', 'required' => true],
                ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'required' => true],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
                ['name' => 'quota', 'label' => 'Quota', 'type' => 'number'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['ongoing' => 'Ongoing', 'completed' => 'Completed', 'canceled' => 'Canceled', 'pending' => 'Pending'], 'required' => true],
            ],
        ];
    }

    private function filters(): array
    {
        return [
            'status' => [
                'label' => 'Status',
                'options' => [
                    'ongoing' => 'Ongoing',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                    'pending' => 'Pending'
                ]
            ]
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'type', 'label' => 'Type'],
            ['key' => 'description', 'label' => 'Description'],
            ['key' => 'location', 'label' => 'Location'],
            ['key' => 'start_time', 'label' => 'Start Time'],
            ['key' => 'end_time', 'label' => 'End Time'],
            ['key' => 'quota', 'label' => 'Quota'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }
}
