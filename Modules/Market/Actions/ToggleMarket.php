<?php

namespace Modules\Market\Actions;

use Modules\Market\Entities\Market;
use Modules\Market\Events\MarketDisabled;
use Modules\Market\Events\MarketEnabled;

class ToggleMarket
{
    public function __invoke(Market $market)
    {
        $market->toggle()->refresh();

        event($market->isEnabled() ? new MarketEnabled($market) : new MarketDisabled($market));
    }
}
