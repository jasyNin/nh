<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Observers\PostObserver;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Services\NeuronchikService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NeuronchikService::class, function ($app) {
            return new NeuronchikService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Регистрируем наблюдатели
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
