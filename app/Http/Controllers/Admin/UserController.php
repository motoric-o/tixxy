<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $title = 'Users';
        $columns = [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'created_at', 'label' => 'Joined'],
        ];
        $rows = User::all();
        $createUrl = '/admin/users/create';
        $editUrl = '/admin/users';

        return view('admin.crud.index', compact(
            'title',
            'columns',
            'rows',
            'createUrl',
            'editUrl',
        ));
    }
}
