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
            'title' => $this->action == 'index' ? 'Orders' : 'Edit Order #' . $this->orders->id,
            'columns' => $this->columns(),
            'rows' => $this->orders,
            'filters' => $this->filters(),
            'createUrl' => '/admin/orders/create',
            'editUrl' => '/admin/orders',
            'backUrl' => '/admin/orders',
            'action' => $this->action,
            'item' => $this->orders,
            'fields' => [
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Pending', 'completed' => 'Completed', 'canceled' => 'Canceled'], 'required' => true],
            ],
        ];
    }

    private function filters(): array
    {
        return [
            'status' => [
                'label' => 'Status',
                'options' => [
                    'pending' => 'Pending',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled'
                ]
            ]
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'amount', 'label' => 'Amount'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'user.name', 'label' => 'User'],
            ['key' => 'event.title', 'label' => 'Event'],
            ['key' => 'created_at', 'label' => 'Created At'],
        ];
    }
}
