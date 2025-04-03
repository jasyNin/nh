<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Показывает админ панель
     */
    public function index()
    {
        // Проверяем, что пользователь является администратором
        if (!auth()->user()->is_admin) {
            abort(403, 'Доступ запрещен');
        }

        return view('admin.dashboard');
    }
}
