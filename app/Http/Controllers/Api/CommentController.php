<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Создаем уведомление для автора поста
        if ($post->user_id !== Auth::id()) {
            $post->user->notifications()->create([
                'from_user_id' => Auth::id(),
                'type' => 'comment',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
            ]);
        }

        return response()->json($comment->load('user'));
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return response()->json($comment->load('user'));
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Комментарий удален']);
    }
}