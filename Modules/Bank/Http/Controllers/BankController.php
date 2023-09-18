<?php

namespace Modules\Bank\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Mdhesari\LaravelQueryFilters\Actions\ApplyQueryFilters;
use Modules\Bank\Actions\CreateBank;
use Modules\Bank\Actions\DeleteBank;
use Modules\Bank\Actions\UpdateBank;
use Modules\Bank\Entities\Bank;
use Modules\Bank\Http\Requests\BankRequest;
use Throwable;
use WendellAdriel\ValidatedDTO\Exceptions\CastTargetException;
use WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            //
        ]);
    }

    /**
     * @param Request $request
     * @param ApplyQueryFilters $applyQueryFilters
     * @return JsonResponse
     * @throws ValidationException
     * @throws CastTargetException
     * @throws MissingCastTypeException
     * @LRDparam s string
     * @LRDparam oldest boolean
     * @LRDparam per_page integer
     * @LRDparam user_id integer
     * @LRDparam date_from integer
     * @LRDparam date_to integer
     */
    public function index(Request $request, ApplyQueryFilters $applyQueryFilters): JsonResponse
    {
        $query = $applyQueryFilters(Bank::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * @param BankRequest $request
     * @param CreateBank $createBank
     * @return JsonResponse
     */
    public function store(BankRequest $request, CreateBank $createBank): JsonResponse
    {
        $model = $createBank($request->validated());

        return api()->success(null, [
            'item' => Bank::find($model->id),
        ]);
    }

    /**
     * @param BankRequest $request
     * @param Bank $model
     * @param UpdateBank $updateBank
     * @return JsonResponse
     */
    public function update(BankRequest $request, Bank $model, UpdateBank $updateBank): JsonResponse
    {
        $updateBank($model, $request->validated());

        return api()->success(null, [
            'item' => Bank::find($model->id),
        ]);
    }

    /**
     * @param Bank $model
     * @return JsonResponse
     */
    public function show(Bank $model): JsonResponse
    {
        return api()->success(null, [
            'item' => Bank::find($model->id),
        ]);
    }

    /**
     * @param Bank $model
     * @param DeleteBank $deleteBank
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Bank $model, DeleteBank $deleteBank): JsonResponse
    {
        $deleteBank($model);

        return api()->success();
    }
}
