<?php

namespace Modules\Wallet\Traits;

use Modules\Wallet\Entities\Transaction;

trait HasTransaction
{
    public function transactions(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    abstract public function getOwner();
}
