<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = Post::all();
        $users = User::all();

        foreach ($posts as $post) {
            // 2–5 authenticated comments per post
            Comment::factory(rand(2, 5))
                ->recycle($users)
                ->create(['post_id' => $post->id]);

            // Occasional guest comment
            if (rand(0, 1)) {
                Comment::factory()
                    ->guest()
                    ->create(['post_id' => $post->id]);
            }
        }
    }
}
