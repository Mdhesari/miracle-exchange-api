<?php

namespace Modules\Helpdesk\Observers;

use Modules\Helpdesk\Actions\UpdateTicketStatus;
use Modules\Helpdesk\Entities\TicketMessage;

class TicketMessageObserver
{
    /**
     * Handle the TicketMessage "created" event.
     *
     * @param TicketMessage $ticketMessage
     * @return void
     */
    public function created(TicketMessage $ticketMessage)
    {
        app(UpdateTicketStatus::class)($ticketMessage);
    }
}
