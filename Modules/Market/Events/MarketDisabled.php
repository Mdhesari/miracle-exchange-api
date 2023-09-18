<?php

namespace Modules\Market\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Market\Entities\Market;

class MarketDisabled
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Market $market
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
