<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        $c = new Comment();
        $c->user_id = $users->random()->id;
        $post = $posts->random();
        $c->commentable_id = $post->id;
        $c->commentable_type = Post::class;
        $c->body = "Wow, that looks amazing!";
        $c->save();

        Comment::factory()
            ->count(19)
            ->create([
                'user_id' => fn() => $users->random()->id,
                'commentable_id' => fn() => $posts->random()->id,
                'commentable_type' => Post::class,
            ]);
    }
}
