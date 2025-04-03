<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function index()
    {
        $answers = Answer::with(['user', 'post'])
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        $topUsers = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('answers.index', compact('answers', 'popularTags', 'topUsers'));
    }
}
