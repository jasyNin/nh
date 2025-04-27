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
            ->paginate(10);

        // Получаем уведомления о лайках
        $likeNotifications = \App\Models\PostLike::whereHas('post', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['user', 'post'])
            ->latest()
            ->get()
            ->map(function($like) {
                return [
                    'id' => $like->id,
                    'type' => 'like',
                    'read' => false,
                    'created_at' => $like->created_at,
                    'fromUser' => $like->user,
                    'notifiable' => $like->post
                ];
            });

        // Получаем уведомления о комментариях
        $commentNotifications = \App\Models\Comment::whereHas('post', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->with(['user', 'post'])
            ->latest()
            ->get()
            ->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'type' => 'comment',
                    'read' => false,
                    'created_at' => $comment->created_at,
                    'fromUser' => $comment->user,
                    'notifiable' => $comment->post
                ];
            });

        // Объединяем уведомления
        $allNotifications = $notifications
            ->concat($likeNotifications)
            ->concat($commentNotifications)
            ->sortByDesc('created_at');

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

        return view('notifications.index', compact('allNotifications', 'popularTags', 'topUsers'));
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

    public function markAsViewed()
    {
        $user = auth()->user();
        $user->last_notification_view = now();
        $user->save();
        
        return response()->json(['success' => true]);
    }

    public function getUnviewedCount()
    {
        $user = auth()->user();
        $lastView = $user->last_notification_view;
        
        $likeCount = \App\Models\PostLike::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->when($lastView, function($query) use ($lastView) {
            return $query->where('created_at', '>', $lastView);
        })
        ->count();

        $commentCount = \App\Models\Comment::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->when($lastView, function($query) use ($lastView) {
            return $query->where('created_at', '>', $lastView);
        })
        ->count();

        return response()->json([
            'has_unviewed' => ($likeCount + $commentCount) > 0
        ]);
    }
} 