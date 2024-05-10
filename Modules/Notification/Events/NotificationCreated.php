<?php

namespace Modules\Notification\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Notification\Entities\Notification;

class NotificationCreated
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public Notification $notification
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
