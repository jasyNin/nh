<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\QuestionBotService;
use Illuminate\Support\Facades\Log;

class QuestionBotObserver
{
    private QuestionBotService $botService;

    public function __construct(QuestionBotService $botService)
    {
        $this->botService = $botService;
    }

    public function created(Post $post): void
    {
        try {
            Log::info('QuestionBotObserver: New post created', [
                'post_id' => $post->id,
                'post_type' => $post->type
            ]);

            $this->botService->processNewPost($post);
        } catch (\Exception $e) {
            Log::error('QuestionBotObserver: Error processing new post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
} 