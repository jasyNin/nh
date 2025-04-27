<?php

namespace App\Services;

class RankService
{
    private const RANKS = [
        'novice' => [
            'name' => 'Новичок',
            'icon' => 'novichec.svg',
            'min_points' => 0,
            'max_points' => 9
        ],
        'student' => [
            'name' => 'Ученик',
            'icon' => 'silver.svg',
            'min_points' => 10,
            'max_points' => 29
        ],
        'expert' => [
            'name' => 'Знаток',
            'icon' => 'gold.svg',
            'min_points' => 30,
            'max_points' => 59
        ],
        'erudite' => [
            'name' => 'Эрудит',
            'icon' => 'purple.svg',
            'min_points' => 60,
            'max_points' => 119
        ],
        'master' => [
            'name' => 'Эксперт',
            'icon' => 'dimond.svg',
            'min_points' => 120,
            'max_points' => 239
        ],
        'supermind' => [
            'name' => 'Сверхразум',
            'icon' => 'ruby.svg',
            'min_points' => 240,
            'max_points' => PHP_INT_MAX
        ],
        'admin' => [
            'name' => 'Администратор',
            'icon' => 'adminicon.svg',
            'min_points' => 0,
            'max_points' => PHP_INT_MAX
        ],
        'moderator' => [
            'name' => 'Модератор',
            'icon' => 'moder.svg',
            'min_points' => 0,
            'max_points' => PHP_INT_MAX
        ],
        'bot' => [
            'name' => 'Бот',
            'icon' => 'bot.svg',
            'min_points' => 0,
            'max_points' => PHP_INT_MAX
        ]
    ];

    public function getRankByPoints(int $rating): string
    {
        foreach (self::RANKS as $rank => $data) {
            if ($rating >= $data['min_points'] && $rating <= $data['max_points']) {
                return $rank;
            }
        }
        return 'novice';
    }

    public function getRankIcon(string $rank): string
    {
        return self::RANKS[$rank]['icon'] ?? 'novichec.svg';
    }

    public function getRankName(string $rank): string
    {
        return self::RANKS[$rank]['name'] ?? 'Новичок';
    }

    public function getAllRanks(): array
    {
        return self::RANKS;
    }
} 