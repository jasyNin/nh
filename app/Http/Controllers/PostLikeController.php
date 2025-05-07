<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = auth()->user();
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            // Уменьшаем рейтинг пользователя при отмене лайка
            $user->update(['rating' => $user->rating - 1]);
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            // Увеличиваем рейтинг пользователя при лайке
            $user->update(['rating' => $user->rating + 1]);
            $liked = true;

            // Создаем уведомление для автора поста
            if ($post->user_id !== $user->id) {
                $post->user->notifications()->create([
                    'from_user_id' => $user->id,
                    'type' => 'like',
                    'notifiable_type' => Post::class,
                    'notifiable_id' => $post->id
                ]);
            }
        }

        return response()->json([
            'likes_count' => $post->likes()->count(),
            'liked' => $liked
        ]);
    }
}
