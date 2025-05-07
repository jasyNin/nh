<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        try {
            Log::info('New comment created', [
                'comment_id' => $comment->id,
                'post_id' => $comment->post_id,
                'user_id' => $comment->user_id
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing new comment', [
                'comment_id' => $comment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
} 