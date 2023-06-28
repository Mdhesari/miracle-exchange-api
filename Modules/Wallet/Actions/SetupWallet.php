<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Events\WalletCreated;

class SetupWallet
{
    public function __invoke(array $data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|Wallet|\Illuminate\Database\Query\Builder|null
    {
        if (!isset($data['quantity'])) {
            $data['quantity'] = 0;
        }

        $wallet = Wallet::withTrashed()->firstOrCreate([
            'user_id' => $data['user_id'],
        ], $data);

        if ($wallet->trashed()) {
            $wallet->update($data);
            $wallet->restore();
        }

        event(new WalletCreated($wallet));

        return $wallet->fresh();
    }
}
