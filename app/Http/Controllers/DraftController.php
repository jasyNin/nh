<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    public function index()
    {
        $drafts = Post::where('user_id', auth()->id())
            ->where('status', 'draft')
            ->with(['tags'])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('drafts.index', compact('drafts', 'popularTags', 'topUsers'));
    }

    public function show(Post $post)
    {
        $this->authorize('update', $post);
        return redirect()->route('posts.edit', $post);
    }
}
