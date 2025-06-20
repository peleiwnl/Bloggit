<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

/**
 * Livewire component for handling profile image uploads.
 */
class ProfileImageUpload extends Component
{
    use WithFileUploads;

    public $avatar;
    public $user;
    public $profile;

    /**
     * Initialize the component with user and profile data.
     *
     * @param \App\Models\User $user The user whose avatar is being updated
     * @param \App\Models\Profile $profile The user's profile model
     * @return void
     */
    public function mount($user, $profile)
    {
        $this->user = $user;
        $this->profile = $profile;
    }

    /**
     * Handle the avatar file upload and update process.
     *
     * @return void
     * @throws \Exception When upload or profile update fails
     */
    public function updatedAvatar()
    {
        try {
            $this->validate([
                'avatar' => 'image|max:1024', // 1MB max file size
            ]);
    
            if (!$this->profile || !$this->avatar) {
                session()->flash('error', 'Missing profile or avatar data.');
                return;
            }
            
            // delete old avatar if it exists and isn't the default
            if ($this->profile->avatar && $this->profile->avatar !== 'avatars/default.jpg') {
                Storage::delete('public/' . $this->profile->avatar);
            }
            
            // store new avatar
            $avatarPath = $this->avatar->store('avatars', 'public');
            
            // update profile with new avatar path
            $result = $this->profile->update([
                'avatar' => $avatarPath
            ]);
    
            if ($result) {
                $this->redirect(request()->header('Referer'));
            }
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile-image-upload');
    }
}