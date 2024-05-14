<?php

namespace Modules\Market\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Market\Entities\Market;
use Modules\Market\Enums\MarketStatus;

class MarketObserver
{
    public function creating(Market $market)
    {
        if (! $market->status) {
            $market->status = MarketStatus::Enabled->name;
        }
    }

    public function updated(Market $market)
    {
        if ($market->symbol == 'usdt') {
            Cache::forget('usdt_irt_latest_price');
        }
    }
}
