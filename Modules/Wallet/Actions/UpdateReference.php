<?php

namespace Modules\Wallet\Actions;

use App\Models\User;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Events\TransactionReferenceUpdated;

class UpdateReference
{
    public function __invoke(Transaction $transaction, array $data)
    {
        $admin_id = User::whereHas('roles', function ($query) {
            $query->whereHas('permissions', function ($query) {
                $query->where('name', 'transactions');
            });
        })->first(['id'])->id;

        $transaction->update([
            'reference' => $data['reference'],
            'status'    => Transaction::STATUS_ADMIN_PENDING,
            'admin_id'  => $admin_id,
        ]);

        if (isset($data['media'])) {
            array_map(fn($file) => $transaction->addReferenceMedia($file), $data['media']);
        }

        event(new TransactionReferenceUpdated($transaction));

        return $transaction;
    }
}
