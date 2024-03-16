<?php

namespace Modules\Comment\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Events\CommentCreated;

class CreateComment
{
    public function __invoke(array $data)
    {
        $comment = Comment::create(array_replace($data, [
            'user_id' => Auth::id(),
        ]));

        event(new CommentCreated($comment));

        return $comment;
    }
}
