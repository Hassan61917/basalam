<?php

namespace Database\Factories;

use App\Models\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportRule>
 */
class ReportRuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "category_id" => ReportCategory::factory(),
            "count" => 10,
            'duration' => 24
        ];
    }
}
