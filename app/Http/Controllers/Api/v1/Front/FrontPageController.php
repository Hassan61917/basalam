<?php

namespace App\Http\Controllers\Api\v1\Front;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\FollowResource;
use App\Http\Resources\v1\PageResource;
use App\Http\Resources\v1\PostResource;
use App\Http\Resources\v1\UserResource;
use App\Models\Page;
use App\ModelServices\Social\FollowService;
use App\ModelServices\Social\PostService;
use Illuminate\Http\JsonResponse;

class FrontPageController extends Controller
{
    public function __construct(
        private FollowService $followService,
        private PostService   $postService,
    )
    {
    }

    public function index(): JsonResponse
    {
        $user = $this->authUser();
        $user->load("page", "profile");
        return $this->ok(null, UserResource::make($user));
    }

    public function pagePosts(): JsonResponse
    {
        $posts = $this->postService->getFollowingPosts($this->authUser());
        return $this->ok(null, PostResource::collection($this->paginate($posts)));
    }

    public function show(Page $page): JsonResponse
    {
        if ($page->is_private && !$this->followService->isFollowed($this->authUser(), $page->user)) {
            return $this->error(403, "Page is private");
        }
        $page->load("user", "posts");
        return $this->ok(null, PageResource::make($page));
    }

    public function follow(Page $page): JsonResponse
    {
        $follow = $this->followService->follow($this->authUser(), $page->user);
        $follow->load("user", "follow");
        return $this->ok(null, FollowResource::make($follow));
    }

    public function unfollow(Page $page): JsonResponse
    {
        $this->followService->unfollow($this->authUser(), $page->user);
        return $this->message("unfollowed successfully");
    }
}
