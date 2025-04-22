<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;

class CommentReplyController extends Controller
{
    public function store(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|min:1|max:1000'
        ]);

        $reply = $comment->replies()->create([
            'content' => $request->content,
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