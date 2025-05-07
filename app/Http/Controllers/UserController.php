<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Answer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        $posts = $user->posts()
            ->where('status', 'published')
            ->with(['user', 'tags'])
            ->latest()
            ->paginate(10);

        $comments = $user->comments()
            ->with(['post', 'user'])
            ->latest()
            ->paginate(10);

        $bookmarks = $user->bookmarks()
            ->with(['post.user'])
            ->latest()
            ->paginate(10);

        // Подсчет статистики с использованием полиморфных отношений
        $stats = [
            'posts_count' => $user->posts()->where('status', 'published')->count(),
            'comments_count' => $user->comments()->count(),
            'likes_received' => \App\Models\Like::where('likeable_type', 'App\\Models\\Post')
                ->whereIn('likeable_id', $user->posts()->where('status', 'published')->pluck('id'))
                ->count(),
            'likes_given' => \App\Models\Like::where('user_id', $user->id)->count(),
            'bookmarks_count' => $user->bookmarks()->count(),
        ];

        return view('users.show', compact('user', 'posts', 'comments', 'bookmarks', 'stats'));
    }

    public function rating()
    {
        $users = User::withCount(['posts', 'comments'])
            ->orderByDesc('posts_count')
            ->get();

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

        return view('users.rating', compact('users', 'popularTags', 'topUsers'));
    }
} 