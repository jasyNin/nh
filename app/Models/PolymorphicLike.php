<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolymorphicLike extends Model
{
    protected $table = 'polymorphic_likes';
    
    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type'
    ];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 