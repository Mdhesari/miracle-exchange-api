<?php

namespace Modules\Helpdesk\Observers;

use Modules\Helpdesk\Entities\Ticket;

class TicketObserver
{
    /**
     * Handle the Ticket "creating" event.
     *
     * @param Ticket $ticket
     * @return void
     */
    public function creating(Ticket $ticket)
    {
        $ticket->number = $this->getUniqueTicketNumber();
    }

    private function getUniqueTicketNumber(): string
    {
        $number = \Str::random(6);

        return Ticket::whereNumber($number)->exists() ? $this->getUniqueTicketNumber() : $number;
    }

}
