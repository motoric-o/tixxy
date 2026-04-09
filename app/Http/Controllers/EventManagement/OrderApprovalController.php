<?php

namespace App\Http\Controllers\EventManagement;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderApprovalController extends Controller
{
    public function approve($id)
    {
        $order = Order::with(['user', 'event', 'orderDetails.ticket', 'orderDetails.eventTicketType.ticketType'])
            ->when(Auth::user()->role === 'organizer', function ($q) {
                $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
            })
            ->findOrFail($id);

        return view('admin.approve', compact('order'));
    }

    public function handleApprove($id)
    {
        $order = Order::when(Auth::user()->role === 'organizer', function ($q) {
            $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
        })
            ->findOrFail($id);

        $order->update(['status' => 'completed']);

        // Send order confirmation email
        app(EmailController::class)->sendOrderEmail($id);

        return redirect('/manage/orders')->with('success', 'Order #' . str_pad($id, 6, '0', STR_PAD_LEFT) . ' has been approved and confirmation email sent.');
    }

    public function handleDecline($id)
    {
        $order = Order::when(Auth::user()->role === 'organizer', function ($q) {
            $q->whereHas('event', fn($sub) => $sub->where('user_id', Auth::id()));
        })
            ->findOrFail($id);

        // Delete payment proof from storage
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $order->update([
            'payment_proof' => null,
            'status' => 'pending',
        ]);

        return redirect('/manage/orders')->with('success', 'Order #' . str_pad($id, 6, '0', STR_PAD_LEFT) . ' has been declined and payment proof removed.');
    }
}
