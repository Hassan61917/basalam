<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Requests\v1\User\UserProfileRequest;
use App\Http\Resources\v1\UserResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserProfileController extends AuthController
{
    protected string $resource = UserResource::class;

    public function index(): JsonResponse
    {
        $user = $this->authUser(['profile']);
        if (!$user->profile) {
            return $this->error(403, "you must complete your profile");
        }
        return $this->ok($user);
    }

    public function store(UserProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->authUser(['profile'])->profile()->create($data);
        return $this->ok($user);
    }

    public function update(UserProfileRequest $request): JsonResponse
    {
        $user = $this->authUser(['profile']);
        $user->profile()->update($request->validated());
        return $this->ok($user);
    }
}
