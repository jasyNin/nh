<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Post;
use App\Models\Comment;
use App\Observers\NeuronchikObserver;
use App\Observers\CommentObserver;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // ... existing events ...
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
        
        // Регистрируем наблюдатели
        Post::observe(NeuronchikObserver::class);
        Comment::observe(CommentObserver::class);
        
        Log::info('Observers registered', [
            'post_observer' => NeuronchikObserver::class,
            'comment_observer' => CommentObserver::class
        ]);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
} 