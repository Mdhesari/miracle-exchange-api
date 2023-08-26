<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Modules\Order\Actions\ApplyOrderQueryFilters;
use Modules\Order\Actions\CreateOrder;
use Modules\Order\Actions\UpdateOrder;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\OrderRequest;
use Throwable;
use WendellAdriel\ValidatedDTO\Exceptions\CastTargetException;
use WendellAdriel\ValidatedDTO\Exceptions\MissingCastTypeException;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ApplyOrderQueryFilters $applyOrderQueryFilters
     * @return JsonResponse
     * @throws ValidationException
     * @throws CastTargetException
     * @throws MissingCastTypeException
     * @LRDparam s string
     * @LRDparam date_from string
     * @LRDparam date_to string
     * @LRDparam per_page string
     * @LRDparam per_page string
     * @LRDparam user_id integer
     * @LRDparam market_id integer
     * @LRDparam expand string [user, market]
     * @LRDparam status Enum [Done, Pending, FillPending, Rejected]
     */
    public function index(Request $request, ApplyOrderQueryFilters $applyOrderQueryFilters): JsonResponse
    {
        $query = $applyOrderQueryFilters(Order::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @param CreateOrder $createOrder
     * @return JsonResponse
     */
    public function store(OrderRequest $request, CreateOrder $createOrder): JsonResponse
    {
        $order = $createOrder($request->validated());

        return api()->success(null, [
            'item' => Order::find($order->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     * @throws AuthorizationException
     * @QAparam expand string [user, admin, shipping, orderables, coupon]
     */
    public function show(Request $request, Order $order): JsonResponse
    {
        $this->authorize('show', $order);

        $query = Order::query();
        $data = $request->all();

        if (isset($data['expand'])) {
            //TODO: temporary in order to fix scope conflicts
            in_array('transactions', $data['expand']) && $query->with('transactions');;
        }

        return api()->success(null, [
            'item' => $query->find($order->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OrderRequest $request
     * @param Order $order
     * @param UpdateOrder $updateOrder
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function update(OrderRequest $request, Order $order, UpdateOrder $updateOrder): JsonResponse
    {
        $this->authorize('update', $order);

        $order = $updateOrder($order, $request->validated());

        return api()->success(null, [
            'item' => Order::find($order->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('delete', $order);

        $order->delete();

        return api()->success();
    }
}
