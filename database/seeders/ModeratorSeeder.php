<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModeratorSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Модератор',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
            'is_moderator' => true,
            'rank' => 'moderator'
        ]);
    }
} 