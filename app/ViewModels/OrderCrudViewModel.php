<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class OrderCrudViewModel implements Arrayable
{
    private $orders;
    private $action;

    public function __construct($orders, $action = 'index')
    {
        $this->orders = $orders;
        $this->action = $action;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->action == 'index' ? 'Orders' : 'Edit Order',
            'subtitle' => $this->action == 'index' ? 'Overview' : 'Edit Order #' . ($this->orders?->id ?? '') . " - " . ($this->orders?->event?->title ?? 'Unknown Event'),
            'columns' => $this->columns(),
            'rows' => $this->orders,
            'filters' => $this->filters(),
            'createUrl' => null,
            'editUrl' => '/manage/orders',
            'backUrl' => '/manage/orders',
            'canDelete' => false,
            'action' => $this->action == 'create' ? '/manage/orders/create' : ($this->action == 'edit' ? '/manage/orders/' . ($this->orders?->id ?? '') : '/manage/orders'),
            'method' => $this->action == 'edit' ? 'PUT' : 'POST',
            'item' => $this->orders,
            'fields' => [
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => \App\Models\Order::getStatuses(), 'required' => true],
            ],
            'detailFields' => $this->action !== 'index' ? $this->detailFields() : [],
        ];
    }

    private function detailFields(): array
    {
        $order = $this->orders;
        $details = [
            ['label' => 'Order ID',  'value' => '#' . $order->id],
            ['label' => 'Customer',  'value' => $order->user->name ?? 'N/A'],
            ['label' => 'Email',     'value' => $order->user->email ?? 'N/A'],
            ['label' => 'Event',     'value' => $order->event->title ?? 'N/A'],
            ['label' => 'Amount',    'value' => 'Rp ' . number_format($order->amount, 0, ',', '.')],
        ];

        // Order Items (from orderDetails relationship)
        if ($order->relationLoaded('orderDetails') && $order->orderDetails->count() > 0) {
            $items = $order->orderDetails->groupBy('event_ticket_type_id')->map(function ($group) {
                $first = $group->first();
                return [
                    'Ticket Type' => $first->eventTicketType?->ticketType?->name ?? 'N/A',
                    'Price'       => 'Rp ' . number_format($first->eventTicketType?->price ?? 0, 0, ',', '.'),
                    'Qty'         => $group->count(),
                ];
            })->values()->toArray();

            $details[] = [
                'label' => 'Order Items',
                'type'  => 'table',
                'value' => $items,
            ];
        }

        // Generated Tickets
        if ($order->relationLoaded('tickets') && $order->tickets->count() > 0) {
            $tickets = $order->tickets->map(function ($ticket) {
                return [
                    'label' => substr($ticket->qr_code_hash, 0, 12) . '...',
                    'badge' => $ticket->is_scanned ? 'Scanned' : 'Active',
                    'color' => $ticket->is_scanned ? 'gray' : 'green',
                    'url'   => '/manage/tickets/' . $ticket->id . '/edit',
                ];
            })->toArray();

            $details[] = [
                'label' => 'Tickets (' . $order->tickets->count() . ')',
                'type'  => 'badges',
                'value' => $tickets,
            ];
        }

        return $details;
    }

    private function filters(): array
    {
        return [
            'status' => [
                'label' => 'Status',
                'options' => \App\Models\Order::getStatuses()
            ],
            'date_from' => [
                'label' => 'Order Date (From)',
                'type'  => 'date',
            ],
            'date_to' => [
                'label' => 'Order Date (To)',
                'type'  => 'date',
            ]
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'amount', 'label' => 'Amount'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'user.name', 'label' => 'User', 'sortable' => false],
            ['key' => 'event.title', 'label' => 'Event', 'sortable' => false],
            ['key' => 'created_at', 'label' => 'Ordered At'],
        ];
    }
}
