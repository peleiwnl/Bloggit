<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Profile; 

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Profile::create([
            'user_id' => $user->id,
            'avatar' => 'avatars/default.jpg',
            'bio' => 'Hey, I am new here!'  
        ]);
    }

}
