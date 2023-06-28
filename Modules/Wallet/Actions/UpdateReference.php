<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Entities\Transaction;

class UpdateReference
{
    public function __invoke(Transaction $transaction, array $data)
    {
        $transaction->update([
            'reference' => $data['reference'],
            'status'    => Transaction::STATUS_ADMIN_PENDING,
        ]);

        if ( isset($data['media']) ) {
            array_map(fn($file) => $transaction->addReferenceMedia($file), $data['media']);
        }

        return $transaction;
    }
}
