<?php

namespace Database\Seeders;

use App\Models\TicketType;
use Illuminate\Database\Seeder;

class TicketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['General Admission', 'VIP', 'VVIP', 'Early Bird', 'Student'];

        foreach ($types as $type) {
            TicketType::create(['name' => $type]);
        }
    }
}
