<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/events', function () {
    return view('events');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

// --- Admin Events CRUD ---
Route::get('/admin/events', function () {
    return view('admin.crud.index', [
        'title' => 'Events',
        'columns' => [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'date', 'label' => 'Date'],
            ['key' => 'location', 'label' => 'Location'],
            ['key' => 'status', 'label' => 'Status'],
        ],
        'rows' => [],
        'createUrl' => '/admin/events/create',
        'editUrl' => '/admin/events',
    ]);
});

Route::get('/admin/events/create', function () {
    return view('admin.crud.form', [
        'title' => 'Create Event',
        'fields' => [
            ['name' => 'name', 'label' => 'Event Name', 'type' => 'text', 'placeholder' => 'Enter event name', 'required' => true],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'placeholder' => 'Enter location', 'required' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Describe the event...'],
            ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'placeholder' => '0'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published', 'cancelled' => 'Cancelled'], 'required' => true],
        ],
        'action' => '/admin/events',
        'backUrl' => '/admin/events',
    ]);
});

Route::get('/admin/events/{id}/edit', function ($id) {
    return view('admin.crud.form', [
        'title' => 'Edit Event',
        'fields' => [
            ['name' => 'name', 'label' => 'Event Name', 'type' => 'text', 'placeholder' => 'Enter event name', 'required' => true],
            ['name' => 'date', 'label' => 'Date', 'type' => 'date', 'required' => true],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'placeholder' => 'Enter location', 'required' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'placeholder' => 'Describe the event...'],
            ['name' => 'price', 'label' => 'Price', 'type' => 'number', 'placeholder' => '0'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'published' => 'Published', 'cancelled' => 'Cancelled'], 'required' => true],
        ],
        'action' => '/admin/events/' . $id,
        'method' => 'PUT',
        'backUrl' => '/admin/events',
        'item' => null, // Replace with DB lookup later
    ]);
});

// --- Admin Users CRUD ---
Route::get('/admin/users', function () {
    return view('admin.crud.index', [
        'title' => 'Users',
        'columns' => [
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            ['key' => 'created_at', 'label' => 'Joined'],
        ],
        'rows' => [],
        'createUrl' => '/admin/users/create',
        'editUrl' => '/admin/users',
    ]);
});

Route::get('/admin/users/create', function () {
    return view('admin.crud.form', [
        'title' => 'Create User',
        'fields' => [
            ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Enter full name', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email address', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'placeholder' => 'Enter password', 'required' => true],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => ['admin' => 'Admin', 'user' => 'User'], 'required' => true],
        ],
        'action' => '/admin/users',
        'backUrl' => '/admin/users',
    ]);
});

Route::get('/admin/users/{id}/edit', function ($id) {
    return view('admin.crud.form', [
        'title' => 'Edit User',
        'fields' => [
            ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Enter full name', 'required' => true],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email address', 'required' => true],
            ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'placeholder' => 'Leave blank to keep current'],
            ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => ['admin' => 'Admin', 'user' => 'User'], 'required' => true],
        ],
        'action' => '/admin/users/' . $id,
        'method' => 'PUT',
        'backUrl' => '/admin/users',
        'item' => null, // Replace with DB lookup later
    ]);
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
