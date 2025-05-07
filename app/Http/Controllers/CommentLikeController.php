<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentLikeController extends Controller
{
    public function toggle(Comment $comment, Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            if (!$request->has('post_id')) {
                return response()->json(['error' => 'Post ID is required'], 400);
            }
            
            $like = CommentLike::where('user_id', $user->id)
                             ->where('comment_id', $comment->id)
                             ->first();
            
            if ($like) {
                $like->delete();
                // Уменьшаем рейтинг пользователя при отмене лайка
                $user->update(['rating' => $user->rating - 1]);
            } else {
                CommentLike::create([
                    'user_id' => $user->id,
                    'comment_id' => $comment->id,
                    'post_id' => $request->post_id
                ]);
                // Увеличиваем рейтинг пользователя при лайке
                $user->update(['rating' => $user->rating + 1]);
            }
            
            return response()->json([
                'likes_count' => $comment->likes()->count(),
                'liked' => !$like
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CommentLikeController::toggle: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
} 