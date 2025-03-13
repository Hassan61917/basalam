<?php

namespace Tests\Feature\User;

use App\Models\Ticket;
use App\Models\TicketCategory;
use Tests\UserTest;

class UserTicketTest extends UserTest
{
    public function test_store_should_store_ticket()
    {
        $category = TicketCategory::factory()->create();
        $data = [
            "category_id" => $category->id,
            "title" => "ticket title",
            "body" => "ticket message body"
        ];
        $this->postJson(route('v1.user.tickets.store'), $data);
        $this->assertDatabaseCount("tickets", 1);
        $this->assertDatabaseCount("ticket_messages", 1);
    }

    public function test_add_message_should_add_message_to_tickets()
    {
        $ticket = Ticket::factory()->create();
        $data = ["body" => "message"];
        $this->postJson(route("v1.user.tickets.add-message", $ticket), $data);
        $this->assertDatabaseCount("ticket_messages", 1);
    }

    public function test_close_should_close_ticket_with_rate()
    {
        $ticket = Ticket::factory()->create();
        $rate = 2;
        $data = ["rate" =>$rate];
        $this->postJson(route("v1.user.tickets.close", $ticket), $data);
        $this->assertEquals($ticket->fresh()->rate, $rate);
    }
}

