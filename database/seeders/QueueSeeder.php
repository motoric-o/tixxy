<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Queue;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    public function run(): void
    {
        $events = Event::whereIn('status', ['ongoing', 'preparation', 'pending'])->get();

        foreach ($events as $event) {
            $waitingCount    = rand(5, 30);
            $completedCount  = rand(10, 50);
            $canceledCount   = rand(2, 10);

            // Waiting entries
            for ($i = 0; $i < $waitingCount; $i++) {
                Queue::create(['event_id' => $event->id, 'status' => 'waiting']);
            }

            // Completed entries
            for ($i = 0; $i < $completedCount; $i++) {
                Queue::create(['event_id' => $event->id, 'status' => 'completed']);
            }

            // Canceled entries
            for ($i = 0; $i < $canceledCount; $i++) {
                Queue::create(['event_id' => $event->id, 'status' => 'canceled']);
            }
        }
    }
}
