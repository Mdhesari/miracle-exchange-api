<?php

namespace Modules\Helpdesk\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Mdhesari\LaravelQueryFilters\Actions\ApplyQueryFilters;
use Modules\Helpdesk\Actions\ApplyTicketQueryFilters;
use Modules\Helpdesk\Actions\CloseTicket;
use Modules\Helpdesk\Actions\SetupTicket;
use Modules\Helpdesk\Actions\UpdateTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Helpdesk\Entities\Ticket;
use Modules\Helpdesk\Http\Requests\TicketRequest;
use Throwable;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ApplyTicketQueryFilters $applyTicketQueryFilters
     * @LRDparam s string
     * @LRDparam status string
     * @LRDparam oldest boolean
     * @LRDparam user_id integer
     * @LRDparam date_from integer
     * @LRDparam date_to integer
     * @return JsonResponse
     */
    public function index(Request $request, ApplyTicketQueryFilters $applyTicketQueryFilters): JsonResponse
    {
        $query = $applyTicketQueryFilters(Ticket::query(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TicketRequest $request
     * @param SetupTicket $setupTicket
     * @return JsonResponse
     */
    public function store(TicketRequest $request, SetupTicket $setupTicket): JsonResponse
    {
        $ticket = $setupTicket($request->validated());

        return api()->success(null, [
            'item' => Ticket::find($ticket->id),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Ticket $ticket
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(Ticket $ticket): JsonResponse
    {
        $this->authorize('message', $ticket);

        return api()->success(null, [
            'item' => Ticket::find($ticket->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TicketRequest $request
     * @param Ticket $ticket
     * @param UpdateTicket $updateTicket
     * @return JsonResponse
     * @throws Throwable
     */
    public function update(TicketRequest $request, Ticket $ticket, UpdateTicket $updateTicket): JsonResponse
    {
        $ticket = $updateTicket($ticket, $request->validated());

        return api()->success(null, [
            'item' => Ticket::find($ticket->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Ticket $ticket
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        $this->authorize('message', $ticket);

        $ticket->deleteOrFail();

        return api()->success();
    }

    /**
     * @param Request $request
     * @param ApplyQueryFilters $applyQueryFilters
     * @return JsonResponse
     */
    public function getMyTickets(Request $request, ApplyQueryFilters $applyQueryFilters): JsonResponse
    {
        $query = $applyQueryFilters($request->user()->tickets(), $request->all());

        return api()->success(null, [
            'items' => $query->paginate(),
        ]);
    }

    /**
     * @param Ticket $ticket
     * @param CloseTicket $closeTicket
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function closeTicket(Ticket $ticket, CloseTicket $closeTicket): JsonResponse
    {
        $this->authorize('close', $ticket);

        $closeTicket($ticket);

        return api()->success();
    }
}
