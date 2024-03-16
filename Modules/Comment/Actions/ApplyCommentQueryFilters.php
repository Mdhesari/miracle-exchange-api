<?php

namespace Modules\Comment\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyCommentQueryFilters extends BaseQueryFilters
{
    /**
     * @throws ValidationException
     */
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( is_null($this->user()) || $this->user()->cannot('comments') ) {
            $query->where('is_approved', true);
        }

        return $query;
    }
}
