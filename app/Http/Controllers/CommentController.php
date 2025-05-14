<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;
use App\Models\Answer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000|regex:/^[\p{L}\p{N}\p{P}\p{Z}\p{Sm}\p{Sc}\p{Sk}\p{So}\s]+$/u'
        ]);

        // Очищаем контент от потенциально опасных HTML-тегов
        $content = strip_tags($validated['content']);
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $content
        ]);

        // Увеличиваем рейтинг автора комментария
        auth()->user()->increment('rating');

        // Создаем уведомление для автора поста
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new CommentNotification($comment));
            $post->user->notifications()
                ->latest()
                ->first()
                ->update(['viewed' => false]);
        }

        // Если запрос ожидает JSON (AJAX запрос)
        if ($request->expectsJson()) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'post_id' => $post->id,
                'comments_count' => $post->comments()->count(),
                'comment_html' => view('components.comment', [
                    'comment' => $comment,
                    'post' => $post
                ])->render()
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

    public function storeAnswerComment(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000|regex:/^[\p{L}\p{N}\p{P}\p{Z}\p{Sm}\p{Sc}\p{Sk}\p{So}\s]+$/u'
        ]);

        // Очищаем контент от потенциально опасных HTML-тегов
        $content = strip_tags($validated['content']);
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'content' => $content,
            'post_id' => $answer->post_id,
            'answer_id' => $answer->id
        ]);

        // Создаем уведомление для автора ответа
        if ($answer->user_id !== auth()->id()) {
            $answer->user->notify(new CommentNotification($comment));
            $answer->user->notifications()
                ->latest()
                ->first()
                ->update(['viewed' => false]);
        }

        // Если запрос ожидает JSON (AJAX запрос)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'answer_id' => $answer->id,
                'comments_count' => $answer->comments()->count(),
                'comment_html' => view('components.comment', [
                    'comment' => $comment,
                    'post' => $answer->post
                ])->render()
            ]);
        }

        return back()->with('success', 'Комментарий добавлен');
    }
} 