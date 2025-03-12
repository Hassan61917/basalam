<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class PageResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "user" => $this->mergeRelation(UserResource::class, "user", ["profile"]),
            "username" => $this->username,
            "isPrivate" => $this->is_private,
            "canComment" => $this->can_comment,
            "followersCount" => $this->user->followers()->count(),
            "followingsCount" => $this->user->followings()->count(),
        ];
    }
}
