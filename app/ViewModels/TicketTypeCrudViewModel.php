<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;

class TicketTypeCrudViewModel implements Arrayable
{
    private $ticketTypes;
    private $action;
    private $events;

    public function __construct($ticketTypes, $action = 'index')
    {
        $this->ticketTypes = $ticketTypes;
        $this->action      = $action;
    }

    public function toArray(): array
    {
        return [
            'title'     => $this->action == 'index' ? 'Ticket Types' : ($this->action == 'create' ? 'Create Ticket Type' : 'Edit Ticket Type: ' . $this->ticketTypes->name),
            'columns'   => $this->columns(),
            'rows'      => $this->ticketTypes,
            'createUrl' => '/manage/ticket-types/create',
            'editUrl'   => '/manage/ticket-types',
            'backUrl'   => '/manage/ticket-types',
            'action'    => $this->action == 'create' ? '/manage/ticket-types/create' : ($this->action == 'edit' ? '/manage/ticket-types/' . $this->ticketTypes->id : '/manage/ticket-types'),
            'method'    => $this->action == 'edit' ? 'PUT' : 'POST',
            'item'      => $this->ticketTypes,
            'fields'    => $this->fields(),
            'detailFields' => $this->action === 'edit' ? $this->detailFields() : [],
        ];
    }

    private function detailFields(): array
    {
        $ticketType = $this->ticketTypes;
        return [
            ['label' => 'Ticket Type ID',  'value' => '#' . $ticketType->id],
            ['label' => 'Used by Events',   'value' => $ticketType->event_ticket_types_count ?? $ticketType->eventTicketTypes()->count()],
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Ticket Type Name',  'type' => 'text', 'required' => true],
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
        ];
    }
}
