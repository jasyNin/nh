<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContentReported extends Notification implements ShouldQueue
{
    use Queueable;

    protected $content;
    protected $reason;
    protected $type;

    public function __construct($content, $reason, $type)
    {
        $this->content = $content;
        $this->reason = $reason;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новая жалоба на контент')
            ->line('Получена новая жалоба на ' . $this->type)
            ->line('Причина: ' . $this->reason)
            ->action('Просмотреть', url($this->content->getUrl()))
            ->line('Спасибо за использование нашего приложения!');
    }

    public function toArray($notifiable)
    {
        return [
            'content_id' => $this->content->id,
            'content_type' => $this->type,
            'reason' => $this->reason,
            'url' => $this->content->getUrl(),
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id
        ];
    }
} 