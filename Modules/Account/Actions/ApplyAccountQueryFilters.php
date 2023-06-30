<?php

namespace Modules\Account\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyAccountQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( $this->user()->cannot('accounts') ) {
            $query->where('user_id', $this->user()->id);
        }

        return $query;
    }
}
