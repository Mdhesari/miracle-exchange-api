<?php

namespace Modules\Wallet\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;

class ApplyWalletQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if (isset($data['inactive']) && $data['active']) {
            $query->active();
        } else {
            $query->inActive();
        }

        return $query;
    }
}