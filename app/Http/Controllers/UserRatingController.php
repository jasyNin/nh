<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\RankService;
use Illuminate\Support\Facades\DB;

class UserRatingController extends Controller
{
    private $rankOrder = [
        'supermind' => 1,
        'master' => 2,
        'erudite' => 3,
        'expert' => 4,
        'student' => 5,
        'novice' => 6,
        'admin' => 0,
        'moderator' => 0,
        'bot' => 0
    ];

    public function index()
    {
        $users = User::query()
            ->withCount(['posts' => function($query) {
                $query->where('status', 'published');
            }, 'comments'])
            ->whereNotIn('rank', ['bot', 'moderator', 'admin'])
            ->orderByRaw("CASE rank 
                WHEN 'supermind' THEN 1
                WHEN 'master' THEN 2
                WHEN 'erudite' THEN 3
                WHEN 'expert' THEN 4
                WHEN 'student' THEN 5
                WHEN 'novice' THEN 6
                ELSE 7 END")
            ->orderBy('rating', 'desc')
            ->get();

        $popularTags = Tag::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        $topUsers = User::withCount(['posts' => function($query) {
            $query->where('status', 'published');
        }])
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        return view('users.rating', compact('users', 'popularTags', 'topUsers'));
    }
}
