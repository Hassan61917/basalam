<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::factory()->create();
        return [
            "user_id" => User::factory(),
            "shop_id" => $product->shop,
            "product_id" => $product->id,
            "question" => $this->faker->sentence(),
            "answer" => $this->faker->text(),
        ];
    }
}
