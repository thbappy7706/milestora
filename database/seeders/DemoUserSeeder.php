<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Seed the demo account used to log in to the app.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'demo@milestora.test'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'timezone' => 'Asia/Dhaka',
            ],
        );
    }
}
