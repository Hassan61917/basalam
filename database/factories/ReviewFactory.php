<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => User::factory(),
            "order_id" => OrderItem::factory(),
            "rate" => rand(1, 5),
            "body" => $this->faker->paragraph(),
            "reply" => $this->faker->paragraph()
        ];
    }
}
