<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class CommentResource extends AppJsonResource
{
    protected array $resources = [LikeCountResource::class];

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "comment" => $this->comment,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "post" => $this->mergeRelation(PostResource::class, "post"),
            "children" => $this->mergeRelations(CommentResource::class, "children", ["children"]),
        ];
    }
}
