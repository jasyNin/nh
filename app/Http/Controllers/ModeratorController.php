<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ModeratorController extends Controller
{
    public function __construct()
    {
        // Убираем middleware из конструктора
    }

    private function checkModerator()
    {
        if (!auth()->check() || !auth()->user()->is_moderator) {
            abort(403, 'Доступ запрещен');
        }
    }

    public function dashboard()
    {
        $this->checkModerator();
        $complaints = Complaint::with(['complaintable', 'user'])->latest()->get();
        $users = User::latest()->get();
        $posts = Post::with('user')->latest()->get();
        $comments = Comment::with(['user', 'post'])->latest()->get();

        // Получаем общее количество жалоб
        $totalComplaintsCount = Complaint::count();

        return view('moderator.dashboard', compact('complaints', 'users', 'posts', 'comments', 'totalComplaintsCount'));
    }

    public function reports(Request $request)
    {
        $this->checkModerator();
        $query = Report::with(['reportable', 'user'])->latest();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reason', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $reports = $query->get();
        return view('moderator.reports', compact('reports'));
    }

    public function users(Request $request)
    {
        $this->checkModerator();
        $query = User::withCount(['complaints', 'commentComplaints'])->latest();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $users = $query->get();
        return view('moderator.users', compact('users'));
    }

    public function content(Request $request)
    {
        $this->checkModerator();
        $search = $request->search;
        
        $postsQuery = Post::with('user')->latest();
        $commentsQuery = Comment::with(['user', 'post'])->latest();
        
        if ($search) {
            $postsQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
            
            $commentsQuery->where(function($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('post', function($q) use ($search) {
                      $q->where('title', 'like', "%{$search}%");
                  });
            });
        }
        
        $posts = $postsQuery->get();
        $comments = $commentsQuery->get();
        
        return view('moderator.content', compact('posts', 'comments'));
    }

    public function hidePost(Post $post)
    {
        $this->checkModerator();
        $post->update(['is_hidden' => true]);
        return back()->with('success', 'Пост скрыт');
    }

    public function hideComment(Comment $comment)
    {
        $this->checkModerator();
        $comment->update(['is_hidden' => true]);
        return back()->with('success', 'Комментарий скрыт');
    }

    public function restrictUser(Request $request, User $user)
    {
        $this->checkModerator();
        $duration = $request->input('duration');
        $user->restricted_until = null;
        $user->save();
        if ($duration === 'forever') {
            $restrictedUntil = now()->addYears(100);
        } else {
            $restrictedUntil = now()->addDays((int)$duration);
        }
        $user->update(['restricted_until' => $restrictedUntil]);
        $user->refresh();
        return back()->with('success', 'Пользователь ограничен');
    }

    public function deleteUser(User $user)
    {
        $this->checkModerator();
        $user->delete();
        return back()->with('success', 'Пользователь удален');
    }

    public function resolveReport(Report $report)
    {
        $this->checkModerator();
        $report->update(['status' => 'resolved']);
        return back()->with('success', 'Жалоба обработана');
    }

    public function deletePost(Post $post)
    {
        $this->checkModerator();
        $post->delete();
        return back()->with('success', 'Пост успешно удалён.');
    }

    public function deleteComment(Comment $comment)
    {
        $this->checkModerator();
        $comment->delete();
        return back()->with('success', 'Комментарий успешно удалён.');
    }
} 