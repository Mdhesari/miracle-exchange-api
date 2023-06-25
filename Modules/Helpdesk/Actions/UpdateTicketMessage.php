<?php

namespace Modules\Helpdesk\Actions;

use App\Events\Ticket\TicketMessageUpdated;
use Modules\Helpdesk\Entities\TicketMessage;

class UpdateTicketMessage
{
    public function __construct(
        private AddTicketMessageMedia $addTicketMessageMedia
    )
    {
        //
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(TicketMessage $ticketMessage, array $data): TicketMessage
    {
        $ticketMessage->updateOrFail($data);

        ($this->addTicketMessageMedia)($ticketMessage, $data);

        event(new TicketMessageUpdated($ticketMessage->refresh()));

        return $ticketMessage;
    }
}
