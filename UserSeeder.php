<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Owner',
            'email' => 'owner@company.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'status' => 'active',
            'phone' => '+92-300-0000000',
        ]);

        User::create([
            'name' => 'Director',
            'email' => 'director@company.com',
            'password' => Hash::make('password'),
            'role' => 'director',
            'status' => 'active',
            'phone' => '+92-300-1111111',
        ]);

        User::create([
            'name' => 'Investor One',
            'email' => 'investor1@email.com',
            'password' => Hash::make('password'),
            'role' => 'investor',
            'status' => 'active',
            'phone' => '+92-300-2222222',
        ]);

        User::create([
            'name' => 'Investor Two',
            'email' => 'investor2@email.com',
            'password' => Hash::make('password'),
            'role' => 'investor',
            'status' => 'active',
            'phone' => '+92-300-3333333',
        ]);
    }
}
