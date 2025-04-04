<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content']
        ]);

        // Создаем уведомление для автора поста
        if ($post->user_id !== Auth::id()) {
            $post->user->notifications()->create([
                'from_user_id' => Auth::id(),
                'type' => 'comment',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id
            ]);
        }

        // Если запрос ожидает JSON (AJAX запрос)
        if ($request->expectsJson()) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'comment_id' => $comment->id,
                'user_name' => $user->name,
                'user_avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png'),
                'user_url' => route('users.show', $user),
                'content' => $comment->content,
                'likes_count' => 0,
                'comments_count' => $post->comments_count
            ]);
        }

        return back()->with('success', 'Комментарий добавлен');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|min:1'
        ]);

        $comment->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'content' => $comment->content
            ]);
        }

        return back()->with('success', 'Комментарий обновлен!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true
            ]);
        }

        return back()->with('success', 'Комментарий удален!');
    }

    public function like(Comment $comment)
    {
        $user = auth()->user();
        
        if ($comment->likedBy($user)) {
            $comment->likes()->where('user_id', $user->id)->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'likes_count' => $comment->likes_count,
            'liked' => $liked
        ]);
    }
} 