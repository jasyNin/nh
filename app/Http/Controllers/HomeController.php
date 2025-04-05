<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Включаем логирование SQL запросов
        DB::enableQueryLog();
        
        $query = Post::with(['user', 'tags', 'comments.user', 'answers.user'])
            ->withCount(['comments', 'views', 'likesCount as likes_count', 'reposts', 'answers'])
            ->latest();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $posts = $query->paginate(10);
        
        // Выводим сгенерированные запросы в лог
        \Log::info('Queries:', DB::getQueryLog());

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        // Получаем последние ответы для правой колонки
        $recentAnswers = Answer::with(['user', 'post'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('home', compact('posts', 'popularTags', 'topUsers', 'recentAnswers'));
    }
} 