<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->with(['fromUser', 'notifiable'])
            ->latest()
            ;

        // Получаем популярные теги
        $popularTags = Tag::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(10)
            ->get();
            
        // Получаем топ пользователей
        $topUsers = User::withCount('posts')
            ->orderByDesc('posts_count')
            ->limit(5)
            ->get();

        return view('notifications.index', compact('notifications', 'popularTags', 'topUsers'));
    }

    public function markAsRead(Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update(['read' => true]);

        return response()->json($notification);
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['message' => 'Все уведомления отмечены как прочитанные']);
    }

    public function read(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        return back();
    }

    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
} 