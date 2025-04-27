<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateSpecialRanks extends Command
{
    protected $signature = 'users:update-special-ranks';
    protected $description = 'Обновить ранги для специальных пользователей (бот, админ, модератор)';

    public function handle()
    {
        // Обновляем ранг для бота
        User::where('name', 'Нейрончик')->update(['rank' => 'bot']);
        $this->info('Ранг бота обновлен');

        // Обновляем ранг для админов
        User::where('is_admin', true)->update(['rank' => 'admin']);
        $this->info('Ранги админов обновлены');

        // Обновляем ранг для модераторов
        User::where('is_moderator', true)->update(['rank' => 'moderator']);
        $this->info('Ранги модераторов обновлены');

        // Обновляем ранг для обычных пользователей на основе рейтинга
        $users = User::where('is_admin', false)
            ->where('is_moderator', false)
            ->where('name', '!=', 'Нейрончик')
            ->get();

        foreach ($users as $user) {
            $rating = $user->rating ?? 0;
            $user->rank = app(\App\Services\RankService::class)->getRankByPoints($rating);
            $user->save();
        }
        $this->info('Ранги обычных пользователей обновлены');

        $this->info('Все ранги успешно обновлены');
    }
} 