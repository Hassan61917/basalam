<?php

namespace Database\Factories;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "sender_id" => User::factory(),
            "receiver_id" => User::factory(),
            "message" => $this->faker->sentence(),
            "seen_at" => null,
            "reply_id" => null
        ];
    }
}
