<?php

namespace Modules\Order\Listeners;

use Illuminate\Support\Str;
use Modules\Auth\Jobs\SendSMS;
use Modules\Order\Exceptions\InvalidTransactionException;
use Modules\Wallet\Events\TransactionReferenceUpdated;

class SendTransactionNotifications
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
     * @throws InvalidTransactionException
     */
    public function handle(TransactionReferenceUpdated $event)
    {
        $transaction = $event->transaction;
        $order = $transaction->transactionable;

        if (is_null($order)) {
            throw new InvalidTransactionException;
        }

        SendSMS::dispatch($order->user->mobile, $transaction->admin_id ? 'submitAdminReceipt' : 'submitUserReceipt', [
            // currency
            Str::replace(' ', '_', $order->market->persian_name),
        ]);
    }
}
