<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Enums\LikeableModel;
use App\Http\Controllers\AuthUserController;
use App\Http\Requests\v1\User\UserLikeRequest;
use App\ModelServices\Social\LikeService;
use App\Utils\EnumHelper;
use Illuminate\Http\JsonResponse;

class UserLikeController extends AuthUserController
{
    public function __construct(
        private LikeService $likeService
    )
    {
    }

    public function like(UserLikeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = $this->getModel($data);
        $this->likeService->like($this->authUser(), $model);
        return $this->message("liked successfully");
    }
    public function dislike(UserLikeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $model = $this->getModel($data);
        $this->likeService->disLike($this->authUser(), $model);
        return $this->message("liked successfully");
    }
    private function getModel(array $data)
    {
        $model = EnumHelper::getValue(LikeableModel::class, $data['model'])->value;
        return $model::find($data['model_id']);
    }
}
