<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;

/**
 * CommentCount Livewire Component
 * 
 * A real-time comment counter component
 */
class CommentCount extends Component
{
    public $post;
    
    /**
     * @var array<string, string> Event listeners for the component
     * Listens for 'commentAdded' event and refreshes the component
     */
    protected $listeners = ['commentAdded' => '$refresh'];

    /**
     * Initialize the component with a post
     * 
     * @param Post $post The post instance to track comments for
     * @return void
     */
    public function mount(Post $post): void
    {
        $this->post = $post;
    }

    /**
     * Render the component
     * 
     * Calculates the current comment count and passes it to the view
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.comment-count', [
            'count' => $this->post->comments->count()
        ]);
    }
}