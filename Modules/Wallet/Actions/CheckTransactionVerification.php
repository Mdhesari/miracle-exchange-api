<?php

namespace Modules\Wallet\Actions;

use Modules\Wallet\Events\WalletTransaction;
use Modules\Wallet\Exceptions\InsufficientBalanceException;

class CheckTransactionVerification
{
    /**
     * @throws InsufficientBalanceException
     */
    public function __invoke($transaction)
    {
        if ($transaction->isDeposit()) {
            // The transaction is deposited

            $oldStatus = $transaction->getOriginal('status');

            if ($oldStatus !== $transaction->status && $transaction->isVerified()) {
                $wallet = $transaction->wallet;

                $wallet->chargeWallet($transaction->quantity);

                event(new WalletTransaction($transaction));
            }

        } else {
            // The transaction is withdrawn

            if (! $transaction->isRejected()) {
                // The transaction is not rejected

                if ($transaction->wasRecentlyCreated && ! $transaction->hasGateway()) {
                    /*
                     * Check wallet has enough balance
                     */
                    if ($transaction->wallet->quantity < $transaction->quantity) {
                        throw new InsufficientBalanceException($transaction, 'required quantity : '.$transaction->quantity);
                    }

                    $transaction->wallet->dischargeWallet($transaction->quantity);
                }

            } else {
                // The transaction is rejected

                /*
                 * Newly created transactions with rejected status never discharge wallet
                 */
                if (! $transaction->wasRecentlyCreated && ! $transaction->hasGateway()) {
                    $transaction->wallet->chargeWallet($transaction->quantity);
                }
            }

        }
    }
}
