<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReplyToReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'reply_id',
    ];

    /**
     * Get the user that owns the reply to reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reply that owns the reply to reply.
     */
    public function reply()
    {
        return $this->belongsTo(Reply::class);
    }

    /**
     * Get the likes for the reply to reply.
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Check if the reply to reply is liked by a user.
     */
    public function likedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
