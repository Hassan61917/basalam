<?php

namespace App\ModelServices\Support;

use App\Enums\TicketStatus;
use App\Events\TicketWasCreated;
use App\Exceptions\ModelException;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketService
{
    public function make(User $user, array $data): Ticket
    {
        $ticket = $user->tickets()->create($data);
        TicketWasCreated::dispatch($ticket);
        return $ticket;
    }

    public function addMessage(Ticket $ticket, string $body): TicketMessage
    {
        if (!$this->canSendMessage($ticket)) {
            throw new ModelException("You can't send message");
        }
        return $this->sendMessage($ticket->user, $ticket, $body);
    }

    public function answer(User $user, Ticket $ticket, string $body): TicketMessage
    {
        $ticket->update([
            "status" => TicketStatus::Answered->value
        ]);
        return $this->sendMessage($user, $ticket, $body);
    }

    public function getTicketsFor(User $user, array $relations = []): HasMany
    {
        return $user->tickets()->withFilters()->with($relations);
    }

    public function getTickets(array $relations = []): Builder
    {
        return Ticket::query()->with($relations);
    }

    public function getUnClosedTickets(array $relations = []): Builder
    {
        return Ticket::with($relations)
            ->where("status", "!=", TicketStatus::Closed->value)
            ->with($relations)
            ->latest();
    }

    public function getClosedTickets(array $relations = []): Builder
    {
        return Ticket::with($relations)
            ->whereNotNull("close_at")
            ->with($relations)
            ->latest();
    }

    public function seenMessage(User $user, Ticket $ticket): void
    {
        $ticket->messages()->unSeen($user->id)->update(["seen_at" => now()]);
    }

    public function close(Ticket $ticket, ?int $rate = null): void
    {
        $ticket->update([
            "status" => TicketStatus::Closed->value,
            "rate" => $rate
        ]);
    }

    public function autoClose(Ticket $ticket): void
    {
        $time = now()->addDays($ticket->category->auto_close);
        $ticket->update([
            "close_at" => $time
        ]);
    }

    private function canSendMessage(Ticket $ticket): bool
    {
        return $ticket->status != TicketStatus::Closed->value;
    }

    private function sendMessage(User $user, Ticket $ticket, string $body): TicketMessage
    {
        return $ticket->messages()->create([
            "user_id" => $user->id,
            "body" => $body
        ]);
    }
}
