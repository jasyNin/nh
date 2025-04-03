<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Notifications\ContentReported;
use App\Models\CommentReply;

class ReportController extends Controller
{
    public function reportPost(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required|string|in:спам,оскорбление,неприемлемый контент,нарушение авторских прав,другое',
            'reason' => 'required|string|min:10|max:1000'
        ]);

        // Создаем жалобу
        $complaint = Complaint::create([
            'user_id' => auth()->id(),
            'complaintable_id' => $post->id,
            'complaintable_type' => Post::class,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'new'
        ]);

        // Отправляем уведомление администраторам
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new ContentReported($post, $request->reason, $request->type));
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Спасибо за ваше сообщение. Мы рассмотрим жалобу в ближайшее время.']);
        }

        return back()->with('success', 'Спасибо за ваше сообщение. Мы рассмотрим жалобу в ближайшее время.');
    }

    public function reportComment(Request $request, Comment $comment)
    {
        $request->validate([
            'type' => 'required|string|in:спам,оскорбление,неприемлемый контент,нарушение авторских прав,другое',
            'reason' => 'required|string|min:10|max:1000'
        ]);

        // Создаем жалобу
        $complaint = Complaint::create([
            'user_id' => auth()->id(),
            'complaintable_id' => $comment->id,
            'complaintable_type' => Comment::class,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'new'
        ]);

        // Отправляем уведомление администраторам
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new ContentReported($comment, $request->reason, $request->type));
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Спасибо за ваше сообщение. Мы рассмотрим жалобу в ближайшее время.']);
        }

        return back()->with('success', 'Спасибо за ваше сообщение. Мы рассмотрим жалобу в ближайшее время.');
    }

    public function reportReply(Request $request, CommentReply $reply)
    {
        $request->validate([
            'type' => 'required|string|in:спам,оскорбление,неприемлемый контент,нарушение авторских прав,другое',
            'reason' => 'required|string|min:10|max:1000'
        ]);

        // Создаем жалобу
        $complaint = Complaint::create([
            'user_id' => auth()->id(),
            'complaintable_id' => $reply->id,
            'complaintable_type' => CommentReply::class,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'new'
        ]);

        // Отправляем уведомление администраторам
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new ContentReported($reply, $request->reason, $request->type));
        }

        if ($request->ajax()) {
            return response()->json(['message' => 'Спасибо за ваше сообщение. Мы рассмотрим жалобу в ближайшее время.']);
        }

        return back()->with('success', 'Спасибо за ваше сообщение. Мы рассмотрим жалобу в ближайшее время.');
    }
} 