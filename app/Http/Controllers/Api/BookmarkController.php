<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', Auth::id())
            ->with(['post.user', 'post.tags'])
            ->latest()
            ->paginate(10);

        return response()->json($bookmarks);
    }

    public function store(Request $request, Post $post)
    {
        $bookmark = Bookmark::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return response()->json($bookmark->load(['post.user', 'post.tags']));
    }

    public function destroy(Bookmark $bookmark)
    {
        $this->authorize('delete', $bookmark);

        $bookmark->delete();

        return response()->json(['message' => 'Закладка удалена']);
    }
} 