<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Создаем тестового пользователя
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]
        );

        // Сначала создаем базовые сущности
        $this->call([
            UserSeeder::class,      // Сначала пользователи
            TagSeeder::class,       // Потом теги
            QuestionBotSeeder::class, // Затем бот
        ]);

        // Затем создаем контент
        $this->call([
            PostSeeder::class,      // Посты (требуют пользователей и теги)
            ModeratorSeeder::class, // Модераторы (требуют пользователей)
        ]);
    }
}
