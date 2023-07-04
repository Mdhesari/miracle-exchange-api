<?php

namespace Modules\Helpdesk\Actions;

use Modules\Helpdesk\Entities\Ticket;
use Modules\Helpdesk\Events\TicketUpdated;

class UpdateTicket
{
    /**
     * @throws \Throwable
     */
    public function __invoke(Ticket $ticket, array $data): Ticket
    {
        $ticket->updateOrFail($data);

        event(new TicketUpdated($ticket->refresh()));

        return $ticket;
    }
}
