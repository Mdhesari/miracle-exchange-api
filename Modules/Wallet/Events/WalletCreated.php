<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Entities\Wallet;

class WalletCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Wallet $wallet
    )
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
