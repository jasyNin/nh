<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ModeratorSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Модератор',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'images/moderator.png',
            'rank' => 'moderator',
            'rating' => 0,
            'is_moderator' => true,
            'email_verified_at' => now(),
        ]);
    }
} 