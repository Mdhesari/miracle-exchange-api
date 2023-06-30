<?php

namespace Modules\Gateway\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Gateway\Actions\ApplyGatewayQueryFilters;
use Modules\Gateway\Actions\CreateGateway;
use Modules\Gateway\Actions\UpdateGateway;
use Modules\Gateway\Entities\Gateway;
use Modules\Gateway\Http\Requests\GatewayRequest;

class GatewayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');

        $this->middleware('can:gateways')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     * @LRDparam s string
     * @LRDparam date_from string
     * @LRDparam date_to string
     * @LRDparam per_page string
     * @LRDparam per_page string
     * @LRDparam is_active boolean [only for admins]
     */
    public function index(Request $request, ApplyGatewayQueryFilters $applyGatewayQueryFilters): \Illuminate\Http\JsonResponse
    {
        $query = $applyGatewayQueryFilters(Gateway::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GatewayRequest $request, CreateGateway $createGateway): \Illuminate\Http\JsonResponse
    {
        $gateway = $createGateway($request->validated());

        return api()->success(null, [
            'item' => Gateway::find($gateway->id),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gateway $gateway): \Illuminate\Http\JsonResponse
    {
        return api()->success(null, [
            'item' => Gateway::find($gateway->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GatewayRequest $request, Gateway $gateway, UpdateGateway $updateGateway): \Illuminate\Http\JsonResponse
    {
        $gateway = $updateGateway($gateway, $request->validated());

        return api()->success(null, [
            'item' => Gateway::find($gateway->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gateway $gateway): \Illuminate\Http\JsonResponse
    {
        $gateway->delete();

        return api()->success();
    }
}
