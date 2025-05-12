<?php

namespace App\Providers;

use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\CommentReply;
use App\Policies\BookmarkPolicy;
use App\Policies\CommentPolicy;
use App\Policies\NotificationPolicy;
use App\Policies\PostPolicy;
use App\Policies\CommentReplyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        Comment::class => CommentPolicy::class,
        Bookmark::class => BookmarkPolicy::class,
        Notification::class => NotificationPolicy::class,
        CommentReply::class => CommentReplyPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
} 