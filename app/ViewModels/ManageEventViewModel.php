<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;

class ManageEventViewModel implements Arrayable
{
    private $event;
    private $action;
    private $categories;
    private $ticketTypes;
    private $organizers;
    private $ticketTypesData;
    private $eventTicketTypesData;
    private $statuses;

    public function __construct($event, $action = 'index', $categories = [], $ticketTypes = [], $organizers = [], $ticketTypesData = [], $eventTicketTypesData = [])
    {
        $this->event               = $event;
        $this->action              = $action;
        $this->categories          = $categories;
        $this->ticketTypes         = $ticketTypes;
        $this->organizers          = $organizers;
        $this->ticketTypesData     = $ticketTypesData;
        $this->eventTicketTypesData = $eventTicketTypesData;
        $this->statuses             = \App\Models\Event::getStatuses();
    }

    public function toArray(): array
    {
        return [
            'title'                => $this->action == 'index' ? 'Manage Event: ' . ($this->event?->title ?? 'Unknown') : 'Edit Event: ' . ($this->event?->title ?? 'Unknown'),
            'backUrl'              => '/manage/events',
            'action'               => $this->action,
            'item'                 => $this->event,
            'categories'           => $this->categories,
            'ticketTypes'          => $this->ticketTypes,
            'organizers'           => $this->organizers,
            'ticketTypesData'      => $this->ticketTypesData,
            'eventTicketTypesData' => $this->eventTicketTypesData,
            'fields'               => $this->fields(),
            'statuses'             => $this->statuses,
        ];
    }

    private function fields() : array {
        return [
            ['name' => 'title',       'label' => 'Event Name',  'type' => 'text',          'required' => true],
            ['name' => 'user_id',     'label' => 'Organizer',   'type' => 'select',        'options' => $this->organizers],
            ['name' => 'category_id', 'label' => 'Category',    'type' => 'select',         'options' => $this->categories, 'required' => true],
            ['name' => 'start_time',  'label' => 'Start Time',  'type' => 'datetime-local', 'required' => true],
            ['name' => 'end_time',    'label' => 'End Time',    'type' => 'datetime-local', 'required' => true],
            ['name' => 'location',    'label' => 'Location',    'type' => 'text',           'required' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea'],
            ['name' => 'quota',       'label' => 'Quota',       'type' => 'number'],
            ['name' => 'status',      'label' => 'Status',      'type' => 'select',         'options' => $this->statuses, 'required' => true],
        ];
    }
}
