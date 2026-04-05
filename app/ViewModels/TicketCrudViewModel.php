<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;

class TicketCrudViewModel implements Arrayable
{
    private $tickets;
    private $action;

    public function __construct($tickets, $action = 'index')
    {
        $this->tickets = $tickets;
        $this->action = $action;
    }

    public function toArray(): array
    {
        $isIndex = $this->action === 'index';
        
        return [
            'title' => $isIndex ? 'Tickets' : 'Edit Ticket',
            'subtitle' => $isIndex ? 'Manage all event tickets' : 'Edit Ticket #' . $this->tickets->id,
            'columns' => $this->columns(),
            'rows' => $this->tickets,
            'filters' => $this->filters(),
            'createUrl' => null, // Tickets are usually created via orders
            'editUrl' => '/admin/tickets',
            'backUrl' => '/admin/tickets',
            'action' => $this->action === 'index' ? '/admin/tickets' : '/admin/tickets/' . ($this->tickets->id ?? ''),
            'method' => $this->action === 'edit' ? 'PUT' : 'POST',
            'item' => $this->tickets,
            'fields' => [
                ['name' => 'is_scanned', 'label' => 'Status', 'type' => 'select', 'options' => [0 => 'Active', 1 => 'Scanned'], 'required' => true],
            ],
            'detailFields' => $this->action === 'edit' ? $this->detailFields() : [],
        ];
    }

    private function detailFields(): array
    {
        $ticket = $this->tickets;
        $order = $ticket->order;

        return [
            ['label' => 'Ticket ID',    'value' => '#' . $ticket->id],
            ['label' => 'QR Code Hash',  'value' => $ticket->qr_code_hash],
            ['label' => 'Status',       'value' => $ticket->is_scanned ? 'Scanned' : 'Active'],
            ['label' => 'Order',        'value' => $order ? '#' . $order->id : 'N/A', 'url' => $order ? '/admin/orders/' . $order->id . '/edit' : null],
            ['label' => 'Event',        'value' => $order->event->title ?? 'N/A'],
            ['label' => 'Customer',     'value' => $order->user->name ?? 'N/A'],
            ['label' => 'Customer Email', 'value' => $order->user->email ?? 'N/A'],
            ['label' => 'Generated At', 'value' => $ticket->created_at->format('d M Y H:i')],
        ];
    }

    private function filters(): array
    {
        return [
            'is_scanned' => [
                'label' => 'Status',
                'options' => [
                    '0' => 'Active',
                    '1' => 'Scanned'
                ]
            ]
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'qr_code_hash', 'label' => 'QR Hash'],
            ['key' => 'is_scanned', 'label' => 'Status'], // Will be mapped in controller if needed or just display 0/1 for now
            ['key' => 'order.event.title', 'label' => 'Event'],
            ['key' => 'order.user.name', 'label' => 'Customer'],
            ['key' => 'created_at', 'label' => 'Date'],
        ];
    }
}
