<?php

namespace Database\Factories;

use App\Enums\PriorityType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportCategory>
 */
class ReportCategoryFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => $this->faker->sentence(3),
            "priority" => PriorityType::Low->value,
            "ban_duration" => 1,
        ];
    }
}
