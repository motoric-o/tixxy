<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EventManagement\EventPanelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
 * No Role
 */
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/events', function () {
    return view('events');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
});

/*
 * Auth Middleware
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
 * Admin Middleware
 */
Route::middleware(['auth', 'role:organizer,admin'])->group (function () {
    // --- Events CRUD ---
Route::get('/events/manage/{id}', [EventPanelController::class, 'index']);
Route::put('/events/manage/{id}', [EventPanelController::class, 'update']);

    Route::get('/admin/events', [EventController::class, 'index'])->name('organizer.home');

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

    Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
    Route::put('/admin/events/{id}', [EventController::class, 'update']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.home');

    // --- Admin Users CRUD ---
    Route::get('/admin/users', [UserController::class, 'index']);

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

    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);

    Route::get('/admin/orders', [OrderController::class, 'index']);

    Route::get('/admin/orders/{id}/edit', [OrderController::class, 'edit']);
    Route::put('/admin/orders/{id}', [OrderController::class, 'update']);
});

require __DIR__ . '/auth.php';
