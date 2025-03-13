<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class LikeCountResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "likesCount" => $this->mergeCount("likes"),
            "dislikesCount" => $this->mergeCount("dislikes"),
        ];
    }
}
