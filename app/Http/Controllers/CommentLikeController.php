<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentLikeController extends Controller
{
    public function toggle(Comment $comment)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            
            if ($comment->likedBy($user)) {
                $comment->likes()->where('user_id', $user->id)->delete();
                $liked = false;
            } else {
                $comment->likes()->create(['user_id' => $user->id]);
                $liked = true;
            }
            
            return response()->json([
                'likes_count' => $comment->likes()->count(),
                'liked' => $liked
            ]);
        } catch (\Exception $e) {
            Log::error('Error in CommentLikeController::toggle: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
} 