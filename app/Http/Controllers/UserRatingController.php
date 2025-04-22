<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;

class UserRatingController extends Controller
{
    public function index()
    {
        $users = User::withCount(['posts', 'comments'])
            ->orderBy('rating', 'desc')
            ->paginate(20);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('users.rating', compact('users', 'popularTags', 'topUsers'));
    }
}
