<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\ReportCategory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends AppFactory
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
            "category_id" => $this->randomModel(ReportCategory::class),
            "user_id" => $this->randomModel(User::class),
            "body" => $this->faker->realText(),
            "reportable_type" => $post::class,
            "reportable_id" => $post->id,
        ];
    }
}
