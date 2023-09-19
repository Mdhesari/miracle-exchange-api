<?php

namespace Modules\Revenue\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Revenue\Entities\Revenue;

class RevenueCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Revenue $revenue
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
