<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Answer;
use App\Models\Comment;
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
                return User::withCount(['posts' => function($query) {
                    $query->where('status', 'published');
                }, 'comments'])
                    ->orderBy('posts_count', 'desc')
                    ->take(5)
                    ->get();
            });

            // Кэшируем последние комментарии на 5 минут
            $recentAnswers = Cache::remember('recent_answers', 300, function () {
                return Comment::with(['user', 'post'])
                    ->whereHas('post', function($query) {
                        $query->where('status', 'published');
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            });

            // Топ-3 пользователей по рейтингу (как на странице рейтинга)
            $topRatingUsers = User::query()
                ->withCount(['posts' => function($query) {
                    $query->where('status', 'published');
                }, 'comments'])
                ->whereNotIn('rank', ['bot', 'moderator', 'admin'])
                ->orderByRaw("CASE rank 
                    WHEN 'supermind' THEN 1
                    WHEN 'master' THEN 2
                    WHEN 'erudite' THEN 3
                    WHEN 'expert' THEN 4
                    WHEN 'student' THEN 5
                    WHEN 'novice' THEN 6
                    ELSE 7 END")
                ->orderBy('rating', 'desc')
                ->take(3)
                ->get();

            // Запрос постов с фильтрацией по типу
            $query = Post::with(['user', 'tags', 'likes'])
                ->where('status', 'published')
                ->where('is_hidden', false)
                ->withCount(['comments', 'likes'])
                ->orderBy('created_at', 'desc');

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            // Для AJAX-запросов возвращаем только посты
            if ($request->ajax()) {
                $posts = $query->skip($request->input('skip', 0))->get();
                return response()->json([
                    'posts' => $posts,
                    'hasMore' => false
                ]);
            }

            // Для первого запроса загружаем все посты
            $posts = $query->get();

            // Получаем просмотренные посты для авторизованного пользователя
            $viewedPosts = collect();
            if (auth()->check()) {
                $viewedPosts = auth()->user()->viewedPosts()
                    ->where('status', 'published')
                    ->take(5)
                    ->get();
            }

            // Логируем успешный запрос
            Log::info('Home page loaded successfully', [
                'user_id' => auth()->id(),
                'is_guest' => auth()->guest(),
                'posts_count' => $posts->count(),
                'tags_count' => $popularTags->count(),
                'users_count' => $topUsers->count(),
                'answers_count' => $recentAnswers->count()
            ]);

            return view('home', compact('posts', 'popularTags', 'topUsers', 'recentAnswers', 'viewedPosts', 'topRatingUsers'));

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
                'viewedPosts' => collect([]),
                'topRatingUsers' => collect([]),
                'error' => 'Произошла ошибка при загрузке данных. Пожалуйста, попробуйте позже.'
            ]);
        }
    }
} 