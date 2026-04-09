<?php

namespace App\Http\Controllers\Management;

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

    public function create()
    {
        $viewModel = new UserCrudViewModel(null, 'create');

        return view('admin.crud.form', $viewModel->toArray());
    }

    public function edit($id)
    {
        $viewModel = new UserCrudViewModel(User::find($id), 'edit');
        return view('admin.crud.form', $viewModel->toArray());
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,organizer',
        ]);

        $user = User::findOrFail($id);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password_hash'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect('/manage/users')->with('success', 'User updated successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,organizer',
        ]);
        $data = $request->all();

        $data['password'] = bcrypt($request->password);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => $data['password'],
            'role' => $data['role'],
        ]);

        return redirect('/manage/users')->with('success', 'User created successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/manage/users')->with('success', 'User deleted successfully.');
    }
}
