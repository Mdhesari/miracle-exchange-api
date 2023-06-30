<?php

namespace Modules\Order\Observers;

use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatus;
use Modules\Wallet\Entities\Transaction;

class TransactionObserver
{
    public function updated(Transaction $transaction)
    {
        $oldStatus = $transaction->getOriginal('status');

        if ( $oldStatus !== $transaction->status && $transaction->isAdminPending() && $transaction->transactionable instanceof Order ) {
            $transaction->transactionable->update([
                'status' => OrderStatus::AdminPending->name,
            ]);
        }
    }
}
