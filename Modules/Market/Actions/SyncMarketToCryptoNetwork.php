<?php

namespace Modules\Market\Actions;

use Modules\Market\Entities\Market;

class SyncMarketToCryptoNetwork
{
    public function __invoke(Market $market, array $cryptoNetworkIds)
    {
        $market->cryptoNetworks()->sync($cryptoNetworkIds);
    }
}
