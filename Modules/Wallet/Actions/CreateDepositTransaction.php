<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Wallet as WalletModule;
use Modules\Wallet\Events\TransactionDepositCreated;

class CreateDepositTransaction
{
    public function __invoke(array $data)
    {
        $user = WalletModule::user()->findOrFail($data['user_id']);

        $wallet = $user->wallets()->first();

        if ( ! $wallet ) {
            $wallet = app(SetupWallet::class)([
                'user_id' => $user->id,
            ]);
        }

        $wallet->refresh();

        $transaction = $wallet->deposit($data);

        event(new TransactionDepositCreated($transaction));

        return $transaction;
    }
}
