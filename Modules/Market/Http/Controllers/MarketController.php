<?php

namespace Modules\Market\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Market\Actions\ApplyMarketQueryFilters;
use Modules\Market\Actions\CreateMarket;
use Modules\Market\Actions\UpdateMarket;
use Modules\Market\Entities\Market;
use Modules\Market\Http\Requests\MarketRequest;

class MarketController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:markets'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ApplyMarketQueryFilters $applyMarketQueryFilters): JsonResponse
    {
        $query = $applyMarketQueryFilters(Market::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarketRequest $request, CreateMarket $createMarket): JsonResponse
    {
        $market = $createMarket($request->validated());

        return api()->success(null, [
            'item' => Market::find($market->id),
        ]);
    }

    /**
     * @param Market $market
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Market $market): JsonResponse
    {
        $this->authorize('show', $market);

        return api()->success(null, [
            'item' => Market::find($market->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarketRequest $request, Market $market, UpdateMarket $updateMarket): JsonResponse
    {
        $market = $updateMarket($market, $request->validated());

        return api()->success(null, [
            'item' => Market::find($market->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Market $market): JsonResponse
    {
        $market->delete();

        return api()->success();
    }
}
