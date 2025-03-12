<?php

namespace App\ModelServices\Social;

use App\Enums\PostStatus;
use App\Events\PostWasCreated;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostService
{
    public function getFollowingPosts(User $user): Builder
    {
        return Post::query()
            ->select("posts.*")
            ->leftJoin("follows", "posts.user_id", "=", "follows.follow_id")
            ->orWhere("posts.user_id", $user->id)
            ->orWhere("follows.user_id", $user->id)
            ->published();
    }

    public function getPosts(array $relations = []): Builder
    {
        return Post::query()->with($relations);
    }

    public function getPublishedPosts(): Builder
    {
        return Post::withStatus(PostStatus::Published)->with("user.page");
    }

    public function getDraftPosts(): Builder
    {
        return Post::withStatus(PostStatus::Draft)->with("user.page");
    }

    public function getPostsFor(User $user, array $relations = []): HasMany
    {
        return $user->posts()->with($relations);
    }

    public function make(User $user, array $data)
    {
        $data["published_at"] = $data["published_at"] ?? now();
        if (Carbon::make($data["published_at"])->isFuture()) {
            $data["status"] = PostStatus::Draft->value;
        } else {
            $data["status"] = PostStatus::Published->value;
        }
        $post = $user->posts()->create($data);
        PostWasCreated::dispatch($post);
        return $post;
    }
}
