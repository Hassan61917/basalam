<?php

namespace Tests\Feature\User;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\UserTest;

class UserCommentTest extends UserTest
{
    public function test_index_should_see_user_comments()
    {
        $comment1 = Comment::factory()->for($this->user)->create();
        $comment2 = Comment::factory()->create();
        $res = $this->getJson(route("v1.user.comments.index"));
        $res->assertSee($comment1->comment);
        $res->assertDontSee($comment2->comment);
    }

    public function test_postComments_should_return_user_posts_comments()
    {
        $post1 = $this->makePost();
        $post2 = $this->makePost($this->makeUser());
        $comment1 = Comment::factory()->for($post1)->create();
        $comment2 = Comment::factory()->for($post2)->create();
        $res = $this->getJson(route("v1.user.my-posts-comments"));
        $res->assertSee($comment1->comment);
        $res->assertDontSee($comment2->comment);
    }

    public function test_store_should_store_comment()
    {
        $data = $this->commentData();
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.comments.store"), $data);
        $this->assertDatabaseCount("comments", 1);
    }

    public function test_store_should_not_store_comment_if_post_owner_disabled_comments()
    {
        $post = $this->makePost($this->user,["can_comment" => false]);
        $data = $this->commentData($post);
        $this->postJson(route("v1.user.comments.store"), $data)
            ->assertStatus(422);
    }

    public function test_store_should_not_store_comment_if_disabled_comments()
    {
        $this->user->page()->update(["can_comment" => false]);
        $data = $this->commentData();
        $this->postJson(route("v1.user.comments.store"), $data)
            ->assertStatus(422);
    }

    private function makePost(?User $user = null, array $data = []): Post
    {
        $user = $user ?: $this->user;
        return Post::factory()->for($user)->create($data);
    }
    private function commentData(?Post $post = null): array
    {
        $post = $post ?: $this->makePost();
        return Comment::factory()->for($post)->raw();
    }
}
