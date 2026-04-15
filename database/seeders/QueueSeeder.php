<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::whereIn('status', ['ongoing', 'preparation', 'pending'])->get();
        $users = User::where('role', 'user')->pluck('id')->toArray();

        if (empty($users)) {
            return;
        }

        foreach ($events as $event) {
            $shuffled = collect($users)->shuffle()->values();

            $queuedCount    = min(rand(3, 6), count($shuffled));
            $waitlistedCount = min(rand(2, 4), count($shuffled) - $queuedCount);
            $purchasedCount = min(rand(3, 8), count($shuffled) - $queuedCount - $waitlistedCount);
            $canceledCount  = min(rand(1, 3), count($shuffled) - $queuedCount - $waitlistedCount - $purchasedCount);

            $index = 0;

            for ($i = 0; $i < $queuedCount && $index < count($shuffled); $i++, $index++) {
                Queue::create([
                    'event_id' => $event->id,
                    'user_id'  => $shuffled[$index],
                    'status'   => Queue::STATUS_QUEUED,
                ]);
            }

            for ($i = 0; $i < $waitlistedCount && $index < count($shuffled); $i++, $index++) {
                Queue::create([
                    'event_id' => $event->id,
                    'user_id'  => $shuffled[$index],
                    'status'   => Queue::STATUS_WAITLISTED,
                ]);
            }

            for ($i = 0; $i < $purchasedCount && $index < count($shuffled); $i++, $index++) {
                Queue::create([
                    'event_id' => $event->id,
                    'user_id'  => $shuffled[$index],
                    'status'   => Queue::STATUS_PURCHASED,
                ]);
            }

            for ($i = 0; $i < $canceledCount && $index < count($shuffled); $i++, $index++) {
                Queue::create([
                    'event_id' => $event->id,
                    'user_id'  => $shuffled[$index],
                    'status'   => Queue::STATUS_CANCELED,
                ]);
            }
        }
    }
}
