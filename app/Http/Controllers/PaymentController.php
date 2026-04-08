<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Menampilkan halaman pembayaran untuk event tertentu.
     */
    public function show(string $id): View
    {
        // Mengambil data order berdasarkan ID
        $order = \App\Models\Order::with('event')->findOrFail($id);

        return view('payment', compact('order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $order = \App\Models\Order::findOrFail($id);
        $expiryDate = $order->expired_at ?? $order->created_at->addHour();
        
        // Prevent upload if expired
        if (now()->greaterThanOrEqualTo($expiryDate) || $order->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment period has expired or order is invalid.');
        }

        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->update(['payment_proof' => $path]);
        }

        return redirect()->back()->with('success', 'Payment proof submitted successfully!');
    }
}
