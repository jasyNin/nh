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
        'reason',
        'status'
    ];

    /**
     * Получить пользователя, отправившего жалобу
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить объект, на который поступила жалоба
     */
    public function complaintable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Получить статус жалобы на русском языке
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'new' => 'Новая',
            'open' => 'Открыт спор',
            'unjustified' => 'Не обоснована',
            'closed' => 'Закрыта',
            default => 'Неизвестно'
        };
    }
}
