<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\QuestionBotService;

class QuestionBotSeeder extends Seeder
{
    public function run(): void
    {
        $botService = new QuestionBotService();
        $botService->initializeBot();
    }
} 