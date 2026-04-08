<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * Display the scanner interface.
     */
    public function index()
    {
        return view('admin.scanner');
    }

    /**
     * Validate a scanned QR code hash.
     */
    public function validateHash(Request $request)
    {
        $request->validate([
            'hash' => 'required|string',
        ]);

        $hash = $request->input('hash');
        
        $ticket = Ticket::with(['order.user', 'order.event'])
            ->where('qr_code_hash', $hash)
            ->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Ticket: Code not found in system.'
            ], 404);
        }

        if ($ticket->is_scanned) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Already Scanned: This ticket was used at ' . $ticket->updated_at->format('H:i:s'),
                'ticket' => $ticket
            ]);
        }

        if ($ticket->order->status !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Order: Payment for this ticket is not completed.',
                'ticket' => $ticket
            ]);
        }

        // Mark as scanned
        $ticket->update(['is_scanned' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket Validated Successfully!',
            'ticket' => $ticket,
            'attendee' => $ticket->order->user->name,
            'event' => $ticket->order->event->title
        ]);
    }
}
