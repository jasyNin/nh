<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Notifications\ContentReported;
use App\Models\CommentReply;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ReportController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reportPost(Request $request, Post $post)
    {
        $request->validate([
            'type' => 'required|string|in:спам,оскорбление,неприемлемый контент,нарушение авторских прав,другое',
            'reason' => 'required|string|min:10|max:1000'
        ]);

        // Создаем жалобу
        $report = Report::create([
            'user_id' => auth()->id(),
            'reportable_id' => $post->id,
            'reportable_type' => Post::class,
            'reason' => $request->reason,
            'status' => 'pending'
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
        $report = Report::create([
            'user_id' => auth()->id(),
            'reportable_id' => $comment->id,
            'reportable_type' => Comment::class,
            'reason' => $request->reason,
            'status' => 'pending'
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
        $report = Report::create([
            'user_id' => auth()->id(),
            'reportable_id' => $reply->id,
            'reportable_type' => CommentReply::class,
            'reason' => $request->reason,
            'status' => 'pending'
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|in:App\Models\Post,App\Models\Comment',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:500'
        ]);

        $report = Report::create([
            'user_id' => auth()->id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Жалоба успешно отправлена');
    }
} 