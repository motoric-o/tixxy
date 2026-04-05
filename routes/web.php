<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\EventManagement\EventPanelController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

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

    Route::get('/admin/events', [EventController::class, 'index'])->name('organizer.home');

    Route::get('/admin/events/create', [EventController::class, 'create']);

    Route::post('/admin/events/create', [EventController::class, 'store']);
    Route::delete('/admin/events/{id}', [EventController::class, 'destroy']);

    Route::get('/admin/events/{id}/edit', [EventController::class, 'edit']);
    Route::put('/admin/events/{id}', [EventController::class, 'update']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.home');

    // --- Admin Categories CRUD ---
    Route::get('/admin/categories',            [CategoryController::class, 'index']);
    Route::get('/admin/categories/create',     [CategoryController::class, 'create']);
    Route::post('/admin/categories/create',    [CategoryController::class, 'store']);
    Route::get('/admin/categories/{id}/edit',  [CategoryController::class, 'edit']);
    Route::put('/admin/categories/{id}',       [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}',    [CategoryController::class, 'destroy']);

    // --- Admin Ticket Types CRUD ---
    Route::get('/admin/ticket-types',            [TicketTypeController::class, 'index']);
    Route::get('/admin/ticket-types/create',     [TicketTypeController::class, 'create']);
    Route::post('/admin/ticket-types/create',    [TicketTypeController::class, 'store']);
    Route::get('/admin/ticket-types/{id}/edit',  [TicketTypeController::class, 'edit']);
    Route::put('/admin/ticket-types/{id}',       [TicketTypeController::class, 'update']);
    Route::delete('/admin/ticket-types/{id}',    [TicketTypeController::class, 'destroy']);

    // --- Admin Users CRUD ---
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::get('/admin/users/create', [UserController::class, 'create']);
    Route::post('/admin/users/create', [UserController::class, 'store']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);

    // --- Admin Orders CRUD ---
    Route::get('/admin/orders', [OrderController::class, 'index']);
    Route::get('/admin/orders/{id}/edit', [OrderController::class, 'edit']);
    Route::put('/admin/orders/{id}', [OrderController::class, 'update']);

    // --- Admin Tickets CRUD ---
    Route::get('/admin/tickets', [AdminTicketController::class, 'index']);
    Route::get('/admin/tickets/{id}/edit', [AdminTicketController::class, 'edit']);
    Route::put('/admin/tickets/{id}', [AdminTicketController::class, 'update']);
    Route::delete('/admin/tickets/{id}', [AdminTicketController::class, 'destroy']);
});

require __DIR__ . '/auth.php';
