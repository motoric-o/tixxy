<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizers = User::where('role', 'organizer')->get();
        $categories = Category::all()->keyBy('name');

        $events = [
            // Ongoing
            [
                'title'       => 'Jakarta Music Festival 2026',
                'description' => 'A three-day open-air music festival featuring local and international artists.',
                'category'    => 'Music',
                'location'    => 'Gelora Bung Karno, Jakarta',
                'start_time'  => now()->subHours(5),
                'end_time'    => now()->addDays(2),
                'status'      => 'ongoing',
                'quota'       => 500,
                'organizer'   => 0,
            ],
            [
                'title'       => 'TechConf Indonesia',
                'description' => 'Annual technology conference covering AI, cloud, and startup ecosystems.',
                'category'    => 'Conference',
                'location'    => 'Jakarta Convention Center',
                'start_time'  => now()->subHours(2),
                'end_time'    => now()->addHours(6),
                'status'      => 'ongoing',
                'quota'       => 300,
                'organizer'   => 1,
            ],
            // Upcoming / preparation
            [
                'title'       => 'Bandung Food & Art Fair',
                'description' => 'A weekend market celebrating local cuisine and visual arts.',
                'category'    => 'Exhibition',
                'location'    => 'Gedung Sate, Bandung',
                'start_time'  => now()->addDays(3),
                'end_time'    => now()->addDays(5),
                'status'      => 'preparation',
                'quota'       => 200,
                'organizer'   => 2,
            ],
            [
                'title'       => 'Startup Pitch Night',
                'description' => 'Founders pitch their startups to a panel of investors.',
                'category'    => 'Business',
                'location'    => 'CoHive Space, Jakarta',
                'start_time'  => now()->addDays(7),
                'end_time'    => now()->addDays(7)->addHours(4),
                'status'      => 'preparation',
                'quota'       => 100,
                'organizer'   => 0,
            ],
            [
                'title'       => 'Bali Yoga Retreat',
                'description' => 'A weekend wellness retreat combining yoga, meditation, and healthy dining.',
                'category'    => 'Wellness',
                'location'    => 'Ubud, Bali',
                'start_time'  => now()->addDays(14),
                'end_time'    => now()->addDays(16),
                'status'      => 'pending',
                'quota'       => 80,
                'organizer'   => 1,
            ],
            // Completed (for financial data)
            [
                'title'       => 'New Year Countdown Gala 2026',
                'description' => 'Exclusive year-end gala with live DJ sets and fireworks.',
                'category'    => 'Party',
                'location'    => 'Kuta Beach, Bali',
                'start_time'  => now()->subMonths(3)->startOfMonth(),
                'end_time'    => now()->subMonths(3)->startOfMonth()->addHours(6),
                'status'      => 'completed',
                'quota'       => 400,
                'organizer'   => 2,
            ],
            [
                'title'       => 'Indonesian Film Awards',
                'description' => 'Annual ceremony recognising the best in Indonesian cinema.',
                'category'    => 'Awards',
                'location'    => 'Taman Ismail Marzuki, Jakarta',
                'start_time'  => now()->subMonths(2)->startOfMonth(),
                'end_time'    => now()->subMonths(2)->startOfMonth()->addHours(4),
                'status'      => 'completed',
                'quota'       => 250,
                'organizer'   => 0,
            ],
            [
                'title'       => 'Gaming Expo Surabaya',
                'description' => 'Esports tournaments and gaming expos for enthusiasts.',
                'category'    => 'Gaming',
                'location'    => 'Grand City Mall, Surabaya',
                'start_time'  => now()->subMonth()->startOfMonth(),
                'end_time'    => now()->subMonth()->startOfMonth()->addDays(2),
                'status'      => 'completed',
                'quota'       => 350,
                'organizer'   => 1,
            ],
        ];

        $ticketTypes = TicketType::all();

        foreach ($events as $data) {
            $event = Event::create([
                'title'       => $data['title'],
                'description' => $data['description'],
                'category_id' => $categories[$data['category']]->id,
                'location'    => $data['location'],
                'start_time'  => $data['start_time'],
                'end_time'    => $data['end_time'],
                'status'      => $data['status'],
                'quota'       => $data['quota'],
                'user_id'     => $organizers[$data['organizer']]->id,
            ]);

            // Attach 2-3 ticket types per event
            $selectedTypes = $ticketTypes->random(rand(2, 3));
            $prices = [75000, 150000, 300000, 50000, 200000];
            shuffle($prices);

            foreach ($selectedTypes as $i => $type) {
                EventTicketType::create([
                    'event_id'       => $event->id,
                    'ticket_type_id' => $type->id,
                    'price'          => $prices[$i],
                    'capacity'       => intval($data['quota'] / $selectedTypes->count()),
                ]);
            }
        }
    }
}
