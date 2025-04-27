<?php

namespace App\Observers;

use App\Models\Comment;
use App\Services\NeuronchikService;

class CommentObserver
{
    protected $neuronchikService;

    public function __construct(NeuronchikService $neuronchikService)
    {
        $this->neuronchikService = $neuronchikService;
    }

    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        // Отправляем комментарий Нейрончику
        $this->neuronchikService->handleNewComment($comment);
    }
} 