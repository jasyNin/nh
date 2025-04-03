<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\Models\Tag;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'current_password' => ['required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар, если он существует
            if ($user->avatar) {
                $oldPath = public_path('storage/' . $user->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Получаем файл
            $image = $request->file('avatar');
            
            // Создаем уникальное имя файла
            $filename = time() . '.' . $image->getClientOriginalExtension();
            
            // Путь для сохранения
            $path = 'avatars/' . $filename;
            
            // Создаем директорию, если она не существует
            if (!file_exists(public_path('storage/avatars'))) {
                mkdir(public_path('storage/avatars'), 0777, true);
            }
            
            // Сохраняем изображение
            $image->move(public_path('storage/avatars'), $filename);
            
            $validated['avatar'] = $path;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'Профиль успешно обновлен!');
    }

    public function show(Request $request)
    {
        $user = $request->user();
        
        $posts = $user->posts()
            ->with(['user', 'tags'])
            ->latest()
            ->paginate(10);

        $comments = $user->comments()
            ->with(['post', 'user'])
            ->latest()
            ->paginate(10);

        $bookmarks = $user->bookmarks()
            ->with(['post.user'])
            ->latest()
            ->paginate(10);

        // Подсчет статистики
        $stats = [
            'posts_count' => $user->posts()->count(),
            'comments_count' => $user->comments()->count(),
            'likes_received' => $user->posts()->join('likes', 'posts.id', '=', 'likes.post_id')->count(),
            'bookmarks_count' => $user->bookmarks()->count(),
        ];

        return view('profile.show', compact('user', 'posts', 'comments', 'bookmarks', 'stats'));
    }

    public function password(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Пароль успешно изменен');
    }
} 