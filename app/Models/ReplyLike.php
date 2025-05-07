<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReplyLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reply_id',
        'post_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reply()
    {
        return $this->belongsTo(CommentReply::class, 'reply_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
