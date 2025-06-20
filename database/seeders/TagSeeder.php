<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Announcement',
            'Discussion',
            'Help'
        ];


        foreach ($tags as $tagName) {
            Tag::create([
                'name' => $tagName
            ]);
        }
    }
}
