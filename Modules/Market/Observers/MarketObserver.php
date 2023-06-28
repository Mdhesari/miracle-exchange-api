<?php

namespace Modules\Market\Observers;

use Modules\Market\Entities\Market;
use Modules\Market\Enums\MarketStatus;

class MarketObserver
{
    public function creating(Market $market)
    {
        if ( ! $market->status ) {
            $market->status = MarketStatus::Enabled->name;
        }
    }
}
