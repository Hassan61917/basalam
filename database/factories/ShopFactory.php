<?php

namespace Database\Factories;

use App\Enums\ShopStatus;
use App\Models\Category;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends AppFactory
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
            "category_id" => Category::factory(),
            "name" => $this->faker->company(),
            "description" => $this->faker->text(),
            "status" => ShopStatus::Draft->value
        ];
    }
}
