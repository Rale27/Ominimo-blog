<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    // --- PostPolicy ---

    public function test_owner_can_update_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $policy = new PostPolicy();

        $this->assertTrue($policy->update($user, $post));
    }

    public function test_non_owner_cannot_update_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $policy = new PostPolicy();

        $this->assertFalse($policy->update($other, $post));
    }

    public function test_admin_can_update_any_post(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $policy = new PostPolicy();

        $this->assertTrue($policy->update($admin, $post));
    }

    public function test_owner_can_delete_their_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $policy = new PostPolicy();

        $this->assertTrue($policy->delete($user, $post));
    }

    public function test_admin_can_delete_any_post(): void
    {
        $admin = User::factory()->admin()->create();
        $post = Post::factory()->create();

        $policy = new PostPolicy();

        $this->assertTrue($policy->delete($admin, $post));
    }

    // --- CommentPolicy ---

    public function test_comment_owner_can_delete_their_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user)->create();

        $policy = new CommentPolicy();

        $this->assertTrue($policy->delete($user, $comment));
    }

    public function test_post_owner_can_delete_comments_on_their_post(): void
    {
        $postOwner = User::factory()->create();
        $post = Post::factory()->for($postOwner)->create();
        $commenter = User::factory()->create();
        $comment = Comment::factory()->for($commenter)->for($post)->create();

        $policy = new CommentPolicy();

        $this->assertTrue($policy->delete($postOwner, $comment));
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::factory()->admin()->create();
        $comment = Comment::factory()->create();

        $policy = new CommentPolicy();

        $this->assertTrue($policy->delete($admin, $comment));
    }

    public function test_unrelated_user_cannot_delete_comment(): void
    {
        $other = User::factory()->create();
        $comment = Comment::factory()->create();

        $policy = new CommentPolicy();

        $this->assertFalse($policy->delete($other, $comment));
    }
}
