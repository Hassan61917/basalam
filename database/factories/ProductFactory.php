<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "shop_id" => Shop::factory(),
            "category_id" => Category::factory(),
            "name" => $this->faker->name(),
            "description" => $this->faker->text(),
            "price" => rand(100, 1000),
            "available" => true,
            "hidden" => false
        ];
    }
}
