<?php

namespace App\Http\Controllers;

use App\Models\CommentReply;
use App\Models\ReplyLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReplyLikeController extends Controller
{
    public function toggle(CommentReply $reply, Request $request)
    {
        try {
        $user = auth()->user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            if (!$request->has('post_id')) {
                return response()->json(['error' => 'Post ID is required'], 400);
            }
            
            $like = ReplyLike::where('user_id', $user->id)
                           ->where('reply_id', $reply->id)
                           ->first();

        if ($like) {
            $like->delete();
            // Уменьшаем рейтинг пользователя при отмене лайка
            $user->update(['rating' => $user->rating - 1]);
        } else {
                ReplyLike::create([
                    'user_id' => $user->id,
                    'reply_id' => $reply->id,
                    'post_id' => $request->post_id
                ]);
            // Увеличиваем рейтинг пользователя при лайке
            $user->update(['rating' => $user->rating + 1]);
        }

        return response()->json([
            'likes_count' => $reply->likes()->count(),
            'liked' => !$like
        ]);
        } catch (\Exception $e) {
            Log::error('Error in ReplyLikeController::toggle: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
