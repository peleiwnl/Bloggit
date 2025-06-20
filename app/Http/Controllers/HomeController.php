<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Handles all post-related operations including viewing, creating, editing, and deleting posts.
 */
class HomeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a paginated list of posts with optional tag filtering.
     *
     * @param Request $request Contains the filter parameter for tag-based filtering
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        
        $query = Post::with(['user', 'tags']); 
    
        if ($filter !== 'all') {
            $query->whereHas('tags', function($q) use ($filter) {
                $q->where('name', $filter);
            });
        }
    
        $posts = $query->latest()->paginate(5);
        
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('posts.create'); 
    }

    /**
     * Show the form for editing a post.
     *
     * @param Post $post The post to edit
     * @return \Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $tags = Tag::orderBy('name', 'asc')->get();
        return view('posts.edit', ['post' => $post]);
    }

    

    /**
     * Display a specific post.
     *
     * @param Post $post The post to display
     * @return \Illuminate\View\View
     */
    public function show(Post $post)
    {
        return view('posts.show', ['post' => $post]);
    }


    /**
     * Delete a post.
     *
     * @param Post $post The post to delete
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('message', 'Post was deleted.');
    }
}