<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // --- Listing & Viewing ---

    public function test_posts_index_is_accessible_to_guests(): void
    {
        $response = $this->get(route('posts.index'));
        $response->assertStatus(200);
    }

    public function test_single_post_is_accessible_to_guests(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));
        $response->assertStatus(200);
    }

    // --- Create ---

    public function test_create_form_requires_authentication(): void
    {
        $response = $this->get(route('posts.create'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('posts.create'));
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => 'My First Post',
            'content' => 'This is the content of my first post and it is long enough.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('posts', [
            'title' => 'My First Post',
            'user_id' => $user->id,
        ]);
    }

    public function test_post_creation_requires_title_and_content(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'title' => '',
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'content']);
    }

    public function test_guest_cannot_create_post(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'Some title',
            'content' => 'Some content that is long enough.',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('posts', 0);
    }

    // --- Edit & Update ---

    public function test_post_owner_can_access_edit_form(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->get(route('posts.edit', $post));
        $response->assertStatus(200);
    }

    public function test_non_owner_cannot_access_edit_form(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs($other)->get(route('posts.edit', $post));
        $response->assertStatus(403);
    }

    public function test_post_owner_can_update_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->put(route('posts.update', $post), [
            'title' => 'Updated Title',
            'content' => 'Updated content that is long enough to pass validation.',
        ]);

        $response->assertRedirect(route('posts.show', $post));
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Updated Title']);
    }

    public function test_non_owner_cannot_update_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs($other)->put(route('posts.update', $post), [
            'title' => 'Hacked Title',
            'content' => 'Hacked content that is long enough.',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('posts', ['title' => 'Hacked Title']);
    }

    // --- Delete ---

    public function test_post_owner_can_delete_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create();

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_non_owner_cannot_delete_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs($other)->delete(route('posts.destroy', $post));

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_can_delete_any_post(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $response = $this->actingAs($admin)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
