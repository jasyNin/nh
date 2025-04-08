<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Like;
use App\Models\Bookmark;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Post::with(['user', 'tags'])
            ->withCount(['comments', 'views', 'likesCount as likes_count', 'reposts', 'answers'])
            ->latest();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $posts = $query->paginate(10);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('home', compact('posts', 'popularTags', 'topUsers'));
    }

    public function create()
    {
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

        return view('posts.create', compact('popularTags', 'topUsers'));
    }

    public function store(Request $request)
    {
        // Отладочная информация
        \Log::info('Request data:', $request->all());
        \Log::info('Tags type:', ['type' => gettype($request->tags)]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:post,question',
            'tags' => 'nullable|string',
            'is_draft' => 'nullable|in:0,1',
            'redirect_to' => 'nullable|string'
        ]);

        // Отладочная информация после валидации
        \Log::info('Validated data:', $validated);
        \Log::info('Validated tags type:', ['type' => gettype($validated['tags'])]);

        // Создаем базовый пост
        $post = new Post([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type']
        ]);

        // Определяем и устанавливаем статус, если это поле существует в таблице
        $isDraft = isset($validated['is_draft']) && $validated['is_draft'] == '1';
        
        try {
            if ($isDraft) {
                $post->setAttribute('status', 'draft');
            }
            $post->save();
        } catch (\Exception $e) {
            // Если статус не поддерживается, просто сохраняем пост без него
            \Log::error('Error setting status: ' . $e->getMessage());
            if (!$post->exists) {
                $post->save();
            }
        }

        // Обработка тегов
        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            
            if (!empty($tagIds)) {
                $post->tags()->attach($tagIds);
            }
        }

        // Определяем куда перенаправить пользователя
        if (!empty($validated['redirect_to'])) {
            $route = $validated['redirect_to'];
            $message = 'Пост сохранен как черновик.';
            
            // Если это абсолютный URL-путь, преобразуем его в относительный
            if (strpos($route, '/') === 0) {
                return redirect($route)->with('success', $message);
            }
            return redirect()->to($route)->with('success', $message);
        }

        // По умолчанию переходим на страницу поста
        return redirect()->route('posts.show', $post)
            ->with('success', $isDraft ? 'Черновик успешно создан.' : 'Пост успешно опубликован.');
    }

    public function show(Post $post)
    {
        if (auth()->check()) {
            $post->viewedBy(auth()->user());
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
            
        // Получаем последние ответы для правой колонки
        $recentAnswers = Answer::with(['user', 'post'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $post->load(['user', 'tags', 'comments.user']);
        $similarPosts = Post::whereHas('tags', function ($query) use ($post) {
            $query->whereIn('tags.id', $post->tags->pluck('id'));
        })
        ->where('posts.id', '!=', $post->id)
        ->take(3)
        ->get();

        return view('posts.show', compact('post', 'similarPosts', 'popularTags', 'topUsers', 'recentAnswers'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:post,question',
            'tags' => 'nullable|string'
        ]);

        $post->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'type' => $validated['type']
        ]);

        $post->tags()->detach();
        
        if (!empty($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate(
                        ['name' => $tagName],
                        ['slug' => Str::slug($tagName)]
                    );
                    $tagIds[] = $tag->id;
                }
            }
            
            if (!empty($tagIds)) {
                $post->tags()->attach($tagIds);
            }
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Пост успешно обновлен.');
    }

    public function destroy(Post $post)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $post->delete();
        return redirect()->route('home')->with('success', 'Пост успешно удален');
    }

    public function bookmark(Post $post)
    {
        $user = Auth::user();
        
        if ($post->isBookmarkedBy($user)) {
            Bookmark::where('user_id', $user->id)
                ->where('post_id', $post->id)
                ->delete();
            $message = 'Пост удален из закладок';
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $message = 'Пост добавлен в закладки';
        }
        
        return back()->with('success', $message);
    }

    public function rate(Request $request, Post $post)
    {
        $user = Auth::user();
        $value = $request->input('value');
        
        if ($post->hasUserRated($user)) {
            $post->ratings()->where('user_id', $user->id)->update(['value' => $value]);
        } else {
            $post->ratings()->create([
                'user_id' => $user->id,
                'value' => $value
            ]);
        }
        
        return back();
    }

    public function like(Post $post)
    {
        $liked = false;
        
        if ($post->likedBy(auth()->user())) {
            $post->likes()->where('user_id', auth()->id())->delete();
        } else {
            $post->likes()->create(['user_id' => auth()->id()]);
            $liked = true;
        }

        return response()->json([
            'likes_count' => $post->likes_count,
            'liked' => $liked
        ]);
    }
} 