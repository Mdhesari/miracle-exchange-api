<?php

namespace Modules\Market\Actions;

use Modules\Market\Entities\Market;

class AddMarketToCryptoNetwork
{
    public function __invoke(Market $market, $cryptoNetworkId)
    {
        $market->cryptoNetworks()->toggle($cryptoNetworkId);
    }
}
