<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Order\Actions\CreateAdminOrderTransaction;
use Modules\Order\Actions\CreateOrderTransaction;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\OrderAdminPaymentRequest;
use Modules\Order\Http\Requests\OrderPaymentRequest;
use Modules\Wallet\Entities\Transaction;

class OrderAdminPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'can:orders']);
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderAdminPaymentRequest $request, Order $order, CreateAdminOrderTransaction $createAdminOrderTransaction): \Illuminate\Http\JsonResponse
    {
        $transaction = $createAdminOrderTransaction($order, $request->validated());

        return api()->success(null, [
            'item' => Transaction::with('transactionable')->find($transaction->id)
        ]);
    }
}
