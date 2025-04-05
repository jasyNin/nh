<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        try {
            return $this->hasMany(Comment::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе comments: ' . $e->getMessage());
            return $this->hasMany(Comment::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function tags(): BelongsToMany
    {
        try {
            return $this->belongsToMany(Tag::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе tags: ' . $e->getMessage());
            return $this->belongsToMany(Tag::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks');
    }

    public function ratings(): HasMany
    {
        try {
            return $this->hasMany(Rating::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе ratings: ' . $e->getMessage());
            return $this->hasMany(Rating::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function notifications(): HasMany
    {
        try {
            return $this->hasMany(Notification::class, 'notifiable_id')
                ->where('notifiable_type', self::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе notifications: ' . $e->getMessage());
            return $this->hasMany(Notification::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function isBookmarkedBy(User $user)
    {
        return $this->bookmarks()->where('user_id', $user->id)->exists();
    }

    public function getRatingAttribute()
    {
        return $this->ratings()->sum('value');
    }

    public function hasUserRated(User $user)
    {
        return $this->ratings()->where('user_id', $user->id)->exists();
    }

    public function getUserRating(User $user)
    {
        return $this->ratings()->where('user_id', $user->id)->value('value');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // Метод для использования с withCount
    public function likesCount(): MorphMany
    {
        try {
            return $this->morphMany(Like::class, 'likeable');
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе likesCount: ' . $e->getMessage());
            return $this->morphMany(Like::class, 'likeable')->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function answers(): HasMany
    {
        try {
            return $this->hasMany(Answer::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе answers: ' . $e->getMessage());
            return $this->hasMany(Answer::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function getAnswersCountAttribute()
    {
        return $this->answers()->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function views(): HasMany
    {
        try {
            return $this->hasMany(PostView::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе views: ' . $e->getMessage());
            return $this->hasMany(PostView::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function viewedBy(User $user)
    {
        $this->views()->updateOrCreate(
            ['user_id' => $user->id],
            ['viewed_at' => now()]
        );
    }

    public function reposts(): HasMany
    {
        try {
            return $this->hasMany(Repost::class);
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе reposts: ' . $e->getMessage());
            return $this->hasMany(Repost::class)->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }

    public function getRepostsCountAttribute()
    {
        return $this->reposts()->count();
    }

    public function getUrl()
    {
        return route('posts.show', $this);
    }

    public function bookmarkedByUsers(): BelongsToMany
    {
        try {
            return $this->belongsToMany(User::class, 'bookmarks', 'post_id', 'user_id');
        } catch (\Exception $e) {
            \Log::error('Ошибка в методе bookmarkedByUsers: ' . $e->getMessage());
            return $this->belongsToMany(User::class, 'bookmarks', 'post_id', 'user_id')->whereRaw('1=0'); // Возвращаем пустой запрос
        }
    }
}
