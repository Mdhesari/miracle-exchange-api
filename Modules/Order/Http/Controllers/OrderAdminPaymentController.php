<?php

namespace Modules\Order\Http\Controllers;

use Modules\Order\Actions\CreateAdminOrderTransaction;
use Modules\Order\Entities\Order;
use Modules\Order\Http\Requests\OrderAdminPaymentRequest;
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
        $transaction = $createAdminOrderTransaction($order, [$request->validated(), ...['type' => Transaction::TYPE_WITHDRAW]]);

        return api()->success(null, [
            'item' => Transaction::with('transactionable')->find($transaction->id)
        ]);
    }
}
