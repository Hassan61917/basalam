<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class QuestionResource extends AppJsonResource
{
    protected array $resources = [LikeCountResource::class];

    public function toArray(Request $request): array
    {
        return [
            "question" => $this->question,
            "answer" => $this->answer,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "shop" => $this->mergeRelation(ShopResource::class, "shop"),
            "product" => $this->mergeRelation(ProductResource::class, "product"),
        ];
    }
}
