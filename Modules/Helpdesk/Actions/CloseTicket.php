<?php

namespace Modules\Helpdesk\Actions;

use Modules\Helpdesk\Entities\Ticket;

class CloseTicket
{
    public function __invoke(Ticket $ticket)
    {
        $ticket->forceFill([
            'status' => Ticket::STATUS_CLOSED,
        ])->save();

        return $ticket;
    }
}
