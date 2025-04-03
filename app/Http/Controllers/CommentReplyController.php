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

        return response()->json([
            'html' => view('components.comment-reply', ['reply' => $reply])->render(),
            'replies_count' => $comment->replies_count
        ]);
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

        return response()->json([
            'content' => $reply->content
        ]);
    }

    public function destroy(CommentReply $reply)
    {
        $this->authorize('delete', $reply);

        $reply->delete();

        return response()->json([
            'replies_count' => $reply->comment->replies_count
        ]);
    }
} 