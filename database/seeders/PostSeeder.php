<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        // Curated sample posts
        $samplePosts = [
            [
                'title' => 'Getting Started with Laravel 11',
                'content' => "Laravel 11 brings a significantly simplified application structure that makes it easier than ever to get started with PHP web development.\n\nThe new minimal skeleton reduces the number of default files, letting developers focus on what matters: building their application. Key improvements include a slimmer bootstrap process, consolidated middleware handling, and the new application service provider.\n\nIn this post, we'll walk through the most important changes and how they affect your day-to-day development workflow.",
            ],
            [
                'title' => 'Why Inertia.js Changes Everything',
                'content' => "Inertia.js bridges the gap between classic server-side rendering and modern single-page applications without requiring you to build a separate API.\n\nWith Inertia, you write your controllers and return data from them just like you always have — but instead of rendering a Blade view, you return a React (or Vue) component. The client-side router handles navigation without full page reloads.\n\nThis means you get the developer experience of React with the simplicity of server-driven routing.",
            ],
            [
                'title' => 'Docker for PHP Developers: A Practical Guide',
                'content' => "Running your Laravel application inside Docker containers ensures that your local environment matches production exactly, eliminating the classic 'works on my machine' problem.\n\nIn this guide, we cover setting up a docker-compose.yml with PHP-FPM, Nginx, and MySQL. We'll look at volume mounts for live code reloading during development, health checks to sequence container startup correctly, and a clean entrypoint script to handle migrations automatically.",
            ],
            [
                'title' => 'Understanding Laravel Policies',
                'content' => "Authorization in Laravel is handled elegantly through Policies — plain PHP classes that encapsulate the authorization logic for a given model.\n\nRather than scattering permission checks throughout your controllers, you define methods like update() and delete() on a Policy class and let Laravel resolve them automatically via Gate::authorize().\n\nThis keeps your controllers thin and your authorization logic testable and centralized.",
            ],
            [
                'title' => 'Building a Blog with React and Inertia',
                'content' => "Combining Laravel's expressive back-end with React's component model gives you a powerful full-stack toolkit.\n\nInertia.js acts as the glue: it sends page props as JSON from your controllers and React renders them client-side. Form submissions use Inertia's useForm hook, which handles CSRF tokens, validation errors, and redirect handling automatically.\n\nThe result is a smooth SPA experience with zero API boilerplate.",
            ],
        ];

        foreach ($samplePosts as $i => $postData) {
            Post::create([
                'user_id' => $users[$i % $users->count()]->id,
                ...$postData,
            ]);
        }

        // Additional random posts
        Post::factory(10)->recycle($users)->create();
    }
}
