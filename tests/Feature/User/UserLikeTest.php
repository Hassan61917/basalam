<?php

namespace Tests\Feature\User;

use App\Models\Question;
use Tests\UserTest;

class UserLikeTest extends UserTest
{
    public function test_like_user_can_like()
    {
        $question = Question::factory()->create();
        $data = [
            "model" => "question",
            "model_id" => $question->id,
        ];
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 1);
    }

    public function test_like_should_remove_like_is_user_hint_twice()
    {
        $question = Question::factory()->create();
        $data = [
            "model" => "question",
            "model_id" => $question->id,
        ];
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->postJson(route("v1.user.like"), $data);
        $this->assertDatabaseCount("likes", 0);
    }

    public function test_like_user_can_dislike()
    {
        $question = Question::factory()->create();
        $data = [
            "model" => "question",
            "model_id" => $question->id,
        ];
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseHas("likes", [
            "isLike" => false,
        ]);
    }

    public function test_like_should_remove_dislike_is_user_hint_twice()
    {
        $question = Question::factory()->create();
        $data = [
            "model" => "question",
            "model_id" => $question->id,
        ];
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseCount("likes", 1);
        $this->postJson(route("v1.user.dislike"), $data);
        $this->assertDatabaseCount("likes", 0);
    }
}
