<?php

namespace App\Traits;

use App\Services\RankService;

trait HasRank
{
    protected static function bootHasRank()
    {
        static::saving(function ($user) {
            // Если пользователь бот, админ или модератор, не меняем его ранг
            if (in_array($user->rank, ['bot', 'admin', 'moderator'])) {
                return;
            }

            // Для остальных пользователей обновляем ранг на основе рейтинга
            if ($user->isDirty('rating')) {
                $rankService = app(RankService::class);
                $user->rank = $rankService->getRankByPoints($user->rating);
            }
        });
    }

    public function getRankIconAttribute(): string
    {
        $rankService = app(RankService::class);
        return $rankService->getRankIcon($this->rank);
    }

    public function getRankNameAttribute(): string
    {
        $rankService = app(RankService::class);
        return $rankService->getRankName($this->rank);
    }
} 