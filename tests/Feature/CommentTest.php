<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    // --- Create ---

    public function test_authenticated_user_can_post_comment(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'comment' => 'This is a great article!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'comment' => 'This is a great article!',
        ]);
    }

    public function test_guest_can_post_comment(): void
    {
        $post = Post::factory()->create();

        $response = $this->post(route('comments.store', $post), [
            'comment' => 'Guest comment here.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => null,
            'comment' => 'Guest comment here.',
        ]);
    }

    public function test_comment_cannot_be_empty(): void
    {
        $post = Post::factory()->create();

        $response = $this->post(route('comments.store', $post), [
            'comment' => '',
        ]);

        $response->assertSessionHasErrors(['comment']);
        $this->assertDatabaseCount('comments', 0);
    }

    // --- Delete ---

    public function test_comment_owner_can_delete_their_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_post_owner_can_delete_comment_on_their_post(): void
    {
        $postOwner = User::factory()->create();
        $post = Post::factory()->for($postOwner)->create();
        $commenter = User::factory()->create();
        $comment = Comment::factory()->for($commenter)->for($post)->create();

        $response = $this->actingAs($postOwner)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_other_user_cannot_delete_comment(): void
    {
        $commenter = User::factory()->create();
        $other = User::factory()->create();
        $comment = Comment::factory()->for($commenter)->create();

        $response = $this->actingAs($other)->delete(route('comments.destroy', $comment));

        $response->assertStatus(403);
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::factory()->admin()->create();
        $comment = Comment::factory()->create();

        $response = $this->actingAs($admin)->delete(route('comments.destroy', $comment));

        $response->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }

    public function test_guest_cannot_delete_comment(): void
    {
        $comment = Comment::factory()->create();

        $response = $this->delete(route('comments.destroy', $comment));

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }
}
