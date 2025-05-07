<?php

namespace App\Services;

use App\Models\QuestionBot;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class QuestionBotService
{
    private ?QuestionBot $bot = null;

    public function __construct()
    {
        // Инициализация бота происходит только при первом вызове методов
    }

    private function ensureBotInitialized(): void
    {
        if ($this->bot === null) {
            $this->initializeBot();
        }
    }

    public function initializeBot(): void
    {
        try {
            Log::info('Initializing Question Bot');
            
            DB::beginTransaction();
            
            // Получаем или создаем пользователя для бота
            $botUser = User::firstOrCreate(
                ['email' => 'question_bot@example.com'],
                [
                    'name' => 'Нейрончик',
                    'password' => Hash::make("12345678"),
                    'avatar' => '/images/question-bot-avatar.png',
                    'is_bot' => true,
                    'role' => 'bot'
                ]
            );

            // Получаем или создаем запись бота
            $this->bot = QuestionBot::firstOrCreate(
                ['user_id' => $botUser->id],
                [
                    'name' => 'Нейрончик',
                    'is_active' => true,
                    'settings' => [
                        'response_delay' => 5,
                        'max_responses_per_day' => 100
                    ]
                ]
            );

            DB::commit();

            Log::info('Question Bot initialized successfully', [
                'bot_id' => $this->bot->id,
                'user_id' => $botUser->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to initialize Question Bot', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function processNewPost(Post $post): void
    {
        try {
            $this->ensureBotInitialized();

            if (!$this->bot->is_active) {
                Log::info('Bot is inactive, skipping post processing', [
                    'post_id' => $post->id
                ]);
                return;
            }

            // Проверяем, является ли пост вопросом
            if ($post->type !== 'question') {
                Log::info('Post is not a question, skipping', [
                    'post_id' => $post->id,
                    'post_type' => $post->type
                ]);
                return;
            }

            // Проверяем лимит ответов в день
            $responsesToday = Comment::where('user_id', $this->bot->user_id)
                ->whereDate('created_at', now())
                ->count();

            if ($responsesToday >= ($this->bot->settings['max_responses_per_day'] ?? 100)) {
                Log::info('Bot reached daily response limit', [
                    'post_id' => $post->id,
                    'responses_today' => $responsesToday
                ]);
                return;
            }

            // Генерируем ответ
            $answer = $this->bot->generateAnswer($post->content);

            // Добавляем задержку перед ответом
            $delay = $this->bot->settings['response_delay'] ?? 5;
            sleep($delay);

            // Создаем комментарий от имени бота
            Comment::create([
                'user_id' => $this->bot->user_id,
                'post_id' => $post->id,
                'content' => $answer,
                'is_bot' => true
            ]);

            // Обновляем время последней активности
            $this->bot->update(['last_activity' => now()]);

            Log::info('Bot answered question', [
                'post_id' => $post->id,
                'delay' => $delay
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 