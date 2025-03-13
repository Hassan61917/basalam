<?php

namespace App\ModelServices\Social;

use App\Models\Interfaces\Likeable;
use App\Models\Like;
use App\Models\User;

class LikeService
{
    public function like(User $user, Likeable $model): void
    {
        $this->toggle($user, $model, true);
    }

    public function disLike(User $user, Likeable $model): void
    {
        $this->toggle($user, $model, false);
    }

    private function toggle(User $user, Likeable $model, bool $isLike): void
    {
        $last = $this->exists($user, $model);
        if ($last) {
            $this->remove($user, $model);
        }
        if (!$last || $last->isLike != $isLike) {
            $this->makeLike($user, $model, $isLike);
        }

    }

    private function makeLike(User $user, Likeable $model, bool $isLike): void
    {
        $data = ["user_id" => $user->id, "isLike" => $isLike];
        if ($isLike) {
            $model->likes()->create($data);
        } else {
            $model->dislikes()->create($data);
        }
    }

    private function exists(User $user, Likeable $model): ?Like
    {
        return $user->likes()->where([
            "likeable_type" => $model::class,
            "likeable_id" => $model->id
        ])->first();
    }

    private function remove(User $user, Likeable $model): void
    {
        $user->likes()->where([
            "likeable_type" => $model::class,
            "likeable_id" => $model->id
        ])->delete();
    }
}
