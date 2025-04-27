<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\Post;
use App\Observers\PostObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // ... existing events ...
    ];

    public function boot(): void
    {
        Post::observe(PostObserver::class);
    }
} 