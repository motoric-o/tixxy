<?php

use App\Http\Controllers\EventManagement\OrderApprovalController;
use App\Http\Controllers\Management\CategoryController;
use App\Http\Controllers\Management\ScannerController;
use App\Http\Controllers\Management\TicketController as ManagementTicketController;
use App\Http\Controllers\Management\DashboardController;
use App\Http\Controllers\Management\EventController;
use App\Http\Controllers\Management\FinanceController;
use App\Http\Controllers\Management\OrderController;
use App\Http\Controllers\Management\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\EventManagement\EventPanelController;
use App\Http\Controllers\Management\TicketTypeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Management\QRController;

use App\Http\Controllers\EventListController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EmailController;

use App\Models\Event;

/*
 * Test Routes
 */
Route::get('/test-email', function () {
    app(EmailController::class)->sendOrderEmail('1');
    return 'Order email sent for order #1!';
});

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

Route::get('/events', [EventListController::class, 'index'])->name('events.index');



/*
 * Auth Middleware
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // checkout & payment flow
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/{id}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{id}', [PaymentController::class, 'store'])->name('payment.store');

    // ticketing
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
});

/*
 * Manage Middleware (Organizer + Admin)
 */
Route::middleware(['auth', 'role:organizer,admin'])->prefix('manage')->group(function () {
    Route::get('/manage-event-orders/{id}', [OrderController::class, 'eventOrders'])->name('manage.orders.event');

    // --- Dashboard ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('manage.home');
    Route::get('/scan', [QRController::class, 'index'])->name('manage.scan');
    Route::post('/tickets/scan', [QRController::class, 'update']);

    // --- Financial ---
    Route::get('/finances', [FinanceController::class, 'index'])->name('manage.finances');

    // --- Scanner ---
    Route::get('/scanner', [ScannerController::class, 'index'])->name('manage.scanner');
    Route::post('/scanner/validate', [ScannerController::class, 'validateHash'])->name('manage.scanner.validate');

    // --- Events CRUD (shared) ---
    Route::get('/events', [EventController::class, 'index'])->name('manage.events');
    Route::get('/events/create', [EventController::class, 'create']);
    Route::post('/events/create', [EventController::class, 'store']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);

    // --- Event Management Panel (edit + update) ---
    Route::get('/events/{id}/edit', [EventPanelController::class, 'index']);
    Route::put('/events/{id}', [EventPanelController::class, 'update']);


    // --- Orders CRUD ---
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);

    // --- Order Approval ---
    Route::get('/orders/{id}/approve', [OrderApprovalController::class, 'approve']);
    Route::post('/orders/{id}/approve', [OrderApprovalController::class, 'handleApprove']);
    Route::post('/orders/{id}/decline', [OrderApprovalController::class, 'handleDecline']);

    // --- Tickets CRUD ---
    Route::get('/tickets', [ManagementTicketController::class, 'index']);
    Route::get('/tickets/{id}/edit', [ManagementTicketController::class, 'edit']);
    Route::put('/tickets/{id}', [ManagementTicketController::class, 'update']);
    Route::delete('/tickets/{id}', [ManagementTicketController::class, 'destroy']);

    // --- Events comparison ----
    Route::get('/events/compare', [EventController::class, 'compare'])->name('manage.events.compare');
});

/*
 * Manage Middleware (Admin Only)
 */
Route::middleware(['auth', 'role:admin'])->prefix('manage')->group(function () {
    // --- Categories CRUD ---
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/create', [CategoryController::class, 'create']);
    Route::post('/categories/create', [CategoryController::class, 'store']);
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // --- Ticket Types CRUD ---
    Route::get('/ticket-types', [TicketTypeController::class, 'index']);
    Route::get('/ticket-types/create', [TicketTypeController::class, 'create']);
    Route::post('/ticket-types/create', [TicketTypeController::class, 'store']);
    Route::get('/ticket-types/{id}/edit', [TicketTypeController::class, 'edit']);
    Route::put('/ticket-types/{id}', [TicketTypeController::class, 'update']);
    Route::delete('/ticket-types/{id}', [TicketTypeController::class, 'destroy']);

    // --- Users CRUD ---
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/create', [UserController::class, 'create']);
    Route::post('/users/create', [UserController::class, 'store']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/users/{id}/edit', [UserController::class, 'edit']);
    Route::put('/users/{id}', [UserController::class, 'update']);
});

require __DIR__ . '/auth.php';
