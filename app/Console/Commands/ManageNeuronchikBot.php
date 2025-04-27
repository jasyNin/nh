<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NeuronchikService;

class ManageNeuronchikBot extends Command
{
    protected $signature = 'neuronchik {action : Действие (status|toggle|settings)}';
    protected $description = 'Управление ботом Нейрончик';

    private $neuronchikService;

    public function __construct(NeuronchikService $neuronchikService)
    {
        parent::__construct();
        $this->neuronchikService = $neuronchikService;
    }

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'status':
                $this->showStatus();
                break;
            case 'toggle':
                $this->toggleBot();
                break;
            case 'settings':
                $this->showSettings();
                break;
            default:
                $this->error('Неизвестное действие');
                return 1;
        }

        return 0;
    }

    private function showStatus(): void
    {
        $status = $this->neuronchikService->isBotActive() ? 'Активен' : 'Неактивен';
        $this->info("Статус бота Нейрончик: {$status}");
    }

    private function toggleBot(): void
    {
        $this->neuronchikService->toggleBotStatus();
        $this->showStatus();
    }

    private function showSettings(): void
    {
        $settings = $this->neuronchikService->getBotSettings();
        $this->info('Настройки бота Нейрончик:');
        $this->table(
            ['Параметр', 'Значение'],
            collect($settings)->map(fn($value, $key) => [$key, is_array($value) ? implode(', ', $value) : $value])
        );
    }
} 