<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\EventManagement\EventPanelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Organizer\EventController as OrganizerEventController;
use App\Http\Controllers\Organizer\OrderController as OrganizerOrderController;
use App\Http\Controllers\Organizer\UserController as OrganizerUserController;


use App\Models\Event;

/*
 * No Role
 */
Route::get('/', function () {
    $musicCount = Event::where('category_id', 1)->count();
    $techCount = Event::where('category_id', 2)->count();
    $artCount = Event::where('category_id', 3)->count();
    $sportsCount = Event::where('category_id', 4)->count();

    return view('home', compact('musicCount', 'techCount', 'artCount', 'sportsCount'));
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

    // ticketing
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
});

/*
 * Admin Middleware
 */
Route::middleware(['auth', 'role:organizer,admin'])->group (function () {
    // --- Events CRUD ---
    Route::get('/events/manage/{id}', [EventPanelController::class, 'index']);
    Route::put('/events/manage/{id}', [EventPanelController::class, 'update']);

    Route::get('/organizer/dashboard', [OrganizerDashboardController::class, 'index'])->name('organizer.home');
    Route::get('/organizer/events', [OrganizerEventController::class, 'index']);
    

    Route::get('/admin/events/create', function () {
        $categories = \App\Models\Category::orderBy('name')->pluck('name', 'id');
        return view('admin.crud.form', [
            'title' => 'Create Event',
            'fields' => [
                ['name' => 'title',       'label' => 'Event Title', 'type' => 'text',           'placeholder' => 'Enter event name', 'required' => true],
                ['name' => 'category_id', 'label' => 'Category',    'type' => 'select',          'options' => $categories, 'required' => true],
                ['name' => 'start_time',  'label' => 'Start Time',  'type' => 'datetime-local',  'required' => true],
                ['name' => 'end_time',    'label' => 'End Time',    'type' => 'datetime-local',  'required' => true],
                ['name' => 'location',    'label' => 'Location',    'type' => 'text',            'placeholder' => 'Enter location', 'required' => true],
                ['name' => 'description', 'label' => 'Description', 'type' => 'textarea',        'placeholder' => 'Describe the event...'],
                ['name' => 'quota',       'label' => 'Quota',       'type' => 'number',          'required' => true],
            ],
            'action' => '/admin/events',
            'backUrl' => '/admin/events',
        ]);
    });

    Route::post('/admin/events', [EventController::class, 'store']);
    Route::delete('/admin/events/{id}', [EventController::class, 'destroy']);

    Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
    Route::put('/admin/events/{id}', [EventController::class, 'update']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.home');

    // --- Admin Categories CRUD ---
    Route::get('/admin/categories',            [CategoryController::class, 'index']);
    Route::get('/admin/categories/create',     [CategoryController::class, 'create']);
    Route::post('/admin/categories',           [CategoryController::class, 'store']);
    Route::get('/admin/categories/{id}/edit',  [CategoryController::class, 'edit']);
    Route::put('/admin/categories/{id}',       [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}',    [CategoryController::class, 'destroy']);

    // --- Admin Users CRUD ---
    Route::get('/admin/users', [UserController::class, 'index']);

    Route::get('/admin/users/create', function () {
        return view('admin.crud.form', [
            'title' => 'Create User',
            'fields' => [
                ['name' => 'name', 'label' => 'Full Name', 'type' => 'text', 'placeholder' => 'Enter full name', 'required' => true],
                ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'placeholder' => 'Enter email address', 'required' => true],
                ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'placeholder' => 'Enter password', 'required' => true],
                ['name' => 'role', 'label' => 'Role', 'type' => 'select', 'options' => ['admin' => 'Admin', 'organizer' => 'Organizer'], 'required' => true],
            ],
            'action' => '/admin/users',
            'backUrl' => '/admin/users',
        ]);
    });

    Route::post('/admin/users', [UserController::class, 'store']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);

    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);

    Route::get('/admin/orders', [OrderController::class, 'index']);

    Route::get('/admin/orders/{id}/edit', [OrderController::class, 'edit']);
    Route::put('/admin/orders/{id}', [OrderController::class, 'update']);

    Route::get('/admin/events', [EventController::class, 'index']);
});

require __DIR__ . '/auth.php';
