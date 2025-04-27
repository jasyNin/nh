<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $tags = Tag::all();

        // Создаем несколько постов для каждого пользователя
        foreach ($users as $user) {
            Post::factory(3)->create([
                'user_id' => $user->id,
            ])->each(function ($post) use ($tags) {
                // Привязываем случайные теги к посту
                $post->tags()->attach(
                    $tags->random(rand(1, 3))->pluck('id')->toArray()
                );
            });
        }
    }
} 