<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Entities\Wallet;

class WalletWithdraw
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Wallet $wallet
    )
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public
    function broadcastOn(): array
    {
        return [];
    }
}
