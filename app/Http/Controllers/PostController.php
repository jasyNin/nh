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
            ->withCount(['comments', 'views', 'likesCount as likes_count', 'reposts'])
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
            ->orderByDesc('posts_count')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                $user->posts_count = (int)$user->posts_count;
                return $user;
            });

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
            \Log::info('Tags before processing:', [
                'type' => gettype($validated['tags']),
                'value' => $validated['tags']
            ]);
            
            $tags = is_array($validated['tags']) ? implode(',', $validated['tags']) : $validated['tags'];
            
            \Log::info('Tags after processing:', [
                'type' => gettype($tags),
                'value' => $tags
            ]);
            
            $tagNames = array_map('trim', explode(',', $tags));
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
        // Отслеживаем просмотр поста
        $post->viewedBy(auth()->user());

        // Получаем популярные теги
        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        // Получаем топ пользователей
        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        return view('posts.show', compact('post', 'popularTags', 'topUsers'));
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
            $tags = is_array($validated['tags']) ? implode(',', $validated['tags']) : $validated['tags'];
            $tagNames = array_map('trim', explode(',', $tags));
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
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $message = 'Пост добавлен в закладки';
            $bookmarked = true;
            
            // Создаем уведомление для автора поста
            if ($post->user_id !== $user->id) {
                $post->user->notifications()->create([
                    'from_user_id' => $user->id,
                    'type' => 'bookmark',
                    'notifiable_type' => Post::class,
                    'notifiable_id' => $post->id
                ]);
            }
        }
        
        return response()->json([
            'message' => $message,
            'bookmarked' => $bookmarked
        ]);
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

    public function repost(Post $post)
    {
        $user = Auth::user();
        
        // Создаем репост
        $post->reposts()->create([
            'user_id' => $user->id
        ]);
        
        // Создаем уведомление для автора поста
        if ($post->user_id !== $user->id) {
            $post->user->notifications()->create([
                'from_user_id' => $user->id,
                'type' => 'repost',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id
            ]);
        }
        
        return response()->json([
            'message' => 'Пост успешно репостнут',
            'reposts_count' => $post->reposts_count
        ]);
    }
} 