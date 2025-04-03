<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'user_id'
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
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function bookmarks(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks');
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

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getAnswersCountAttribute()
    {
        return $this->answers()->count();
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    public function viewedBy(User $user)
    {
        $this->views()->updateOrCreate(
            ['user_id' => $user->id],
            ['viewed_at' => now()]
        );
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class);
    }

    public function getRepostsCountAttribute()
    {
        return $this->reposts()->count();
    }

    public function getUrl()
    {
        return route('posts.show', $this);
    }
}
