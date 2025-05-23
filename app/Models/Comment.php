<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'parent_id',
        'is_bot',
        'answer_id',
        'is_hidden',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_bot' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Удаленный пользователь',
            'avatar' => null
        ]);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class)->withDefault();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', self::class);
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(CommentReply::class)->with('user')->latest();
    }

    public function likedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getRepliesCountAttribute(): int
    {
        return $this->replies()->count();
    }

    public function getUrl()
    {
        return route('posts.show', $this->post) . '#comment-' . $this->id;
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class, 'answer_id')->withDefault();
    }
} 