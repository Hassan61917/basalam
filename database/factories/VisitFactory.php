<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends AppFactory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $post = $this->randomModel(Post::class);
        return [
            "user_id" => $this->randomModel(User::class),
            "ip" => $this->faker->ipv4(),
            "visitable_type" => $post::class,
            "visitable_id" => $post->id,
        ];
    }
}
