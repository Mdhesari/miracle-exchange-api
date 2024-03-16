<?php

namespace Modules\Comment\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;

class UpdateComment
{
    public function __invoke(Comment $comment, array $data): Comment
    {
        $comment->update(array_replace($data, [
            'user_id' => Auth::id(),
        ]));

        return $comment;
    }
}
