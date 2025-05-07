<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostRepost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostRepostController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = Auth::user();
        
        $repost = PostRepost::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->first();

        if ($repost) {
            $repost->delete();
            $isReposted = false;
        } else {
            PostRepost::create([
                'user_id' => $user->id,
                'post_id' => $post->id
            ]);
            $isReposted = true;
        }

        // Получаем актуальное количество репостов
        $repostsCount = $post->reposts()->count();

        return response()->json([
            'reposts_count' => $repostsCount,
            'is_reposted' => $isReposted
        ]);
    }
}
