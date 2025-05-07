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
                ->where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%");
                })
                ->latest()
                ->paginate(10);
        }

        // Получаем популярные теги
        $popularTags = Tag::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])
            ->orderByDesc('posts_count')
            ->limit(10)
            ->get();
            
        // Получаем топ пользователей
        $topUsers = User::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])
            ->orderByDesc('posts_count')
            ->limit(5)
            ->get();

        return view('search.index', compact('posts', 'popularTags', 'topUsers'));
    }

    public function searchPosts(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $posts = Post::with(['user', 'tags'])
            ->where('status', 'published')
            ->where('title', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'url' => route('posts.show', $post),
                    'user' => [
                        'name' => $post->user->name,
                        'avatar' => $post->user->avatar ? asset('storage/' . $post->user->avatar) : asset('images/default-avatar.png')
                    ],
                    'created_at' => $post->created_at->diffForHumans()
                ];
            });

        return response()->json($posts);
    }
} 