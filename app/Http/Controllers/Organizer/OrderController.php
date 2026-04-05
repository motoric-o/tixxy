<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\ViewModels\OrderCrudViewModel;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $search = request('search');
        $status = request('status');
        $rows = Order::with(['user', 'event'])
            ->when($search, function ($query, $search) {
                $query->where('status', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('event', function($q) use ($search) {
                          $q->where('title', 'like', "%{$search}%");
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
        $order = Order::find($id);

        return view('admin.crud.form', [
            'title' => 'Edit Order #' . $order->id,
            'action' => '/admin/orders/' . $order->id,
            'method' => 'PUT',
            'backUrl' => '/admin/orders',
            'item' => $order,
            'fields' => [
                [
                    'name' => 'status', 
                    'label' => 'Status', 
                    'type' => 'select', 
                    'options' => [
                        'pending' => 'Pending', 
                        'completed' => 'Completed', 
                        'canceled' => 'Canceled'
                    ], 
                    'required' => true
                ],
            ],
        ]);
    }

    public function update($id, Request $request) {
        $order = Order::find($id);
        $order->update($request->all());

        return redirect('/admin/orders')->with('success', 'Order updated successfully.');
    }
}
