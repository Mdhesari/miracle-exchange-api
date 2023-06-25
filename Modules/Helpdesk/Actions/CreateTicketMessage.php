<?php

namespace Modules\Helpdesk\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Helpdesk\Entities\Ticket;
use Modules\Helpdesk\Events\TicketMessageCreated;

class CreateTicketMessage
{
    public function __construct(
        private AddTicketMessageMedia $addTicketMessageMedia
    )
    {
        //
    }

    /**
     * @param Ticket $ticket
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function __invoke(Ticket $ticket, array $data)
    {
        $ticketMessage = $ticket->messages()->create(array_replace($data, [
            'user_id' => Auth::id(),
        ]));

        ($this->addTicketMessageMedia)($ticketMessage, $data);

        event(new TicketMessageCreated($ticketMessage));

        return $ticketMessage;
    }
}
