<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NeuronchikBot;
use App\Models\User;

class NeuronchikBotSeeder extends Seeder
{
    public function run(): void
    {
        $botUser = User::firstOrCreate(
            ['email' => 'neuronchik@example.com'],
            [
                'name' => 'Нейрончик',
                'password' => bcrypt('123123123'),
                'role' => 'bot',
                'avatar' => 'images/neuronchik.png'
            ]
        );

        NeuronchikBot::firstOrCreate(
            ['user_id' => $botUser->id],
            [
                'name' => 'Нейрончик',
                'is_active' => true,
                'settings' => [
                    'response_delay' => 5,
                    'max_responses_per_day' => 100,
                    'topics' => ['weather', 'technology', 'facts', 'general']
                ]
            ]
        );
    }
} 