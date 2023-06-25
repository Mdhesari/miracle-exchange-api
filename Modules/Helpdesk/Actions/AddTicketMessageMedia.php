<?php

namespace Modules\Helpdesk\Actions;

use Modules\Helpdesk\Entities\TicketMessage;

class AddTicketMessageMedia
{
    public function __invoke(TicketMessage $ticketMessage, array $data)
    {
        if ( isset($data['attachments']) ) {
            array_map(fn($attachment) => $ticketMessage->addAttachment($attachment), $data['attachments']);
        }
    }
}
