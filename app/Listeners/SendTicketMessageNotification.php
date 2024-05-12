<?php

namespace App\Listeners;

use App\Notifications\TicketMessageNotification;
use Modules\Helpdesk\Events\TicketMessageCreated;

class SendTicketMessageNotification
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
    public function handle(TicketMessageCreated $event): void
    {
        $tm = $event->ticketMessage;
        if ($tm->user->can('tickets')) {
            $tm->ticket->user->notify(new TicketMessageNotification($tm));
        }

    }
}
