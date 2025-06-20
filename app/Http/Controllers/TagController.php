<?php

namespace App\Http\Controllers;

use App\Models\Tag;

/**
 * Handles the display of posts associated with specific tags.
 */
class TagController extends Controller
{
    /**
     * Display posts associated with a specific tag.
     *
     * @param string $tagName The name of the tag to find
     * @return \Illuminate\View\View
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException When tag is not found
     */
    public function show(string $tagName)
    {
        $tag = Tag::where('name', $tagName)->firstOrFail();
        return view('tags.show', ['tag' => $tag]);
    }
}