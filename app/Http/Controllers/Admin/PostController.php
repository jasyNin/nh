<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Показывает список постов
     */
    public function index(Request $request)
    {
        $query = Post::with('user')
            ->where('status', 'published')
            ->withCount(['views', 'likes', 'complaints']);

        // Поиск по заголовку
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Сортировка
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $posts = $query->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.posts.index', compact('posts'))->render()
            ]);
        }

        return view('admin.posts.index', compact('posts'));
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Пост успешно удален');
    }
}
