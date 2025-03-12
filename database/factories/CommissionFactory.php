<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commission>
 */
class CommissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "category_id" => Category::factory(),
            "percent" => 10,
            "max_amount" => 100,
            "applied_at"=>now()->format('Y-m-d'),
            "expired_at"=>now()->addYear()->format('Y-m-d'),
        ];
    }
}
