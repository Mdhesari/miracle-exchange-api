<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Events\TransactionDepositCreated;
use Modules\Wallet\Wallet as WalletModule;

class CreateDepositTransaction
{
    public function __invoke(array $data)
    {
        $user = WalletModule::user()->findOrFail($data['user_id']);

        /** @var Wallet $wallet */
        $wallet = $user->wallets()->first();

        if (! $wallet) {
            $wallet = app(SetupWallet::class)([
                'user_id' => $user->id,
            ]);
        }

        $wallet->refresh();

        $transaction = $wallet->deposit($data);

        if ($transaction->isVerified()) {
            $wallet->chargeWallet($transaction->quantity);
        }

        event(new TransactionDepositCreated($transaction));

        return $transaction;
    }
}
