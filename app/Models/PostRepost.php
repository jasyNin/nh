<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PostRepost extends Model
{
    protected $fillable = [
        'user_id',
        'post_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Очищаем кэш при создании репоста
        static::created(function ($repost) {
            Cache::forget("post_{$repost->post_id}_reposts_count");
        });

        // Очищаем кэш при удалении репоста
        static::deleted(function ($repost) {
            Cache::forget("post_{$repost->post_id}_reposts_count");
        });
    }
}
