<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'comment_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Удаленный пользователь',
            'avatar' => null
        ]);
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class)->withDefault();
    }

    public function likes()
    {
        return $this->hasMany(ReplyLike::class, 'reply_id');
    }
    
    public function likedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
    
    public function getLikesCountAttribute(): int
    {
        return $this->likes()->count();
    }

    public function getUrl()
    {
        return route('posts.show', $this->comment->post) . '#reply-' . $this->id;
    }
} 