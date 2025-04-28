<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class NeuronchikSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Нейрончик',
            'email' => 'neuronchik@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'images/neuronchik.png',
            'rank' => 'bot',
            'rating' => 0,
            'is_bot' => true,
            'email_verified_at' => now(),
        ]);
    }
} 