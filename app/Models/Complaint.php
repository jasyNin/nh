<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'complaintable_id',
        'complaintable_type',
        'type',
        'target_type',
        'reason',
        'status',
        'moderator_comment',
        'resolved_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'resolved_at' => 'datetime'
    ];

    /**
     * Получить пользователя, отправившего жалобу
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Удаленный пользователь',
            'avatar' => null
        ]);
    }

    /**
     * Получить объект, на который поступила жалоба
     */
    public function complaintable(): MorphTo
    {
        return $this->morphTo()->withDefault([
            'id' => null,
            'user' => null
        ]);
    }

    /**
     * Получить статус жалобы на русском языке
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'new' => 'Новая',
            'in_progress' => 'В работе',
            'resolved' => 'Решена',
            'rejected' => 'Отклонена',
            default => 'Неизвестно'
        };
    }

    /**
     * Получить тип жалобы на русском языке
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'spam' => 'Спам',
            'insult' => 'Оскорбление',
            'inappropriate' => 'Неприемлемый контент',
            'copyright' => 'Нарушение авторских прав',
            'violence' => 'Насилие',
            'hate_speech' => 'Разжигание ненависти',
            'fake_news' => 'Фейковые новости',
            'other' => 'Другое',
            default => 'Неизвестно'
        };
    }

    /**
     * Получить тип объекта жалобы на русском языке
     */
    public function getTargetTypeTextAttribute(): string
    {
        return match($this->target_type) {
            'post' => 'Пост',
            'comment' => 'Комментарий',
            'reply' => 'Ответ',
            default => 'Неизвестно'
        };
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
