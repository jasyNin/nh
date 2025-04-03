<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Показывает список тегов
     */
    public function index()
    {
        $tags = Tag::withCount('posts')
            ->orderBy('id')
            ->get();
        return view('admin.tags.index', compact('tags'));
    }
    
    /**
     * Удаляет тег
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Тег успешно удален');
    }
}
