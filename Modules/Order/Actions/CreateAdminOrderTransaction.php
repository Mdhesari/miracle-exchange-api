<?php

namespace Modules\Order\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Order\Events\AdminOrderTransactionCreated;
use Modules\Wallet\Entities\Transaction;

class CreateAdminOrderTransaction
{
    /**
     * @param Order $order
     * @param array $data
     * @return Model
     */
    public function __invoke(Order $order, array $data)
    {
        if (! isset($data['type'])) {
            $data['type'] = Transaction::TYPE_DEPOSIT;
        }

        $transaction = $order->transactions()->create([
            'reference' => $data['reference'],
            'quantity'  => $order->cumulative_quote_quantity,
            'status'    => Transaction::STATUS_VERIFIED,
            'user_id'   => $order->user_id,
            'type'      => $data['type'],
            'meta'      => [
                'admin_id' => Auth::id(),
            ]
        ]);

        if (isset($data['media'])) {
            array_map(fn($file) => $transaction->addReferenceMedia($file), $data['media']);
        }

        $order->update([
            'status' => OrderStatus::Done->name,
        ]);

        event(new AdminOrderTransactionCreated($transaction));

        return $transaction;
    }
}
