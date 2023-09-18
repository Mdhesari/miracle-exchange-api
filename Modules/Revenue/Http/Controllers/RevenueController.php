<?php

namespace Modules\Revenue\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Mdhesari\LaravelQueryFilters\Actions\ApplyQueryFilters;
use Modules\Revenue\Entities\Revenue;
use WendellAdriel\ValidatedDTO\Exceptions\CastTargetException;
use WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException;

class RevenueController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth:api', 'can:revenues'
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
        $query = $applyQueryFilters(Revenue::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * @param Revenue $model
     * @return JsonResponse
     */
    public function show(Revenue $model): JsonResponse
    {
        return api()->success(null, [
            'item' => Revenue::find($model->id),
        ]);
    }
}
