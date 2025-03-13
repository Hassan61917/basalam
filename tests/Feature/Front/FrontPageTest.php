<?php

namespace Front;

use App\Enums\PostStatus;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use Tests\UserTest;

class FrontPageTest extends UserTest
{
    public function test_follow_should_follow_user_if_page_is_public()
    {
        $user = $this->makeUser();
        $this->postJson(route("v1.front.social.pages.follow", $user->page));
        $this->assertDatabaseHas("follows", [
            "follow_id" => $user->id,
            "accepted" => true
        ]);
    }

    public function test_follow_should_create_follow_request_if_page_is_private()
    {
        $user = $this->privatePage();
        $this->postJson(route("v1.front.social.pages.follow", $user->page));
        $this->assertDatabaseHas("follows", [
            "follow_id" => $user->id,
            "accepted" => null
        ]);
    }

    public function test_show_should_not_see_page_if_it_is_private()
    {
        $user = $this->privatePage();
        $res = $this->getJson(route("v1.front.social.pages.show", $user->page));
        $res->assertStatus(403);
    }

    public function test_show_should_see_page_if_it_is_private_but_user_is_a_follower()
    {
        $user = $this->privatePage();
        $this->follow($user);
        $res = $this->getJson(route("v1.front.social.pages.show", $user->page));
        $res->assertStatus(200);
    }

    public function test_pagePosts_should_see_posts_that_belongs_to_my_followings()
    {
        $user = $this->makeUser();
        $post1 = $this->createPost($user);
        $post2 = $this->createPost($this->user);
        $post3 = $this->createPost();
        $this->follow($user);
        $res = $this->getJson(route("v1.front.social.pages.posts", $post1));
        $res->assertSee($post1->title);
        $res->assertSee($post2->title);
        $res->assertDontSee($post3->title);
    }

    private function follow(User $user): void
    {
        Follow::factory()->create([
            "user_id" => $this->user->id,
            "follow_id" => $user->id,
            "accepted" => true
        ]);
    }
    private function createPost(?User $user = null): Post
    {
        return Post::factory()->create([
            "user_id" => $user ? $user->id : User::factory()->create()->id,
            "status" => PostStatus::Published->value
        ]);
    }
    private function privatePage(): User
    {
        $user = $this->makeUser();
        $user->page()->update(["is_private" => true]);
        return $user;
    }
}
