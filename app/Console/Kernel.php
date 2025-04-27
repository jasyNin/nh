<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ManageNeuronchikBot::class,
        Commands\UpdateSpecialRanks::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // ... existing schedules ...
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
} 