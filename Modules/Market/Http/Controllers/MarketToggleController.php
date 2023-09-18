<?php

namespace Modules\Market\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Market\Actions\ToggleMarket;
use Modules\Market\Entities\Market;

class MarketToggleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:markets']);
    }

    /**
     * @param Request $request
     * @param Market $market
     * @param ToggleMarket $toggleMarket
     * @return JsonResponse
     */
    public function __invoke(Request $request, Market $market, ToggleMarket $toggleMarket): JsonResponse
    {
        $toggleMarket($market);

        return api()->success(null, [
            'item' => Market::find($market->id),
        ]);
    }
}
