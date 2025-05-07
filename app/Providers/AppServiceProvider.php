<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Observers\QuestionBotObserver;
use App\Services\QuestionBotService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(QuestionBotService::class, function ($app) {
            return new QuestionBotService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Post::observe(QuestionBotObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
