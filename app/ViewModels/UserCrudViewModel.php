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
            'title' => $this->action == 'index' ? ($this->action == 'create' ? 'Create User' : 'Users') : 'Edit User: ' . $this->users->name,
            'columns' => $this->columns(),
            'rows' => $this->users,
            'filters' => $this->filters(),
            'createUrl' => '/admin/users/create',
            'editUrl' => '/admin/users',
            'backUrl' => '/admin/users',
            'action' => $this->action,
            'item' => $this->users,
            'fields' => $this->fields(),
        ];
    }

    private function fields() : array {
        return [
            ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => ['admin' => 'Admin', 'user' => 'User'], 'required' => true],
        ];
    }

    private function filters(): array
    {
        return [
            'role' => [
                'label' => 'Role',
                'options' => [
                    'admin' => 'Admin',
                    'user' => 'User'
                ]
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
