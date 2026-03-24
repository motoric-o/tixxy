<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class ManageEventViewModel implements Arrayable
{
    private $event;
    private $action;

    public function __construct($event, $action = 'index')
    {
        $this->event = $event;
        $this->action = $action;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->action == 'index' ? 'Manage Event: ' . $this->event->title : 'Edit Event: ' . $this->event->title,
            'backUrl' => '/admin/events',
            'action' => $this->action,
            'item' => $this->event,
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
}
