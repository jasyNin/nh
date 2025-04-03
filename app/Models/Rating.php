<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'value'
    ];

    protected $casts = [
        'value' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
            ->where('notifiable_type', self::class);
    }
} 