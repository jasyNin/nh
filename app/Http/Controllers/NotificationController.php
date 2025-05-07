<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->with(['fromUser', 'notifiable', 'post'])
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
                return (object)[
                    'id' => $like->id,
                    'type' => 'like',
                    'read' => false,
                    'created_at' => $like->created_at,
                    'user' => $like->user,
                    'post' => $like->post
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
                return (object)[
                    'id' => $comment->id,
                    'type' => 'comment',
                    'read' => false,
                    'created_at' => $comment->created_at,
                    'user' => $comment->user,
                    'post' => $comment->post,
                    'content' => $comment->content
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
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()->update(['read' => true]);
        return response()->json(['success' => true]);
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
        auth()->user()->notifications()->update(['viewed' => true]);
        return response()->json(['success' => true]);
    }

    public function getUnviewedCount()
    {
        $count = auth()->user()->notifications()
            ->where('viewed', false)
        ->count();

        return response()->json([
            'has_unviewed' => $count > 0,
            'count' => $count
        ]);
    }
} 