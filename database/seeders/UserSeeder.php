<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $u = new User;
        $u->name = 'James';
        $u->email = "james@email.com";
        $u->password = Hash::make('password');
        $u->save();


        User::factory()->count(9)->create();
    }
}
