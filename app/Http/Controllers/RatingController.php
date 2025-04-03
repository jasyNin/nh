<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'value' => 'required|integer|min:1|max:5'
        ]);

        $rating = $post->ratings()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['value' => $validated['value']]
        );

        // Создаем уведомление для автора поста
        if ($post->user_id !== auth()->id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'from_user_id' => auth()->id(),
                'type' => 'rating',
                'notifiable_type' => Rating::class,
                'notifiable_id' => $rating->id
            ]);
        }

        return response()->json($rating, 201);
    }

    public function destroy(Rating $rating)
    {
        $this->authorize('delete', $rating);

        $rating->delete();

        return response()->json(null, 204);
    }
} 