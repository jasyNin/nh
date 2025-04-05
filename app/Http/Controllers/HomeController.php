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
        try {
            $query = Post::with(['user', 'tags'])
                ->withCount(['comments', 'views', 'likesCount as likes_count', 'reposts', 'answers'])
                ->latest();

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $posts = $query->paginate(10);
            
            // Получаем популярные теги только если есть посты
            $popularTags = $posts->isNotEmpty() 
                ? Tag::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->take(10)
                    ->get()
                : collect();

            // Получаем топ пользователей только если есть посты
            $topUsers = $posts->isNotEmpty()
                ? User::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->take(5)
                    ->get()
                : collect();

            // Получаем последние ответы только если есть посты
            $recentAnswers = $posts->isNotEmpty()
                ? Answer::with(['user', 'post'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get()
                : collect();

            return view('home', compact('posts', 'popularTags', 'topUsers', 'recentAnswers'));
        } catch (\Exception $e) {
            \Log::error('Error in HomeController@index: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }
} 