<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'type' => $this->faker->randomElement(['post', 'question']),
            'user_id' => User::factory(),
            'status' => 'published',
            'image' => null,
        ];
    }
} 