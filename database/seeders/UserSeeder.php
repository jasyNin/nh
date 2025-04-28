<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем администратора
        User::create([
            'name' => 'Администратор',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'images/admin.png',
            'rank' => 'admin',
            'rating' => 0,
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Создаем тестовых пользователей
        User::factory(10)->create();
    }
} 