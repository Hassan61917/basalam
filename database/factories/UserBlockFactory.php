<?php

namespace Database\Factories;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBlock>
 */
class UserBlockFactory extends AppFactory
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
            "block_id" => User::factory(),
            "until" => null
        ];
    }
}
