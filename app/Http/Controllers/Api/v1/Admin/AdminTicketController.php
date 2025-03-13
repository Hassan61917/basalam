<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\UserTicketMessageRequest;
use App\Http\Requests\v1\User\UserTicketRequest;
use App\Http\Resources\v1\TicketMessageResource;
use App\Http\Resources\v1\TicketResource;
use App\Models\Ticket;
use App\ModelServices\Support\TicketService;
use Illuminate\Http\JsonResponse;

class AdminTicketController extends Controller
{
    protected string $resource = TicketResource::class;

    public function __construct(
        public TicketService $ticketService
    )
    {
    }

    public function index(): JsonResponse
    {
        $tickets = $this->ticketService->getTickets(["category"]);
        return $this->ok($this->paginate($tickets));
    }

    public function answer(UserTicketMessageRequest $request, Ticket $ticket): JsonResponse
    {
        $data = $request->validated();
        $message = $this->ticketService->answer($this->authUser(), $ticket, $data["body"]);
        return $this->ok($message, TicketMessageResource::make($message));
    }

    public function close(Ticket $ticket): JsonResponse
    {
        $this->ticketService->close($ticket,);
        return $this->ok($ticket);
    }

    public function show(Ticket $ticket): JsonResponse
    {
        $ticket->load("category", "messages");
        $this->ticketService->seenMessage($this->authUser(), $ticket);
        return $this->ok($ticket);
    }

    public function update(UserTicketRequest $request, Ticket $ticket): JsonResponse
    {
        $data = $request->validated();
        $ticket->update($data);
        return $this->ok($ticket);
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();
        return $this->deleted();
    }
}
