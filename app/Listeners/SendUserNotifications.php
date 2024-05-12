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
            // Todo: user in persian should be translated...
            SendSMS::dispatch($event->user->mobile, 'verifyUserM', [$event->user->first_name ?: 'کاربر']);
    }
}
