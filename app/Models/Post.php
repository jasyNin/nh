<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'user_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $with = ['user']; // Автоматически загружаем пользователя

    protected $withCount = ['comments', 'views', 'likes', 'reposts', 'answers']; // Автоматически подсчитываем количество

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Удаленный пользователь',
            'avatar' => null
        ]);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', self::class);
    }

    public function isBookmarkedBy(?User $user): bool
    {
        if (!$user) return false;
        
        return Cache::remember("post_{$this->id}_bookmarked_by_{$user->id}", 300, function () use ($user) {
            return $this->bookmarks()->where('user_id', $user->id)->exists();
        });
    }

    public function getRatingAttribute(): int
    {
        return Cache::remember("post_{$this->id}_rating", 300, function () {
            return $this->ratings()->sum('value');
        });
    }

    public function hasUserRated(?User $user): bool
    {
        if (!$user) return false;
        
        return Cache::remember("post_{$this->id}_rated_by_{$user->id}", 300, function () use ($user) {
            return $this->ratings()->where('user_id', $user->id)->exists();
        });
    }

    public function getUserRating(?User $user): ?int
    {
        if (!$user) return null;
        
        return Cache::remember("post_{$this->id}_user_{$user->id}_rating", 300, function () use ($user) {
            return $this->ratings()->where('user_id', $user->id)->value('value');
        });
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likedBy(?User $user): bool
    {
        if (!$user) return false;
        
        return Cache::remember("post_{$this->id}_liked_by_{$user->id}", 300, function () use ($user) {
            return $this->likes()->where('user_id', $user->id)->exists();
        });
    }

    public function getLikesCountAttribute(): int
    {
        return Cache::remember("post_{$this->id}_likes_count", 300, function () {
            return $this->likes()->count();
        });
    }

    public function likesCount()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class)->latest();
    }

    public function getAnswersCountAttribute(): int
    {
        return Cache::remember("post_{$this->id}_answers_count", 300, function () {
            return $this->answers()->count();
        });
    }

    public function getCommentsCountAttribute(): int
    {
        return Cache::remember("post_{$this->id}_comments_count", 300, function () {
            return $this->comments()->count();
        });
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function viewedBy(?User $user): void
    {
        if (!$user) return;
        
        $this->views()->updateOrCreate(
            ['user_id' => $user->id],
            ['viewed_at' => now()]
        );
        
        // Очищаем кэш просмотров
        Cache::forget("post_{$this->id}_views_count");
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class);
    }

    public function complaints(): MorphMany
    {
        return $this->morphMany(Complaint::class, 'complaintable');
    }

    public function getRepostsCountAttribute(): int
    {
        return Cache::remember("post_{$this->id}_reposts_count", 300, function () {
            return $this->reposts()->count();
        });
    }

    public function getUrl(): string
    {
        return route('posts.show', $this);
    }

    protected static function boot()
    {
        parent::boot();

        // Очищаем кэш при обновлении поста
        static::updated(function ($post) {
            Cache::forget("post_{$post->id}_rating");
            Cache::forget("post_{$post->id}_likes_count");
            Cache::forget("post_{$post->id}_comments_count");
            Cache::forget("post_{$post->id}_answers_count");
            Cache::forget("post_{$post->id}_reposts_count");
        });

        // Очищаем кэш при удалении поста
        static::deleted(function ($post) {
            Cache::forget("post_{$post->id}_rating");
            Cache::forget("post_{$post->id}_likes_count");
            Cache::forget("post_{$post->id}_comments_count");
            Cache::forget("post_{$post->id}_answers_count");
            Cache::forget("post_{$post->id}_reposts_count");
        });
    }
}
