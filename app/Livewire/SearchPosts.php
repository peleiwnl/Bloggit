<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

/**
 * Livewire component for real-time post searching.
 */
class SearchPosts extends Component
{
    /**
     * The current search query string.
     * 
     * @var string
     */
    public $search = '';

    /**
     * Render the search results view.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $posts = $this->search
            ? Post::with(['user', 'tags']) 
                ->where('title', 'like', '%' . $this->search . '%') 
                ->take(10)  // limit results
                ->get()
            : collect(); 

        return view('livewire.search-posts', [
            'posts' => $posts,
        ]);
    }
}