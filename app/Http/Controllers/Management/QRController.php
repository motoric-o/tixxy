<?php

namespace App\Http\Controllers\Management;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class QRController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('organizer.scanQR');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'qr_code_hash' => 'required|string',
        ]);

        // Cari tiket berdasarkan hash QR code beserta relasi user dan event
        $ticket = Ticket::with(['order.user', 'order.event'])
            ->where('qr_code_hash', $request->qr_code_hash)
            ->first();

        if (!$ticket) {
            return response()->json(['message' => 'Tiket tidak valid atau tidak ditemukan.'], 404);
        }

        // Security check: Organizer hanya boleh scan tiket dari event miliknya sendiri
        if (Auth::user()->role === 'organizer' && $ticket->order->event->user_id !== Auth::id()) {
            return response()->json(['message' => 'Akses ditolak. Anda bukan penyelenggara event ini.'], 403);
        }

        if ($ticket->is_scanned) {
            return response()->json(['message' => 'Tiket sudah pernah digunakan (sudah di-scan).'], 400);
        }

        $ticket->update(['is_scanned' => 1]);

        return response()->json([
            'message' => 'Check-in berhasil! Selamat datang, ' . $ticket->order->user->name,
            'user_name' => $ticket->order->user->name
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
