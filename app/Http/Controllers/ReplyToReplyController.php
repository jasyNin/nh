<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\ReplyToReply;
use Illuminate\Support\Facades\Auth;

class ReplyToReplyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Reply $reply)
    {
        if (auth()->check() && auth()->user()->isRestricted()) {
            abort(403, 'Вы временно ограничены в действиях до ' . auth()->user()->restricted_until->format('d.m.Y H:i'));
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $replyToReply = new ReplyToReply();
        $replyToReply->content = $request->content;
        $replyToReply->user_id = Auth::id();
        $replyToReply->reply_id = $reply->id;
        $replyToReply->save();

        return response()->json([
            'success' => true,
            'message' => 'Ответ добавлен',
            'reply_to_reply_id' => $replyToReply->id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user()->name,
            'user_avatar' => Auth::user()->avatar_url,
            'content' => $replyToReply->content,
            'created_at' => $replyToReply->created_at->diffForHumans(),
        ]);
    }
}
