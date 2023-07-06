<?php

namespace App\Observers\Transaction;

use Modules\Wallet\Entities\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        if ($transaction->type == Transaction::TYPE_WITHDRAW)
            cache()->forget('transactions::received');
        else
            cache()->forget('transactions::transferred');
    }
}
