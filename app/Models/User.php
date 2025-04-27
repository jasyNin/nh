<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasRank;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRank;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'avatar',
        'rating',
        'rank',
        'last_notification_view',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rating' => 'integer',
            'last_notification_view' => 'datetime',
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class)->latest();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class)->latest();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function receivedNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id')->latest();
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function postLikes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_likes', 'user_id', 'post_id')->withTimestamps();
    }
    
    public function likedComments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'polymorphic_likes', 'user_id', 'likeable_id')
            ->where('likeable_type', Comment::class)
            ->withTimestamps();
    }
    
    public function likedReplies(): BelongsToMany
    {
        return $this->belongsToMany(CommentReply::class, 'polymorphic_likes', 'user_id', 'likeable_id')
            ->where('likeable_type', CommentReply::class)
            ->withTimestamps();
    }

    public function reposts(): HasMany
    {
        return $this->hasMany(Repost::class)->latest();
    }

    public function getPostCountAttribute(): int
    {
        return Cache::remember("user_{$this->id}_posts_count", 300, function () {
            return $this->posts()->count();
        });
    }

    public function getCommentCountAttribute(): int
    {
        return Cache::remember("user_{$this->id}_comments_count", 300, function () {
            return $this->comments()->count();
        });
    }

    public function getBookmarkCountAttribute(): int
    {
        return Cache::remember("user_{$this->id}_bookmarks_count", 300, function () {
            return $this->bookmarks()->count();
        });
    }

    public function getRepostsCountAttribute(): int
    {
        return Cache::remember("user_{$this->id}_reposts_count", 300, function () {
            return $this->reposts()->count();
        });
    }

    public function viewedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_views')
            ->withPivot('viewed_at')
            ->orderBy('post_views.viewed_at', 'desc')
            ->whereNotNull('post_views.viewed_at');
    }

    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return asset('images/default-avatar.png');
        }
        return asset('storage/' . $this->avatar);
    }

    protected static function boot()
    {
        parent::boot();

        // Очищаем кэш при обновлении пользователя
        static::updated(function ($user) {
            Cache::forget("user_{$user->id}_posts_count");
            Cache::forget("user_{$user->id}_comments_count");
            Cache::forget("user_{$user->id}_bookmarks_count");
            Cache::forget("user_{$user->id}_reposts_count");
        });

        // Очищаем кэш при удалении пользователя
        static::deleted(function ($user) {
            Cache::forget("user_{$user->id}_posts_count");
            Cache::forget("user_{$user->id}_comments_count");
            Cache::forget("user_{$user->id}_bookmarks_count");
            Cache::forget("user_{$user->id}_reposts_count");
        });
    }
}
