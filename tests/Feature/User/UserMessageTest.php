<?php

namespace Tests\Feature\User;

use App\Models\Message;
use App\Models\UserBlock;
use Tests\UserTest;

class UserMessageTest extends UserTest
{
    public function test_inbox_user_can_see_received_messages()
    {
        $message1 = Message::factory()
            ->for($this->makeUser(), "sender")
            ->for($this->user, "receiver")
            ->create();

        $message2 = Message::factory()->create();

        $this->withoutExceptionHandling();
        $res = $this->getJson(route("v1.user.inbox"));
        $res->assertSee($message1->message);
        $res->assertDontSee($message2->message);
    }

    public function test_inbox_user_can_see_sent_messages()
    {
        $message1 = Message::factory()
            ->for($this->makeUser(), "receiver")
            ->for($this->user, "sender")
            ->create();

        $message2 = Message::factory()->create();

        $this->withoutExceptionHandling();
        $res = $this->getJson(route("v1.user.outbox"));
        $res->assertSee($message1->message);
        $res->assertDontSee($message2->message);
    }

    public function test_chat_should_see_chat_messages()
    {
        $message1 = Message::factory()
            ->for($this->makeUser(), "sender")
            ->for($this->user, "receiver")
            ->create();

        $message2 = Message::factory()
            ->for($this->makeUser(), "receiver")
            ->for($this->user, "sender")
            ->create();

        $message3 = Message::factory()
            ->for($this->makeUser(), "receiver")
            ->for($this->user, "sender")
            ->create();


        $this->withoutExceptionHandling();
        $res = $this->getJson(route("v1.user.chat", $message2->receiver));
        $res->assertSee($message1->message);
        $res->assertSee($message2->message);
        $res->assertDontSee($message3->message);
    }

    public function test_store_should_send_message()
    {
        $message = Message::factory()
            ->for($this->makeUser(), "receiver")
            ->raw();

        $this->withoutExceptionHandling();
        $res = $this->postJson(route("v1.user.messages.store"), $message);
        $this->assertDatabaseCount("messages", 1);
    }

    public function test_store_should_not_send_message_if_user_is_blocked()
    {
        $user = $this->makeUser();

        UserBlock::factory()
            ->for($user)
            ->for($this->user, "block")
            ->create();

        $message = Message::factory()
            ->for($user, "receiver")
            ->raw();

        $this->postJson(route("v1.user.messages.store"), $message);
        $this->assertDatabaseCount("messages", 0);
    }
}
