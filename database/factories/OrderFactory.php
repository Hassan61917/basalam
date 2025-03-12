<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends AppFactory
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
            "status" => OrderStatus::Draft->value,
            "address" => $this->faker->address,
            "total_price" => 0,
            "discount_price" => null,
            "discount_code" => null,
        ];
    }
}
