<?php

namespace App\Services;

use App\Models\NeuronchikBot;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class NeuronchikService
{
    private $bot;

    public function __construct()
    {
        if (Schema::hasTable('neuronchik_bots')) {
            $this->initializeBot();
        }
    }

    private function initializeBot(): void
    {
        $this->bot = NeuronchikBot::firstOrCreate(
            ['user_id' => $this->getBotUserId()],
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

    private function getBotUserId(): int
    {
        $botUser = User::firstOrCreate(
            ['email' => 'neuronchik@example.com'],
            [
                'name' => 'Нейрончик',
                'password' => bcrypt(Str::random(16)),
                'role' => 'bot'
            ]
        );

        return $botUser->id;
    }

    public function processNewPost(Post $post): void
    {
        try {
            if (!$this->bot || !$this->bot->is_active) {
                return;
            }

            // Анализируем пост
            $analysis = $this->bot->analyzePost($post->content);

            // Если пост является вопросом, генерируем ответ
            if ($analysis['is_question']) {
                $response = $this->bot->generateResponse($analysis);

                if (!empty($response)) {
                    // Создаем комментарий от имени бота
                    Comment::create([
                        'post_id' => $post->id,
                        'user_id' => $this->bot->user_id,
                        'content' => $response,
                        'is_bot' => true
                    ]);

                    // Обновляем время последней активности бота
                    $this->bot->update(['last_activity' => now()]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при обработке поста ботом Нейрончик: ' . $e->getMessage());
        }
    }

    public function handleNewComment(Comment $comment): void
    {
        try {
            if (!$this->bot || !$this->bot->is_active || $comment->is_bot) {
                return;
            }

            // Анализируем комментарий
            $analysis = $this->bot->analyzePost($comment->content);

            // Если комментарий является вопросом, генерируем ответ
            if ($analysis['is_question']) {
                $response = $this->bot->generateResponse($analysis);

                if (!empty($response)) {
                    // Создаем ответный комментарий от имени бота
                    Comment::create([
                        'post_id' => $comment->post_id,
                        'user_id' => $this->bot->user_id,
                        'content' => $response,
                        'is_bot' => true,
                        'parent_id' => $comment->id // Привязываем к исходному комментарию
                    ]);

                    // Обновляем время последней активности бота
                    $this->bot->update(['last_activity' => now()]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при обработке комментария ботом Нейрончик: ' . $e->getMessage());
        }
    }

    public function isBotActive(): bool
    {
        return $this->bot && $this->bot->is_active;
    }

    public function getBotSettings(): array
    {
        return $this->bot ? ($this->bot->settings ?? []) : [];
    }

    public function updateBotSettings(array $settings): void
    {
        if ($this->bot) {
            $this->bot->update(['settings' => $settings]);
        }
    }

    public function toggleBotStatus(): void
    {
        if ($this->bot) {
            $this->bot->update(['is_active' => !$this->bot->is_active]);
        }
    }
} 