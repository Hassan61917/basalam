<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class PostResource extends AppJsonResource
{
    protected array $resources = [VisitCountResource::class];
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "body" => $this->description,
            "status" => $this->status,
            "publishedAt" => $this->published_at,
            "canComment" => $this->can_comment,
            "author" => $this->mergeRelation(UserResource::class, "user"),
        ];
    }
}
