<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->is_admin) {
            return true;
        }

        // Comment owner
        if ($comment->user_id === $user->id) {
            return true;
        }

        // Post owner
        return $comment->post->user_id === $user->id;
    }
}
