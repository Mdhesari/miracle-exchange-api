<?php

namespace Modules\Order\Listeners;

use App\Models\User;
use Illuminate\Support\Str;
use Modules\Auth\Jobs\SendSMS;
use Modules\Order\Entities\Order;
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
    public function handle($event)
    {
        $transaction = $event->transaction;
        $order = $transaction->transactionable;

        if (is_null($order) && ! $order instanceof Order) {

            return;
        }

        if ($order->user?->mobile) {
            SendSMS::dispatch($order->user->mobile, $event instanceof TransactionReferenceUpdated ? 'submitUserReceipt' : 'submitAdminReceipt', [
                // currency
                Str::replace(' ', '_', $order->market->persian_name),
            ]);
        }

        foreach (User::permission('orders')->whereNotNull('mobile')->cursor() as $user) {
            SendSMS::dispatch($user->mobile, 'submitUserReceiptAdminNotifyM', [
                // number
                $order->id,
            ]);
        }
    }
}
