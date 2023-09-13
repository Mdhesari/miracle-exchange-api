<?php

namespace Modules\Order\Http\Controllers;

use Modules\Order\Actions\CreateOrderTransaction;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\OrderPaymentRequest;
use Modules\Wallet\Entities\Transaction;

class OrderPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderPaymentRequest $request, Order $order, CreateOrderTransaction $createOrderTransaction): \Illuminate\Http\JsonResponse
    {
        $transaction = $createOrderTransaction($order, $request->validated());

        return api()->success(null, [
            'item' => Transaction::with('transactionable')->find($transaction->id)
        ]);
    }
}
