<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Order;
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
            
        $order->update($request->all());

        return redirect('/manage/orders')->with('success', 'Order updated successfully.');
    }
}
