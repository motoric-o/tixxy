<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Event;
use App\ViewModels\OrderCrudViewModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');
        $rows = Order::with(['user', 'event'])
            ->when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('status', 'like', "%{$search}%")
                      ->orWhereHas('user', function($u) use ($search) {
                          $u->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('event', function($e) use ($search) {
                          $e->where('title', 'like', "%{$search}%");
                      });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        $viewModel = new OrderCrudViewModel($rows);

        return view('admin.crud.index', $viewModel->toArray());
    }

    public function edit($id) {
        $order = Order::with(['user', 'event', 'orderDetails.eventTicketType.ticketType', 'tickets'])
            ->when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);
            
        $viewModel = new OrderCrudViewModel($order, 'edit');
        return view('admin.crud.form', $viewModel->toArray());
    }

    public function update($id, Request $request) {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled',
        ]);

        $order = Order::when(Auth::user()->role === 'organizer', function($q) {
                $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);
            
        if ($request->status === 'completed' && empty($order->payment_proof)) {
            return redirect()->back()->with('error', 'Cannot complete order without payment proof.');
        }

        $order->update($request->all());

        return redirect('/manage/orders')->with('success', 'Order updated successfully.');
    }

    public function eventOrders(Request $request, $id)
    {
        $event = Event::with('eventTicketTypes.ticketType')
            ->when(Auth::user()->role === 'organizer', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->findOrFail($id);

        $query = Order::with(['user', 'orderDetails.eventTicketType.ticketType'])
            ->where('event_id', $id);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ticket_type_id')) {
            $query->whereHas('orderDetails.eventTicketType.ticketType', function ($q) use ($request) {
                $q->where('id', $request->ticket_type_id);
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('has_payment_proof')) {
            if ($request->has_payment_proof == '1') {
                $query->whereNotNull('payment_proof');
            } else {
                $query->whereNull('payment_proof');
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'customer_asc':
                // Assuming you want to sort by user's name
                $query->join('users', 'orders.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc')
                      ->select('orders.*'); // Prevent column collision
                break;
            case 'customer_desc':
                $query->join('users', 'orders.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'desc')
                      ->select('orders.*');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.event-orders', compact('event', 'orders'));
    }
}
