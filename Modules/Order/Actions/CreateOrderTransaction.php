<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Events\OrderTransactionCreated;
use Modules\Wallet\Entities\Transaction;

class CreateOrderTransaction
{
    public function __invoke(Order $order, array $data)
    {
        $transaction = $order->transactions()->create([
            'gateway_id' => $data['gateway_id'],
            'quantity'   => $order->cumulative_quote_quantity,
            'user_id'    => $data['user_id'] ?? Auth::id(),
            'status'     => Transaction::STATUS_ADMIN_PENDING,
            'type'       => Transaction::TYPE_WITHDRAW,
            'expires_at' => today()->addDay(),
        ]);

        $order->update([
            'status' => OrderStatus::AdminPending->name
        ]);

        event(new OrderTransactionCreated($transaction));

        return $transaction;
    }
}
