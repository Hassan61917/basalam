<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class FollowResource extends AppJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "accepted" => $this->accepted,
            "user" => $this->mergeRelation(UserResource::class, "user", ["page"]),
            "follow" => $this->mergeRelation(UserResource::class, "follow", ["page"]),
        ];
    }
}
