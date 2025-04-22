<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NeuronchikBot extends Model
{
    protected $fillable = [
        'name',
        'avatar',
        'description'
    ];

    public static function getBot()
    {
        return self::firstOrCreate(
            ['name' => 'Нейрончик'],
            [
                'name' => 'Нейрончик',
                'avatar' => 'images/neuronchik.png',
                'description' => 'Ваш умный помощник в решении технических вопросов'
            ]
        );
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }
} 