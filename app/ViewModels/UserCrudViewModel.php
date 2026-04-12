<?php

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class UserCrudViewModel implements Arrayable
{
    private $users;
    private $action;

    public function __construct($users, $action = 'index')
    {
        $this->users = $users;
        $this->action = $action;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->action == 'index' ? 'Users' : ($this->action == 'create' ? 'Create User' : 'Edit User: ' . $this->users->name),
            'columns' => $this->columns(),
            'rows' => $this->users,
            'filters' => $this->filters(),
            'createUrl' => '/manage/users/create',
            'editUrl' => '/manage/users',
            'backUrl' => '/manage/users',
            'action'    => $this->action == 'create' ? '/manage/users/create' : ($this->action == 'edit' ? '/manage/users/' . $this->users->id : '/manage/users'),
            'method'    => $this->action == 'edit' ? 'PUT' : 'POST',
            'item' => $this->users,
            'fields' => $this->fields(),
            'detailFields' => $this->action === 'edit' ? $this->detailFields() : [],
        ];
    }

    private function detailFields(): array
    {
        $user = $this->users;
        return [
            ['label' => 'User ID',           'value' => '#' . $user->id],
            ['label' => 'Events Organized',   'value' => $user->events_count ?? $user->events()->count()],
            ['label' => 'Orders Placed',      'value' => $user->orders_count ?? $user->orders()->count()],
        ];
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => ['organizer' => 'Organizer', 'user' => 'User'], 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'required' => true],
        ];
    }

    private function filters(): array
    {
        return [
            'role' => [
                'label' => 'Role',
                'options' => [
                    'admin' => 'Admin',
                    'organizer' => 'Organizer',
                    'user' => 'User'
                ]
            ],
            'date_from' => [
                'label' => 'Joined Date (From)',
                'type'  => 'date',
            ],
            'date_to' => [
                'label' => 'Joined Date (To)',
                'type'  => 'date',
            ]
        ];
    }

    private function columns(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'created_at', 'label' => 'Joined'],
        ];
    }
}
