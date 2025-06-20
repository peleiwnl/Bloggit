<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */    
    public function run(): void
    {

        Profile::create([
            'user_id' => 1,
            'bio' => 'This is a bio for the profile',
            'avatar' => 'avatars/default.jpg',
        ]);


        User::whereDoesntHave('profile')->each(function ($user) {
            Profile::factory()->create([
                'user_id' => $user->id,
                'avatar' => 'avatars/default.jpg',
            ]);
        });
    }
}
