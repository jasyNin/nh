<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\RankService;

class UserController extends Controller
{
    private $rankService;

    public function __construct(RankService $rankService)
    {
        $this->rankService = $rankService;
    }

    /**
     * Показывает список пользователей
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Поиск по имени или email
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Фильтрация по роли
        if ($request->has('role')) {
            $role = $request->get('role');
            if ($role === 'admin') {
                $query->where('is_admin', true);
            } elseif ($role === 'moderator') {
                $query->where('is_moderator', true);
            } elseif ($role === 'user') {
                $query->where('is_admin', false)
                      ->where('is_moderator', false);
            }
        }

        // Сортировка
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $users = $query->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users.index', compact('users'))->render()
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Удаляет пользователя (мягкое удаление)
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно удален');
    }

    /**
     * Восстанавливает удаленного пользователя
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно восстановлен');
    }

    /**
     * Изменяет роль пользователя
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,moderator,admin'
        ]);

        // Обновляем роль
        $user->is_admin = $request->role === 'admin';
        $user->is_moderator = $request->role === 'moderator';

        // Обновляем ранг в зависимости от роли
        if ($request->role === 'admin') {
            $user->rank = 'admin';
        } elseif ($request->role === 'moderator') {
            $user->rank = 'moderator';
        } else {
            // Для обычного пользователя вычисляем ранг на основе рейтинга
            $user->rank = $this->rankService->getRankByPoints($user->rating);
        }

        $user->save();

        // Очищаем кэш для обновления иконок и имен рангов
        cache()->forget("user_{$user->id}_rank_icon");
        cache()->forget("user_{$user->id}_rank_name");

        return redirect()->route('admin.users.index')->with('success', 'Роль пользователя успешно изменена');
    }
}
