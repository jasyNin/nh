<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Кэшируем популярные теги на 1 час
            $popularTags = Cache::remember('popular_tags', 3600, function () {
                return Tag::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->take(10)
                    ->get();
            });

            // Кэшируем топ пользователей на 1 час
            $topUsers = Cache::remember('top_users', 3600, function () {
                return User::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->take(5)
                    ->get();
            });

            // Кэшируем последние ответы на 5 минут
            $recentAnswers = Cache::remember('recent_answers', 300, function () {
                return Answer::with(['user', 'post'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            });

            // Запрос постов с фильтрацией по типу
            $query = Post::with(['user', 'tags'])
                ->withCount(['comments', 'views', 'likesCount as likes_count', 'reposts', 'answers'])
                ->latest();

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $posts = $query->paginate(10);

            // Логируем успешный запрос
            Log::info('Home page loaded successfully', [
                'user_id' => auth()->id(),
                'is_guest' => auth()->guest(),
                'posts_count' => $posts->count(),
                'tags_count' => $popularTags->count(),
                'users_count' => $topUsers->count(),
                'answers_count' => $recentAnswers->count()
            ]);

            return view('home', compact('posts', 'popularTags', 'topUsers', 'recentAnswers'));

        } catch (\Exception $e) {
            // Подробное логирование ошибки
            Log::error('Error in HomeController@index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'is_guest' => auth()->guest(),
                'request_type' => $request->type ?? 'all'
            ]);
            
            // Возвращаем пустые коллекции с сообщением об ошибке
            return view('home', [
                'posts' => collect([]),
                'popularTags' => collect([]),
                'topUsers' => collect([]),
                'recentAnswers' => collect([]),
                'error' => 'Произошла ошибка при загрузке данных. Пожалуйста, попробуйте позже.'
            ]);
        }
    }
} 