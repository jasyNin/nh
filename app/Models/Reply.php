<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'comment_id',
    ];

    /**
     * Get the user that owns the reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comment that owns the reply.
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Get the replies to this reply.
     */
    public function replies()
    {
        return $this->hasMany(ReplyToReply::class);
    }

    /**
     * Get the likes for the reply.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if the reply is liked by a user.
     */
    public function likedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
