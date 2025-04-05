<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Включаем логирование SQL запросов
            DB::enableQueryLog();
            
            // Проверяем существование таблиц перед запросами
            $tablesExist = [
                'posts' => Schema::hasTable('posts'),
                'tags' => Schema::hasTable('tags'),
                'users' => Schema::hasTable('users'),
                'answers' => Schema::hasTable('answers'),
                'polymorphic_likes' => Schema::hasTable('polymorphic_likes'),
                'reposts' => Schema::hasTable('reposts')
            ];
            
            Log::info('Проверка существования таблиц:', $tablesExist);
            
            // Проверяем существование таблицы posts
            if (!$tablesExist['posts']) {
                Log::error('Таблица posts не существует');
                return view('home', [
                    'posts' => collect([]),
                    'popularTags' => collect([]),
                    'topUsers' => collect([]),
                    'recentAnswers' => collect([]),
                    'error' => 'Ошибка: таблица постов не найдена. Пожалуйста, обратитесь к администратору.'
                ]);
            }
            
            // Упрощаем запрос для отладки
            $query = Post::query();
            
            // Добавляем только базовые связи
            if ($tablesExist['users']) {
                $query->with('user');
            }
            
            // Добавляем только базовые подсчеты
            if ($tablesExist['posts']) {
                $query->withCount('comments');
            }
            
            $query->latest();

            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            $posts = $query->paginate(10);
            
            // Выводим сгенерированные запросы в лог
            Log::info('Queries:', DB::getQueryLog());

            // Проверяем существование таблицы tags
            $popularTags = collect([]);
            if ($tablesExist['tags']) {
                $popularTags = Tag::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->take(10)
                    ->get();
            }

            // Проверяем существование таблицы users
            $topUsers = collect([]);
            if ($tablesExist['users']) {
                $topUsers = User::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->take(5)
                    ->get();
            }

            // Проверяем существование таблицы answers
            $recentAnswers = collect([]);
            if ($tablesExist['answers']) {
                $recentAnswers = Answer::with(['user', 'post'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            }

            return view('home', compact('posts', 'popularTags', 'topUsers', 'recentAnswers'));
        } catch (\Exception $e) {
            Log::error('Ошибка на главной странице: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Возвращаем упрощенную версию страницы в случае ошибки
            return view('home', [
                'posts' => collect([]),
                'popularTags' => collect([]),
                'topUsers' => collect([]),
                'recentAnswers' => collect([]),
                'error' => 'Произошла ошибка при загрузке данных: ' . $e->getMessage()
            ]);
        }
    }
} 