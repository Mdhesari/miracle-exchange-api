<?php

namespace Modules\Wallet\Actions;

use Illuminate\Validation\ValidationException;
use Modules\Wallet\Entities\Transaction;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Events\TransactionWithdrawCreated;
use Modules\Wallet\Events\WalletTransaction;
use Modules\Wallet\Wallet as WalletModule;

class CreateWithdrawTransaction
{
    /**
     * @throws ValidationException
     */
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

        /** @var Transaction $transaction */
        $transaction = $wallet->withdraw($data);
        if ($transaction->isVerified()) {
            if ($data['quantity'] > $wallet->quantity) {
                throw ValidationException::withMessages([
                    'quantity' => __('wallet::transaction.insufficientBalance'),
                ]);
            }
            $wallet->dischargeWallet($transaction->quantity);

            event(new WalletTransaction($transaction));
        }

        event(new TransactionWithdrawCreated($transaction));

        return $transaction;
    }
}
