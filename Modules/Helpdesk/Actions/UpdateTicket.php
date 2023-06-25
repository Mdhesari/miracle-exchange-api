<?php

namespace Modules\Helpdesk\Actions;

use App\Events\Ticket\TicketUpdated;
use Modules\Helpdesk\Entities\Ticket;

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
