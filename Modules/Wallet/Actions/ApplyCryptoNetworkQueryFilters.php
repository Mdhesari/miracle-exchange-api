<?php

namespace Modules\Wallet\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyCryptoNetworkQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        $query = parent::__invoke($query, $data);

        if (! $this->user() || $this->user()->cannot('crypto')) {
            $query->whereIsActive(true);
        }

        return $query;
    }
}
