<?php

namespace Modules\Comment\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;

class CreateComment
{
    public function __invoke(array $data)
    {
        return Comment::create(array_replace($data, [
            'user_id' => Auth::id(),
        ]));
    }
}
