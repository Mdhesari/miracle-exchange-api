<?php

namespace Modules\Wallet\Listeners;

use Modules\Wallet\Events\WalletWithdraw;
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
    public function handle(WalletWithdraw $event): void
    {
        $tx = $event->transaction;
        $tx->user->notify(new WalletNotification($tx));
    }
}
