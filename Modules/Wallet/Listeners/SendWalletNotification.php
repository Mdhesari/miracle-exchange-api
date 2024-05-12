<?php

namespace Modules\Wallet\Listeners;

use Modules\Wallet\Events\WalletTransaction;
use Modules\Wallet\Notifications\WalletNotification;

class SendWalletNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WalletTransaction $event): void
    {
        $tx = $event->transaction;
        $tx->user->notify(new WalletNotification($tx));
    }
}
