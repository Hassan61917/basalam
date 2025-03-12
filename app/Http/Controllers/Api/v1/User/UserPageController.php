<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserPageRequest;
use App\Http\Resources\v1\PageResource;
use Illuminate\Http\JsonResponse;

class UserPageController extends AuthUserController
{
    protected string $resource = PageResource::class;

    public function index(): JsonResponse
    {
        $user = $this->authUser();
        if (!$user->page) {
            $user->page()->create(["username" => $user->id]);
        }
        $page = $user->page()->with("user");
        return $this->ok($page);
    }

    public function update(UserPageRequest $request): JsonResponse
    {
        $page = $this->authUser()->page;
        $page->update($request->all());
        return $this->ok($page);
    }
}
