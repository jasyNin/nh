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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = Post::with(['user', 'tags'])
            ->where('status', 'published')
            ->withCount(['comments', 'views', 'likesCount as likes_count', 'reposts'])
            ->latest();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $posts = $query->paginate(10);

        $popularTags = Tag::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        // Получаем топ пользователей по рейтингу
        $topUsers = User::query()
            ->whereNotIn('rank', ['bot', 'moderator', 'admin'])
            ->orderBy('rating', 'desc')
            ->limit(3)
            ->get();

        // Получаем историю просмотров для авторизованного пользователя
        $viewedPosts = collect();
        if (auth()->check()) {
            $viewedPosts = auth()->user()->viewedPosts()
                ->with('post')
                ->whereHas('post', function($query) {
                    $query->where('status', 'published');
                })
                ->latest('viewed_at')
                ->take(5)
                ->get()
                ->pluck('post');
        }

        return view('home', compact('posts', 'popularTags', 'topUsers', 'viewedPosts'));
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
        $validated = $request->validate([
            'title' => 'required|string|max:255|regex:/^[\p{L}\p{N}\p{P}\p{Z}\p{Sm}\p{Sc}\p{Sk}\p{So}\s]+$/u',
            'content' => 'required|string|regex:/^[\p{L}\p{N}\p{P}\p{Z}\p{Sm}\p{Sc}\p{Sk}\p{So}\s]+$/u',
            'type' => 'required|string|in:question,post',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_draft' => 'boolean'
        ]);

        // Очищаем контент от потенциально опасных HTML-тегов
        $validated['title'] = strip_tags($validated['title']);
        $validated['title'] = htmlspecialchars($validated['title'], ENT_QUOTES, 'UTF-8');
        
        $validated['content'] = strip_tags($validated['content']);
        $validated['content'] = htmlspecialchars($validated['content'], ENT_QUOTES, 'UTF-8');

        $validated['user_id'] = auth()->id();
        $validated['status'] = $request->is_draft ? 'draft' : 'published';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('posts', $filename, 'public');
            
            // Устанавливаем публичную видимость для файла
            Storage::disk('public')->setVisibility($path, 'public');
            
            $validated['image'] = $path;
        }

        $post = Post::create($validated);

        // Синхронизируем теги
        if ($request->has('tags')) {
            $tagNames = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $tagIds = collect($tagNames)->map(function ($tag) {
                $tagName = trim($tag);
                if (empty($tagName)) return null;
                
                // Проверяем существование тега
                $existingTag = Tag::where('name', $tagName)->first();
                if ($existingTag) {
                    return $existingTag->id;
                }
                
                // Создаем новый тег
                try {
                    $newTag = Tag::create([
                        'name' => $tagName,
                        'slug' => Str::slug($tagName)
                    ]);
                    return $newTag->id;
                } catch (\Exception $e) {
                    \Log::error('Error creating tag: ' . $e->getMessage());
                    return null;
                }
            })->filter()->values()->toArray();
            
            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }

        if ($request->is_draft) {
            return redirect()->route('drafts.index')
                ->with('success', 'Черновик успешно сохранен');
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Пост успешно создан');
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:question,post',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_draft' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Удаляем старое изображение
            if ($post->image) {
                Storage::delete('public/' . $post->image);
            }
            
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/posts', $filename);
            $validated['image'] = 'posts/' . $filename;
        }

        $validated['status'] = $request->is_draft ? 'draft' : 'published';
        $post->update($validated);

        // Синхронизируем теги
        if ($request->has('tags')) {
            $tagNames = is_array($request->tags) ? $request->tags : explode(',', $request->tags);
            $tagIds = collect($tagNames)->map(function ($tag) {
                $tagName = trim($tag);
                if (empty($tagName)) return null;
                
                // Проверяем существование тега
                $existingTag = Tag::where('name', $tagName)->first();
                if ($existingTag) {
                    return $existingTag->id;
                }
                
                // Создаем новый тег
                try {
                    $newTag = Tag::create([
                        'name' => $tagName,
                        'slug' => Str::slug($tagName)
                    ]);
                    return $newTag->id;
                } catch (\Exception $e) {
                    \Log::error('Error creating tag: ' . $e->getMessage());
                    return null;
                }
            })->filter()->values()->toArray();
            
            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }

        if ($request->is_draft) {
            return redirect()->route('drafts.index')
                ->with('success', 'Черновик успешно обновлен');
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Пост успешно обновлен');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();
        return redirect()->route('home')->with('success', 'Пост успешно удален');
    }

    public function bookmark(Post $post)
    {
        $user = Auth::user();
        
        // Проверяем существование закладки напрямую в базе данных
        $existingBookmark = Bookmark::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();
        
        if ($existingBookmark) {
            $existingBookmark->delete();
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
        
        // Очищаем кэш после изменения
        Cache::forget("post_{$post->id}_bookmarked_by_{$user->id}");
        
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
            // Уменьшаем рейтинг автора поста
            $post->user->decrement('rating');
        } else {
            $post->likes()->create(['user_id' => auth()->id()]);
            $liked = true;
            // Увеличиваем рейтинг автора поста
            $post->user->increment('rating');
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