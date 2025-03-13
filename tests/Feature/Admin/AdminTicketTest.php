<?php

namespace Admin;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketMessage;
use Tests\AdminTest;

class AdminTicketTest extends AdminTest
{
    public function test_answer_should_answer_ticket()
    {
        $ticket = Ticket::factory()->create();
        $data = [
            "body" => "message body"
        ];
        $this->withoutExceptionHandling();
         $this->postJson(route("v1.admin.tickets.answer", $ticket), $data);
        $this->assertEquals($ticket->fresh()->status, TicketStatus::Answered->value);
    }

    public function test_answer_should_not_answer_closed_tickets()
    {
        $ticket = Ticket::factory()->create(["status" => TicketStatus::Closed->value]);
        $data = [
            "body" => "message body"
        ];
        $this->postJson(route("v1.admin.tickets.answer", $ticket), $data);
        $this->assertDatabaseMissing("ticket_messages", $data);
    }

    public function test_close_should_close_ticket()
    {
        $ticket = Ticket::factory()->create();
        $this->postJson(route("v1.admin.tickets.close", $ticket));
        $this->assertEquals($ticket->fresh()->status, TicketStatus::Closed->value);
    }

    public function test_show_should_seen_user_ticket_messages()
    {
        $ticket = Ticket::factory()
            ->create();
        $message1 = TicketMessage::factory()
            ->for($ticket)
            ->for($ticket->user)
            ->create();
        $message2 = TicketMessage::factory()
            ->for($ticket)
            ->for($this->user)
            ->create();
        $this->assertNull($message1->fresh()->seen_at);
        $this->assertNull($message2->fresh()->seen_at);
        $this->getJson(route("v1.admin.tickets.show", $ticket));
        $this->assertNotNull($message1->fresh()->seen_at);
        $this->assertNull($message2->fresh()->seen_at);
    }
}
