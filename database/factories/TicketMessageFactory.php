<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketMessage>
 */
class TicketMessageFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "ticket_id" => $this->randomModel(Ticket::class),
            "user_id" => $this->randomModel(User::class),
            "body" => $this->faker->paragraph(),
            "seen_at" => null,
            "message_id" => null
        ];
    }
}
