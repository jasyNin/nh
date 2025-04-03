<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'tags'])
            ->where('is_draft', false)
            ->latest();

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:question,post',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'is_draft' => 'boolean',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_draft' => $request->is_draft ?? false,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load(['user', 'tags']));
    }

    public function show(Post $post)
    {
        return response()->json($post->load(['user', 'tags', 'comments.user']));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:question,post',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'is_draft' => 'boolean',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'is_draft' => $request->is_draft ?? false,
        ]);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json($post->load(['user', 'tags']));
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(['message' => 'Пост удален']);
    }

    public function drafts()
    {
        $drafts = Post::where('user_id', Auth::id())
            ->where('is_draft', true)
            ->with(['user', 'tags'])
            ->latest()
            ->paginate(10);

        return response()->json($drafts);
    }
} 