<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->paginate(20);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        return view('tags.index', compact('tags', 'popularTags', 'topUsers'));
    }

    public function show(Tag $tag)
    {
        $posts = $tag->posts()
            ->with(['user', 'tags'])
            ->withCount(['answers', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(5)
            ->get();

        return view('tags.show', compact('tag', 'posts', 'popularTags', 'topUsers'));
    }
} 