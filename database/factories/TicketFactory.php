<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\TicketCategory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "category_id" => $this->randomModel(TicketCategory::class),
            "user_id" => $this->randomModel(User::class),
            "title" => $this->faker->sentence(),
            "status" => TicketStatus::Waiting->value,
            "closed_at" => null,
        ];
    }
}
