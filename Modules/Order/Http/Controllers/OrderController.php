<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Order\Actions\ApplyOrderQueryFilters;
use Modules\Order\Actions\CreateOrder;
use Modules\Order\Actions\UpdateOrder;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\OrderRequest;

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
     * @QAparam s string
     * @QAparam user_id integer
     * @QAparam oldest boolean
     * @QAparam date_from integer
     * @QAparam date_to integer
     * @return JsonResponse
     */
    public function index(Request $request, ApplyOrderQueryFilters $applyOrderQueryFilters): JsonResponse
    {
        $query = $applyOrderQueryFilters(\Modules\Order\Entities\Order::query(), $request->all());

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
     * @param Order $order
     * @QAparam expand string [user, admin, shipping, orderables, coupon]
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Order $order): JsonResponse
    {
        $this->authorize('show', $order);

        return api()->success(null, [
            'item' => Order::find($order->id),
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
     * @throws \Throwable
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
