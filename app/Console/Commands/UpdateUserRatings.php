<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PostLike;
use App\Models\CommentLike;
use App\Models\CommentReply;

class UpdateUserRatings extends Command
{
    protected $signature = 'users:update-ratings';
    protected $description = 'Обновляет рейтинги пользователей на основе их лайков';

    public function handle()
    {
        $this->info('Начинаем обновление рейтингов...');

        // Сбрасываем все рейтинги до 0
        User::query()->update(['rating' => 0]);

        // Подсчитываем лайки постов
        $postLikes = PostLike::select('user_id')
            ->selectRaw('COUNT(*) as likes_count')
            ->groupBy('user_id')
            ->get();

        foreach ($postLikes as $like) {
            User::where('id', $like->user_id)
                ->increment('rating', $like->likes_count);
        }

        // Подсчитываем лайки комментариев
        $commentLikes = CommentLike::select('user_id')
            ->selectRaw('COUNT(*) as likes_count')
            ->groupBy('user_id')
            ->get();

        foreach ($commentLikes as $like) {
            User::where('id', $like->user_id)
                ->increment('rating', $like->likes_count);
        }

        // Подсчитываем лайки ответов
        $replyLikes = CommentReply::select('user_id')
            ->selectRaw('COUNT(*) as likes_count')
            ->groupBy('user_id')
            ->get();

        foreach ($replyLikes as $like) {
            User::where('id', $like->user_id)
                ->increment('rating', $like->likes_count);
        }

        $this->info('Рейтинги успешно обновлены!');
    }
} 