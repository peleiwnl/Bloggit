<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * Livewire component for inline profile bio updating.
 */
class ProfileBioUpdate extends Component
{

    public $profile;
    public $bio;
    public $isEditing = false;

    /**
     * Initialize the component with the user's profile.
     *
     * @param \App\Models\Profile $profile The profile to edit
     * @return void
     */
    public function mount($profile)
    {
        $this->profile = $profile;
        $this->bio = $profile->bio;
    }

    /**
     * Toggle between edit and view modes.
     * 
     * @return void
     */
    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
        if (!$this->isEditing) {
            $this->bio = $this->profile->bio;
        }
    }

    /**
     * Update the user's profile bio.
     *
     * @return void
     */
    public function updateBio()
    {
        $this->validate([
            'bio' => 'required|string|max:500'
        ]);

        $this->profile->update([
            'bio' => $this->bio
        ]);

        $this->isEditing = false;
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile-bio-update');
    }
}