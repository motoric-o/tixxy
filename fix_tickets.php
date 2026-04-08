<?php
use App\Models\Order;
use App\Models\Ticket;

$orders = Order::where('status', 'completed')->get();
foreach ($orders as $order) {
    $order->loadMissing('orderDetails');
    foreach ($order->orderDetails as $detail) {
        $existing = Ticket::where('order_id', $order->id)->count();
        $toCreate = $detail->quantity - $existing;
        for ($i = 0; $i < $toCreate; $i++) {
            Ticket::create(['order_id' => $order->id]);
            if ($order->event) {
                // Ensure quota doesn't go below 0 if it already is
                if ($order->event->quota > 0) {
                    $order->event->decrement('quota');
                }
            }
        }
    }
}
echo "Tickets generated for completed orders.\n";
