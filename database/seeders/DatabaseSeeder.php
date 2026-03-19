<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters: types → users → events → orders (+ details + tickets) → queues
     */
    public function run(): void
    {
        $this->call([
            TicketTypeSeeder::class,
            UserSeeder::class,
            EventSeeder::class,
            OrderSeeder::class,
            QueueSeeder::class,
        ]);
    }
}
