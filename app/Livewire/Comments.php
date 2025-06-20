<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Notifications\CommentNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

/**
 * Comments Livewire Component
 * 
 * Handles the creation, display, editing, and deletion of comments and their replies
 * for any commentable model. Supports nested comments, real-time updates, and user notifications.
 * 
 */
class Comments extends Component
{
    public Model $commentable;
    public $comments;
    public $body = '';
    public $editingCommentId = null;
    public $editingCommentBody = '';
    public $addComment = false;
    public $replyingTo = null;
    public $replyBody = '';
    

    private const COMMENT_RULES = 'required|min:1';
    
    protected $listeners = ['commentAdded' => 'loadComments'];

    protected $rules = [ //validation rules
        'body' => self::COMMENT_RULES,
        'editingCommentBody' => self::COMMENT_RULES,
        'replyBody' => self::COMMENT_RULES
    ];

    /**
     * Initialize the component with a commentable model
     * 
     * @param Model $commentable The model instance that can receive the comments
     * @return void
     */
    public function mount(Model $commentable): void
    {
        $this->commentable = $commentable;
        $this->loadComments();
    }

    /**
     * Render the component
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.comments', [
            'comments' => $this->comments,
            'post' => $this->commentable 
        ]);
    }
    
    /**
     * Load comments for the current commentable model
     * Includes parent comments and their replies, ordered by creation date
     * 
     * @return void
     */
    public function loadComments(): void
    {
        $this->comments = Comment::where('commentable_type', get_class($this->commentable))
                                ->where('commentable_id', $this->commentable->id)
                                ->whereNull('parent_id')
                                ->with(['user', 'user.profile', 'replies', 'replies.user'])
                                ->orderBy('created_at', 'desc')
                                ->get();
    }

    /**
     * Check if the authenticated user can modify a comment
     * 
     * @param Comment $comment The comment to check permissions for
     * @return bool True if user can modify the comment
     */
    private function canModifyComment(Comment $comment): bool 
    {
        return auth()->user()->isAdmin() || $comment->user_id === Auth::id();
    }


    /**
     * Executing comment operations with error handling
     * 
     * @param callable $operation The operation to execute
     * @return void
     */
    private function tryCatchComment(callable $operation): void
    {
        try {
            $operation();
        } catch (\Exception $e) {
            session()->flash('error', 'Something went wrong!');
            Log::error('Comment operation failed: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch events to notify listeners of comment changes
     * 
     * @return void
     */
    private function notifyCommentChange(): void
    {
        $this->dispatch('commentAdded')->to('comment-count');
        $this->dispatch('commentAdded');
    }

    /**
     * Create a new comment or reply
     * 
     * @param string $body The content of the comment
     * @param int|null $parentId The ID of the parent comment for replies
     * @return Comment The newly created comment
     */
    private function createComment(string $body, ?int $parentId = null): Comment
    {
        $comment = new Comment();
        $comment->commentable_type = get_class($this->commentable);
        $comment->commentable_id = $this->commentable->id;
        $comment->body = $body;
        $comment->user_id = Auth::id();
        $comment->parent_id = $parentId;
        $comment->save();

        if (!$parentId && $this->commentable->user_id !== Auth::id()) {
            $this->commentable->user->notify(
                CommentNotification::newComment($comment)
            );
        }

        return $comment;
    }

    /**
     * Store a new top-level comment
     * 
     * @return RedirectResponse|void
     */
    public function storeComment()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate(['body' => self::COMMENT_RULES]);

        $this->tryCatchComment(function () {
            $this->createComment($this->body);
            $this->resetFields();
            $this->loadComments();
            $this->notifyCommentChange();
        });
    }

    /**
     * Reset all form fields and component state
     * 
     * @return void
     */
    public function resetFields(): void
    {
        $this->body = '';
        $this->editingCommentBody = '';
        $this->editingCommentId = null;
        $this->addComment = false;
        $this->replyingTo = null;
        $this->replyBody = '';
        $this->dispatch('commentReset');
    }
    
    /**
     * Show the comment form
     * 
     * @return void
     */
    public function addComment(): void
    {
        $this->resetFields();
        $this->addComment = true;
    }

    /**
     * Start editing a comment
     * 
     * @param int $commentId The ID of the comment to edit
     * @return void
     */
    public function startEditing(int $commentId): void
    {
        $comment = Comment::find($commentId);
        if ($comment && $this->canModifyComment($comment)) {
            $this->editingCommentId = $commentId;
            $this->editingCommentBody = $comment->body;
        }
    }

    /**
     * Cancel editing a comment
     * 
     * @return void
     */
    public function cancelEditing(): void
    {
        $this->editingCommentId = null;
        $this->editingCommentBody = '';
    }

    /**
     * Update an existing comment
     * 
     * @return void
     */
    public function updateComment(): void
    {
        $this->validate(['editingCommentBody' => self::COMMENT_RULES]);

        $this->tryCatchComment(function () {
            $comment = Comment::find($this->editingCommentId);
            if ($comment && $this->canModifyComment($comment)) {
                $comment->update([
                    'body' => $this->editingCommentBody,
                    'is_edited' => true
                ]);
                $this->editingCommentId = null;
                $this->editingCommentBody = '';
                $this->loadComments();
            }
        });
    }
    
    /**
     * Cancel creating a new comment
     * 
     * @return void
     */
    public function cancelComment(): void
    {
        $this->resetFields();
    }
    
    /**
     * Delete a comment
     * 
     * @param int $id The ID of the comment to delete
     * @return void
     */
    public function deleteComment(int $id): void
    {
        $this->tryCatchComment(function () use ($id) {
            $comment = Comment::find($id);
            if ($comment && $this->canModifyComment($comment)) {
                $comment->delete();
                $this->loadComments();
                $this->notifyCommentChange();
            }
        });
    }
    
    /**
     * Start replying to a comment
     * 
     * @param int $commentId The ID of the comment being replied to
     * @return void
     */
    public function startReply(int $commentId): void
    {
        $this->replyingTo = $commentId;
        $this->replyBody = '';
    }
    
    /**
     * Cancel replying to a comment
     * 
     * @return void
     */
    public function cancelReply(): void
    {
        $this->replyingTo = null;
        $this->replyBody = '';
    }
    
    /**
     * Store a reply to a comment
     * 
     * @return RedirectResponse|void
     */
    public function storeReply()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        $this->validate(['replyBody' => self::COMMENT_RULES]);
    
        $this->tryCatchComment(function () {
            $reply = $this->createComment($this->replyBody, $this->replyingTo);
            $parentComment = Comment::find($this->replyingTo);
            
            if ($parentComment && $parentComment->user_id !== Auth::id()) {
                $parentComment->user->notify(
                    CommentNotification::reply($reply)
                );
            }

            $this->replyingTo = null;
            $this->replyBody = '';
            $this->loadComments();
            $this->notifyCommentChange();
        });
    }
}
