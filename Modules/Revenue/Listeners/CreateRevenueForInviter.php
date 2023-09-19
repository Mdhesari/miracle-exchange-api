<?php

namespace Modules\Revenue\Listeners;

use Modules\Actions\CreateRevenue;
use Modules\Revenue\Enums\RevenueStatus;
use Modules\Wallet\Entities\Transaction;

class CreateRevenueForInviter
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $transaction = $event->transaction;

        if ($transaction->user?->inviter_id
            && $transaction->isVerified()
            && $this->isFirstTransaction($transaction)
        ) {
            app(CreateRevenue::class)([
                'revenuable_id'   => $transaction->id,
                'revenuable_type' => $transaction::class,
                'description'     => 'Invited user first transaction revenue',
                'user_id'         => $transaction->user->inviter_id,
                'quantity'        => 1000,
                'status'          => RevenueStatus::Pending->name,
            ]);
        }
    }

    private function isFirstTransaction(Transaction $transaction): bool
    {
        return Transaction::where('id', '!=', $transaction->id)->where('status', Transaction::STATUS_VERIFIED)->count() < 1;
    }
}
