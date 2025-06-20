<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $po = new Post();
        $po->user_id = 1; 
        $po->title = 'Hey, this is my first post!';
        $po->content = 'This is sample content for the post. Lets get started!';
        $po->save();

        $tags = Tag::all();
        $po->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());

        $users = User::all();

        Post::factory()
            ->count(9)
            ->create([
                'user_id' => fn() => $users->random()->id,
            ])
            ->each(function ($post) use ($tags) {
                $post->tags()->attach($tags->random(rand(1, 3))->pluck('id')->toArray());
            });
    }
}
