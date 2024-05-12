<?php

namespace Modules\Comment\Policies;

use Illuminate\Foundation\Auth\User;
use Modules\Comment\Entities\Comment;

class CommentPolicy
{
    public function delete(User $user, Comment $comment): bool
    {
        return intval($comment->user_id) === intval($user->id) || $user->can('comments');
    }

    public function update(User $user, Comment $comment): bool
    {
        return intval($comment->user_id) === intval($user->id) || $user->can('comments');
    }

    public function show(User $user, Comment $comment): bool
    {
        return $comment->is_approved || intval($comment->user_id) === intval($user->id) || $user->can('comments');
    }

    public function approve(User $user, Comment $comment): bool
    {
        return $user->can('comments');
    }
}
