<?php

namespace Modules\Wallet\Actions;

use Illuminate\Validation\ValidationException;
use Modules\Wallet\Wallet as WalletModule;
use Modules\Wallet\Events\TransactionWithdrawCreated;

class CreateWithdrawTransaction
{
    /**
     * @throws ValidationException
     */
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

        if ( $data['quantity'] > $wallet->quantity ) {
            throw ValidationException::withMessages([
                'quantity' => __('responses.insufficientBalance'),
            ]);
        }

        $transaction = $wallet->withdraw($data);

        event(new TransactionWithdrawCreated($transaction));

        return $transaction;
    }
}
