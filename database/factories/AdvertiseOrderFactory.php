<?php

namespace Database\Factories;

use App\Enums\ShowStatus;
use App\Models\Advertise;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdvertiseOrder>
 */
class AdvertiseOrderFactory extends AppFactory
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
            "ads_id" => Advertise::factory(),
            "link" => $this->faker->url(),
            "image" => $this->faker->imageUrl(),
            "status" => ShowStatus::Waiting->value,
            "show_at" => now(),
            "end_at" => now()->addMonth(),
        ];
    }
}

