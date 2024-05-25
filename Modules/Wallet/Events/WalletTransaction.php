<?php

namespace Modules\Wallet\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Wallet\Entities\Transaction;

class WalletTransaction implements ShouldQueue
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
