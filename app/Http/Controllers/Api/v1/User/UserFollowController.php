<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserFollowRequest;
use App\Http\Resources\v1\FollowResource;
use App\Models\Follow;
use App\ModelServices\Social\FollowService;
use Illuminate\Http\JsonResponse;

class UserFollowController extends AuthUserController
{
    protected string $resource = FollowResource::class;

    public function __construct(
        public FollowService $followService
    )
    {
    }

    public function index(): JsonResponse
    {
        $requests = $this->followService->getFollowingRequests($this->authUser());
        return $this->ok($this->paginate($requests));
    }

    public function followingRequests(): JsonResponse
    {
        $requests = $this->followService->getMyRequests($this->authUser());
        return $this->ok($this->paginate($requests));
    }

    public function accept(Follow $follow): JsonResponse
    {
        $this->followService->acceptFollow($follow);
        return $this->ok($follow);
    }

    public function reject(Follow $follow): JsonResponse
    {
        $this->followService->rejectFollow($follow);
        return $this->ok($follow);
    }

    public function follow(UserFollowRequest $request): JsonResponse
    {
        $data = $request->validated();
        $follow = $this->followService->follow($this->authUser(), $data["page_id"]);
        return $this->ok($follow);
    }

    public function unfollow(UserFollowRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->followService->unfollow($this->authUser(), $data["page_id"]);
        return $this->message("unFollowed");
    }

}
