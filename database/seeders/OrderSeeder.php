<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $events    = Event::with('eventTicketTypes')->get();

        // Spread seeded orders across the last 6 months for meaningful chart data
        $monthOffsets = [5, 5, 4, 4, 4, 3, 3, 2, 2, 1, 1, 0, 0, 0, 0, 0, 0, 0];

        foreach ($events as $index => $event) {
            $ettCollection = $event->eventTicketTypes;
            if ($ettCollection->isEmpty()) {
                continue;
            }

            // Completed events get more orders; ongoing/upcoming get a few pending
            $orderCount = in_array($event->status, ['completed']) ? rand(10, 20) : rand(1, 5);
            $orderStatus = $event->status === 'completed' ? 'completed' : (rand(0, 1) ? 'pending' : 'canceled');

            for ($i = 0; $i < $orderCount; $i++) {
                $customer = $customers->random();
                $ett      = $ettCollection->random();
                $quantity = rand(1, 3);
                $amount   = $ett->price * $quantity;

                // Determine created_at spread for chart realism
                $monthsAgo    = $monthOffsets[array_rand($monthOffsets)];
                $createdAt    = now()->subMonths($monthsAgo)->addDays(rand(0, 27));

                $localStatus = $event->status === 'completed'
                    ? 'completed'
                    : ($i === 0 ? 'pending' : 'completed');

                $order = Order::create([
                    'amount'     => $amount,
                    'status'     => $localStatus,
                    'user_id'    => $customer->id,
                    'event_id'   => $event->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                OrderDetail::create([
                    'order_id'             => $order->id,
                    'event_ticket_type_id' => $ett->id,
                    'quantity'             => $quantity,
                    'created_at'           => $createdAt,
                    'updated_at'           => $createdAt,
                ]);

                // Generate QR tickets for each ticket in this order
                for ($t = 0; $t < $quantity; $t++) {
                    Ticket::create([
                        'qr_code_hash' => Str::random(32),
                        'is_scanned'   => $localStatus === 'completed' && rand(0, 1),
                        'order_id'     => $order->id,
                        'created_at'   => $createdAt,
                        'updated_at'   => $createdAt,
                    ]);
                }
            }
        }

        // Add a few more orders this month for freshness
        $ongoingEvents = Event::where('status', 'ongoing')->with('eventTicketTypes')->get();
        foreach ($ongoingEvents as $event) {
            $ettCollection = $event->eventTicketTypes;
            if ($ettCollection->isEmpty()) {
                continue;
            }
            for ($i = 0; $i < rand(3, 7); $i++) {
                $customer = $customers->random();
                $ett      = $ettCollection->random();
                $quantity = rand(1, 2);
                $amount   = $ett->price * $quantity;
                $createdAt = now()->subDays(rand(0, 15));

                $order = Order::create([
                    'amount'     => $amount,
                    'status'     => 'completed',
                    'user_id'    => $customer->id,
                    'event_id'   => $event->id,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                OrderDetail::create([
                    'order_id'             => $order->id,
                    'event_ticket_type_id' => $ett->id,
                    'quantity'             => $quantity,
                    'created_at'           => $createdAt,
                    'updated_at'           => $createdAt,
                ]);

                for ($t = 0; $t < $quantity; $t++) {
                    Ticket::create([
                        'qr_code_hash' => Str::random(32),
                        'is_scanned'   => rand(0, 1),
                        'order_id'     => $order->id,
                        'created_at'   => $createdAt,
                        'updated_at'   => $createdAt,
                    ]);
                }
            }
        }
    }
}
