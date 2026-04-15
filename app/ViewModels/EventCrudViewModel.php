<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use App\Models\User;

class EventCrudViewModel implements Arrayable
{
    private $events;
    private $action;
    private $categories;

    public function __construct($events, $action = 'index', $categories = [])
    {
        $this->events     = $events;
        $this->action     = $action;
        $this->categories = $categories;
    }

    public function toArray(): array
    {
        return [
            'title'     => $this->action == 'index' ? 'Events' : ($this->action == 'create' ? 'Create Event' : 'Edit Event: ' . $this->events->title),
            'columns'   => $this->columns(),
            'rows'      => $this->events,
            'filters'   => $this->filters(),
            'createUrl' => '/manage/events/create',
            'editUrl'   => '/manage/events',
            'backUrl'   => '/manage/events',
            'action'    => $this->action == 'create' ? '/manage/events/create' : ($this->action == 'edit' ? '/manage/events/' . $this->events->id : '/manage/events'),
            'method'    => $this->action == 'edit' ? 'PUT' : 'POST',
            'item'      => $this->events,
            'fields'    => $this->fields(),
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'title',       'label' => 'Event Name',  'type' => 'text',           'required' => true, 'wide' => true],
            ['name' => 'category_id', 'label' => 'Category',    'type' => 'select',          'options' => $this->categories, 'required' => true],
            ['name' => 'quota',       'label' => 'Quota',       'type' => 'number'],
            ['name' => 'start_time',  'label' => 'Start Time',  'type' => 'datetime-local',  'required' => true],
            ['name' => 'end_time',    'label' => 'End Time',    'type' => 'datetime-local',  'required' => true],
            ['name' => 'location',    'label' => 'Location',    'type' => 'text',            'required' => true, 'wide' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ['name' => 'banner_path', 'label' => 'Event Banner',  'type' => 'file', 'wide' => true],
            ['name' => 'user_id', 'label' => 'Organizer', 'type' => 'select', 'options' => User::where('role', 'organizer')->get()->pluck('name', 'id')],
        ];
    }

    private function filters(): array
    {
        return [
            'status' => [
                'label'   => 'Status',
                'options' => \App\Models\Event::getStatuses(),
            ],
            'date_from' => [
                'label' => 'Start Date (From)',
                'type'  => 'date',
            ],
            'date_to' => [
                'label' => 'Start Date (To)',
                'type'  => 'date',
            ]
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'category.name', 'label' => 'Category', 'sortable' => false],
            ['key' => 'description', 'label' => 'Description', 'sortable' => false],
            ['key' => 'location', 'label' => 'Location'],
            ['key' => 'start_time', 'label' => 'Start Time'],
            ['key' => 'end_time', 'label' => 'End Time'],
            ['key' => 'quota', 'label' => 'Quota'],
            ['key' => 'status', 'label' => 'Status'],
        ];
    }
}
