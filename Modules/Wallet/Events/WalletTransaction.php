<?php

namespace Modules\Wallet\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Entities\Transaction;

class WalletTransaction
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Transaction $transaction
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
