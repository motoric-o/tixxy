<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'role'          => 'admin',
            'name'          => 'Admin Tixxy',
            'email'         => 'admin@tixxy.com',
            'date_of_birth' => '1990-01-01',
            'email_verified_at' => now(),
            'password_hash' => Hash::make('password'),
        ]);

        // Organizers
        $organizers = [
            ['name' => 'Reza Prakasa',   'email' => 'reza@tixxy.com',   'dob' => '1988-05-12'],
            ['name' => 'Liana Santoso',  'email' => 'liana@tixxy.com',  'dob' => '1992-08-23'],
            ['name' => 'Budi Hartono',   'email' => 'budi@tixxy.com',   'dob' => '1985-11-30'],
        ];

        foreach ($organizers as $org) {
            User::create([
                'role'          => 'organizer',
                'name'          => $org['name'],
                'email'         => $org['email'],
                'date_of_birth' => $org['dob'],
                'email_verified_at' => now(),
                'password_hash' => Hash::make('password'),
            ]);
        }

        // Customers
        $customers = [
            ['name' => 'Andi Wijaya',     'email' => 'andi@mail.com',     'dob' => '1995-03-14'],
            ['name' => 'Siti Rahayu',     'email' => 'siti@mail.com',     'dob' => '1997-07-20'],
            ['name' => 'Doni Setiawan',   'email' => 'doni@mail.com',     'dob' => '1993-01-05'],
            ['name' => 'Maya Kusuma',     'email' => 'maya@mail.com',     'dob' => '1999-09-10'],
            ['name' => 'Fajar Nugroho',   'email' => 'fajar@mail.com',    'dob' => '2001-12-22'],
            ['name' => 'Dewi Anggraeni',  'email' => 'dewi@mail.com',     'dob' => '1996-04-08'],
            ['name' => 'Hendra Saputra',  'email' => 'hendra@mail.com',   'dob' => '1994-06-17'],
            ['name' => 'Nisa Permata',    'email' => 'nisa@mail.com',     'dob' => '2000-10-03'],
            ['name' => 'Rizky Aditya',    'email' => 'rizky@mail.com',    'dob' => '1998-02-25'],
            ['name' => 'Clara Indah',     'email' => 'clara@mail.com',    'dob' => '2002-08-11'],
        ];

        foreach ($customers as $cust) {
            User::create([
                'role'          => 'customer',
                'name'          => $cust['name'],
                'email'         => $cust['email'],
                'date_of_birth' => $cust['dob'],
                'email_verified_at' => now(),
                'password_hash' => Hash::make('password'),
            ]);
        }
    }
}
