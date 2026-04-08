<?php

namespace App\Http\Controllers;

use App\Mail\OrderEmail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendOrderEmail(string $id) : void
    {
        $order = Order::with('orderDetails', 'orderDetails.tickets', 'orderDetails.eventTicketType', 'event', 'user')->where('id', $id)->get();
        Mail::to($order->user->email)->send(new OrderEmail($order));
    }
}
