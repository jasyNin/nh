<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentReplyController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|min:1|max:1000|regex:/^[\p{L}\p{N}\p{P}\p{Z}\p{Sm}\p{Sc}\p{Sk}\p{So}\s]+$/u'
        ]);

        // Очищаем контент от потенциально опасных HTML-тегов
        $content = strip_tags($request->content);
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        $reply = $comment->replies()->create([
            'content' => $content,
            'user_id' => auth()->id()
        ]);

        if ($request->ajax()) {
            return view('components.comment-reply', ['reply' => $reply])->render();
        }

        return back()->with('success', 'Ответ успешно добавлен');
    }

    public function update(Request $request, CommentReply $reply)
    {
        $this->authorize('update', $reply);

        $request->validate([
            'content' => 'required|string|min:1|max:1000'
        ]);

        $reply->update([
            'content' => $request->content
        ]);

        if ($request->ajax()) {
            return $reply->content;
        }

        return back()->with('success', 'Ответ успешно обновлен');
    }

    public function destroy(CommentReply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Ответ успешно удален');
    }
} 