<?php

namespace Modules\Comment\Actions;

use Illuminate\Validation\ValidationException;

class GetCommentableType
{
    /**
     * @throws \Exception
     */
    public function __invoke(string $type)
    {
        $types = config('comment.types');

        if ( $types && isset($types[$type]) ) {
            return $types[$type];
        }

        throw ValidationException::withMessages([
            'comment_type' => 'Invalid comment type',
        ]);
    }
}
