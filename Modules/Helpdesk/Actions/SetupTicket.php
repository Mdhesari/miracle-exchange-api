<?php

namespace Modules\Helpdesk\Actions;

use Modules\Helpdesk\Entities\Ticket;
use Illuminate\Support\Facades\Auth;
use Modules\Helpdesk\Events\TicketCreated;

class SetupTicket
{
    public function __invoke(array $data)
    {
        $ticket = Ticket::create(array_replace($data, [
            'user_id' => Auth::id(),
            'status'  => Auth::user()->can('tickets') & isset($data['status']) ? $data['status'] : Ticket::STATUS_PENDING_ADMIN,
        ]));

        event(new TicketCreated($ticket));

        return $ticket;
    }
}
