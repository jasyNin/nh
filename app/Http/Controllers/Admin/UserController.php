<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Показывает список пользователей
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
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

        $user->is_admin = $request->role === 'admin';
        $user->is_moderator = $request->role === 'moderator';
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Роль пользователя успешно изменена');
    }
}
