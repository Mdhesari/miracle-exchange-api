<?php

namespace Modules\Wallet\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Wallet\Actions\ApplyCryptoNetworkQueryFilters;
use Modules\Wallet\Actions\CreateCryptoNetwork;
use Modules\Wallet\Actions\DeleteCryptoNetwork;
use Modules\Wallet\Actions\UpdateCryptoNetwork;
use Modules\Wallet\Entities\CryptoNetwork;
use Modules\Wallet\Http\Requests\CryptoNetworkRequest;
use WendellAdriel\ValidatedDTO\Exceptions\CastTargetException;
use WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException;

class CryptoNetworkController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:crypto'])->except(['index', 'show']);
    }

    /**
     * @param Request $request
     * @param ApplyCryptoNetworkQueryFilters $applyQueryFilters
     * @return JsonResponse
     * @throws ValidationException
     * @throws CastTargetException
     * @throws MissingCastTypeException
     */
    public function index(Request $request, ApplyCryptoNetworkQueryFilters $applyQueryFilters): JsonResponse
    {
        $query = $applyQueryFilters(CryptoNetwork::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CryptoNetworkRequest $request, CreateCryptoNetwork $createCryptoNetwork): JsonResponse
    {
        $model = $createCryptoNetwork($request);

        return api()->success(null, [
            'item' => $model,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CryptoNetwork $cryptoNetwork): JsonResponse
    {
        return api()->success(null, [
            'item' => $cryptoNetwork,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CryptoNetworkRequest $request, CryptoNetwork $cryptoNetwork, UpdateCryptoNetwork $updateCryptoNetwork): JsonResponse
    {
        $updateCryptoNetwork($cryptoNetwork, $request);

        return api()->success();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CryptoNetwork $cryptoNetwork, DeleteCryptoNetwork $deleteCryptoNetwork): JsonResponse
    {
        $deleteCryptoNetwork($cryptoNetwork);

        return api()->success();
    }
}
