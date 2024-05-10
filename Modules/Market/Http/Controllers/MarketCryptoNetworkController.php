<?php

namespace Modules\Market\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Market\Actions\AddMarketToCryptoNetwork;
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
     * @param AddMarketToCryptoNetwork $addMarketToCryptoNetwork
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(MarketCryptoNetworkRequest $request, Market $market, AddMarketToCryptoNetwork $addMarketToCryptoNetwork): \Illuminate\Http\JsonResponse
    {
        $addMarketToCryptoNetwork($market, $request->crypto_network_id);

        return api()->success();
    }
}
