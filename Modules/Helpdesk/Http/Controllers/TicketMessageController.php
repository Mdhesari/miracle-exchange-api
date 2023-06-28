<?php

namespace Modules\Helpdesk\Http\Controllers;

use App\Jobs\Ticket\SetMessagesAsRead;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Mdhesari\LaravelQueryFilters\Actions\ApplyQueryFilters;
use Modules\Helpdesk\Actions\CreateTicketMessage;
use Modules\Helpdesk\Actions\UpdateTicketMessage;
use App\Http\Controllers\Controller;
use Modules\Helpdesk\Entities\Ticket;
use Modules\Helpdesk\Entities\TicketMessage;
use Illuminate\Http\Request;
use Modules\Helpdesk\Http\Requests\TicketMessageRequest;
use Throwable;

class TicketMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware([
            'auth:api'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Ticket $ticket
     * @param ApplyQueryFilters $applyQueryFilters
     * @QAparam s string
     * @QAparam status string
     * @QAparam oldest boolean
     * @QAparam user_id integer
     * @QAparam date_from integer
     * @QAparam date_to integer
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(Request $request, Ticket $ticket, ApplyQueryFilters $applyQueryFilters): JsonResponse
    {
        $this->authorize('message', $ticket);

        $query = $applyQueryFilters($ticket->messages(), ['oldest' => true, ...$request->all()]);

        dispatch(new SetMessagesAsRead($query->pluck('id')->toArray(), $request->user()->id));

        return api()->success(null, [
            'ticket' => $ticket,
            'items'  => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TicketMessageRequest $request
     * @param Ticket $ticket
     * @param CreateTicketMessage $createTicketMessage
     * @return JsonResponse
     */
    public function store(TicketMessageRequest $request, Ticket $ticket, CreateTicketMessage $createTicketMessage): JsonResponse
    {
        $ticketMessage = $createTicketMessage($ticket, $request->validated());

        return api()->success(null, [
            'ticket' => $ticket,
            'item'   => TicketMessage::find($ticketMessage->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Ticket $ticket
     * @param TicketMessage $ticketMessage
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Ticket $ticket, TicketMessage $ticketMessage): JsonResponse
    {
        $this->authorize('message', $ticket);

        return api()->success(null, [
            'ticket' => $ticket,
            'item'   => TicketMessage::find($ticketMessage->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TicketMessageRequest $request
     * @param Ticket $ticket
     * @param TicketMessage $ticketMessage
     * @param UpdateTicketMessage $updateTicketMessage
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(TicketMessageRequest $request, Ticket $ticket, TicketMessage $ticketMessage, UpdateTicketMessage $updateTicketMessage): JsonResponse
    {
        $ticketMessage = $updateTicketMessage($ticketMessage, $request->validated());

        return api()->success(null, [
            'ticket' => $ticket,
            'item'   => TicketMessage::find($ticketMessage->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ticket $ticket
     * @param TicketMessage $ticketMessage
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(Ticket $ticket, TicketMessage $ticketMessage): JsonResponse
    {
        $this->authorize('message', $ticket);

        $ticketMessage->deleteOrFail();

        return api()->success();
    }
}
