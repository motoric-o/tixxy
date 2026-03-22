<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ViewModels\UserCrudViewModel;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search');
        $role = request('role');
        $rows = User::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        $viewModel = new UserCrudViewModel($rows);

        return view('admin.crud.index', $viewModel->toArray());
    }

    public function edit($id) {
        $user = User::find($id);

        return view('admin.crud.form', [
            'title' => 'Edit User: ' . $user->name,
            'action' => '/admin/users/' . $user->id,
            'method' => 'PUT',
            'backUrl' => '/admin/users',
            'item' => $user,
            'fields' => [
                ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'required' => true],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'required' => true],
                ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => ['admin' => 'Admin', 'user' => 'User'], 'required' => true],
            ],
        ]);
    }

    public function update($id, Request $request) {
        $user = User::findOrFail($id);
        
        $data = $request->except('password');
        if($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
        
        $user->update($data);

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }
}
