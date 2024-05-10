<?php

namespace Modules\Market\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Market\Actions\SyncMarketToCryptoNetwork;
use Modules\Market\Entities\Market;
use Modules\Market\Http\Requests\MarketCryptoNetworkRequest;

class MarketCryptoNetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:crypto']);
    }

    /**
     * @param MarketCryptoNetworkRequest $request
     * @param Market $market
     * @param SyncMarketToCryptoNetwork $syncMarketToCryptoNetwork
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(MarketCryptoNetworkRequest $request, Market $market, SyncMarketToCryptoNetwork $syncMarketToCryptoNetwork): \Illuminate\Http\JsonResponse
    {
        $syncMarketToCryptoNetwork($market, $request->crypto_network_ids);

        return api()->success();
    }
}
