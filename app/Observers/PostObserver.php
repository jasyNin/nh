<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\NeuronchikService;

class PostObserver
{
    private $neuronchikService;

    public function __construct(NeuronchikService $neuronchikService)
    {
        $this->neuronchikService = $neuronchikService;
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // Обрабатываем новый пост ботом Нейрончик
        $this->neuronchikService->processNewPost($post);
    }
} 