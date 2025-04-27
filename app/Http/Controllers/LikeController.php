<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            if ($post->likedBy($user)) {
                $post->likes()->where('user_id', $user->id)->delete();
                $liked = false;
            } else {
                $post->likes()->create(['user_id' => $user->id]);
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
            
            // Очищаем кэш
            Cache::forget("post_{$post->id}_likes_count");
            Cache::forget("post_{$post->id}_liked_by_{$user->id}");
            
            return response()->json([
                'likes_count' => $post->likes()->count(),
                'liked' => $liked
            ]);
        } catch (\Exception $e) {
            Log::error('Error in LikeController::toggle: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
} 