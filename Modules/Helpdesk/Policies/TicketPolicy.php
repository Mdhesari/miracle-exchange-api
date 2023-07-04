<?php

namespace Modules\Helpdesk\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Helpdesk\Entities\Ticket;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function message(User $user, Ticket $ticket): bool
    {
        return $user->isOwner($ticket->user_id) || $user->can('tickets');
    }

    public function close(User $user, Ticket $ticket): bool
    {
        return $user->isOwner($ticket->user_id) || $user->can('tickets');
    }
}
