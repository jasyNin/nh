<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with(['post.user', 'post.tags'])
            ->latest()
            ->paginate(10);
        
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

        return view('bookmarks.index', compact('bookmarks', 'popularTags', 'topUsers'));
    }

    public function store(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        
        auth()->user()->bookmarks()->create([
            'post_id' => $post->id,
        ]);

        return back()->with('success', 'Пост добавлен в закладки');
    }

    public function destroy(Post $post)
    {
        $bookmark = auth()->user()->bookmarks()->where('post_id', $post->id)->first();
        if ($bookmark) {
            $bookmark->delete();
        }
        return back()->with('success', 'Закладка удалена');
    }

    public function clear()
    {
        auth()->user()->bookmarks()->delete();

        return back()->with('success', 'Все закладки удалены');
    }
} 