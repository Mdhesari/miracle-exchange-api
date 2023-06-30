<?php

namespace Modules\Gateway\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyGatewayQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( isset($data['is_active']) ) {
            $query->where('is_active', $data['is_active']);
        }

        return $query;
    }
}
