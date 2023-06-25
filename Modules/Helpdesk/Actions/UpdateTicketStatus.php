<?php

namespace Modules\Helpdesk\Actions;

use Modules\Helpdesk\Entities\Ticket;
use Modules\Helpdesk\Entities\TicketMessage;

class UpdateTicketStatus
{
    public function __invoke(TicketMessage $ticketMessage)
    {
        $status = $ticketMessage->user->can('tickets') ? Ticket::STATUS_PENDING_USER : Ticket::STATUS_PENDING_ADMIN;

        $ticketMessage->ticket->update([
            'status' => $status,
        ]);
    }
}
