<?php

namespace App\Listeners;

use Modules\Auth\Jobs\SendSMS;

class SendUserNotifications
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
    public function handle(object $event): void
    {
        if ($event->user->mobile)
            SendSMS::dispatch($event->user->mobile, 'verifyUser', [$event->user->full_name]);
    }
}
