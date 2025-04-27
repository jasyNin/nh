<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;

class PostLikeController extends Controller
{
    public function toggle(Request $request, $postId)
    {
        $user = Auth::user();
        $post = Post::findOrFail($postId);

        $like = PostLike::where('user_id', $user->id)->where('post_id', $post->id)->first();
        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            PostLike::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
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

        $likesCount = $post->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
