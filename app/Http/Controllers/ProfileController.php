<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

/**
 * Handles user profile-related operations.
 */
class ProfileController extends Controller
{
    /**
     * Display a user's profile page with their posts and comments.
     *
     * @param User $user The user whose profile to display
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        $sort = request('sort', 'new');
      
        $user->load(['profile']);

        $posts = $user->posts()
            ->when($sort === 'top', function ($query) {
                return $query->withCount('comments')
                            ->orderBy('comments_count', 'desc');
            }, function ($query) {
                return $query->latest();
            })
            ->get();

        $comments = $user->comments()
            ->with('commentable')
            ->whereHasMorph('commentable', ['App\Models\Post', 'App\Models\Profile'])
            ->latest()
            ->get();
        
        return view('users.show', [
            'user' => $user,
            'profile' => $user->profile,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }
}