<?php

namespace Modules\Helpdesk\Actions;

use Modules\Helpdesk\Entities\TicketMessage;
use Modules\Helpdesk\Events\TicketMessageUpdated;

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
