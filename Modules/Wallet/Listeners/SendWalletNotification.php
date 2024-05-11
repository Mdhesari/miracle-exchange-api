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
        $event->wallet->user->notify(new WalletNotification($event->wallet));
    }
}
