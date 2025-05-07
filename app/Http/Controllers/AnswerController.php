<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Models\CommentReply;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::with([
            'user', 
            'post' => function($query) {
                $query->where('type', 'question')
                      ->with(['comments' => function($query) {
                          $query->with(['user', 'replies.user'])->latest();
                      }]);
            },
            'comments.user'
        ])
        ->whereHas('post', function($query) {
            $query->where('type', 'question');
        })
        ->withCount([
            'comments',
            'post' => function($query) {
                $query->withCount('comments');
            }
        ])
        ->orderBy('created_at', 'desc')
        ->get();

        // Получаем комментарии к постам текущего пользователя
        $commentsToUser = Comment::with(['user', 'post', 'replies.user'])
            ->whereHas('post', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->latest()
            ->get();

        // Получаем ответы на комментарии текущего пользователя
        $repliesToUser = CommentReply::with(['user', 'comment.post'])
            ->whereHas('comment', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->latest()
            ->get();

        // Получаем комментарии текущего пользователя
        $userComments = Comment::with(['user', 'post'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // Получаем ответы текущего пользователя
        $userReplies = CommentReply::with(['user', 'comment.post'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // Преобразуем коллекцию в пагинатор
        $answers = new \Illuminate\Pagination\LengthAwarePaginator(
            $answers,
            $answers->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url()]
        );

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('answers.index', compact('answers', 'popularTags', 'topUsers', 'commentsToUser', 'repliesToUser', 'userComments', 'userReplies'));
    }

    public function getUnreadCount()
    {
        $count = Comment::whereHas('post', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->where('created_at', '>', auth()->user()->last_notification_view ?? now()->subYears(100))
            ->count();

        $count += CommentReply::whereHas('comment', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('user_id', '!=', auth()->id())
            ->where('created_at', '>', auth()->user()->last_notification_view ?? now()->subYears(100))
            ->count();

        return response()->json([
            'has_unread' => $count > 0,
            'count' => $count
        ]);
    }
}
