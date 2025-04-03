<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        $posts = collect();
        if ($query) {
            $posts = Post::with(['user', 'tags'])
                ->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%")
                ->latest()
                ->paginate(10);
        }

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

        return view('search.index', compact('posts', 'popularTags', 'topUsers'));
    }
} 