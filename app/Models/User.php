<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

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
        ];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function receivedNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'polymorphic_likes', 'user_id', 'likeable_id')
            ->where('likeable_type', 'App\\Models\\Post');
    }
    
    public function likedComments()
    {
        return $this->belongsToMany(Comment::class, 'polymorphic_likes', 'user_id', 'likeable_id')
            ->where('likeable_type', 'App\\Models\\Comment');
    }
    
    public function likedReplies()
    {
        return $this->belongsToMany(CommentReply::class, 'polymorphic_likes', 'user_id', 'likeable_id')
            ->where('likeable_type', 'App\\Models\\CommentReply');
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

    public function getPostCountAttribute()
    {
        return $this->posts()->count();
    }

    public function getCommentCountAttribute()
    {
        return $this->comments()->count();
    }

    public function getBookmarkCountAttribute()
    {
        return $this->bookmarks()->count();
    }

    public function getAnswersCountAttribute()
    {
        return $this->answers()->count();
    }

    public function getRepostsCountAttribute()
    {
        return $this->reposts()->count();
    }

    public function viewedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_views')
            ->withPivot('viewed_at')
            ->orderBy('post_views.viewed_at', 'desc')
            ->whereNotNull('post_views.viewed_at');
    }
}
