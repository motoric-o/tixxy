<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\ViewModels\UserCrudViewModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $search = request('search');
        $role = request('role');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        
        $sort = request('sort', 'id');
        $direction = request('direction', 'desc');

        $rows = User::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

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
            'password' => 'nullable|string|min:8',
            'password_confirmation' => 'nullable|string|min:8',
            'date_of_birth' => 'nullable|date',
            'profile_picture_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->password != $request->password_confirmation) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $user = User::findOrFail($id);

        // Self-Demotion Guard
        if (Auth::id() === $user->id && $user->role === 'admin' && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'You cannot demote your own role from the admin panel.');
        }

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
        if (Auth::id() == $id) {
            return redirect('/manage/users')->with('error', 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);

        if ($user->events()->exists() || $user->orders()->exists()) {
            return redirect('/manage/users')->with('error', 'User cannot be deleted because they have associated events or orders.');
        }

        $user->delete();

        return redirect('/manage/users')->with('success', 'User deleted successfully.');
    }
}
