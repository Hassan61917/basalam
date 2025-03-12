<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class WishlistResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "product" => $this->mergeRelation(ProductResource::class, "product")
        ];
    }
}
