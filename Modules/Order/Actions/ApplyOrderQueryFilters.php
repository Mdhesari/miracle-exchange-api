<?php

namespace Modules\Order\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyOrderQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if (isset($data['status'])) {
            $query->where('status', $data['status']);
        }

        if (isset($data['market_id'])) {
            $query->where('market_id', $data['market_id']);
        }

        if (isset($data['expand'])) {
            //TODO: temporary in order to fix scope conflicts
            in_array('transactions', explode(',', $data['expand'])) && $query->with('transactions');;
        }

        return $query;
    }
}
