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
        // Mengambil data event berdasarkan ID
        $event = Event::findOrFail($id);

        return view('payment', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
}
