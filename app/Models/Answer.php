<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'rating'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function postComments()
    {
        return $this->post()->with('comments');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'answer_id')->with('user')->latest();
    }
} 