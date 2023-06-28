<?php

namespace Modules\Market\Actions;

use Mdhesari\LaravelQueryFilters\Abstract\BaseQueryFilters;
use Modules\Market\Enums\MarketStatus;

class ApplyMarketQueryFilters extends BaseQueryFilters
{
    public function __invoke($query, array $data)
    {
        parent::__invoke($query, $data);

        if ( ! $this->user() || $this->user()?->cannot('market') ) {
            $query->where('status', MarketStatus::Enabled->name);
        } else {
            if ( isset($data['status']) )
                $query->where('status', $data['status']);
        }

        return $query;
    }
}
