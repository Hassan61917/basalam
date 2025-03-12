<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Page;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends AppFactory
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
            "title" => $this->faker->sentence(),
            "slug" => $this->faker->slug(),
            "body" => $this->faker->text(),
            "status" => PostStatus::Draft->value,
            "can_comment" => true,
            "published_at" => null
        ];
    }
}
