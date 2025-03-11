<?php

namespace App\Http\Resources;

use App\Http\Resources\v1\CategoryResource;
use App\Http\Resources\v1\ShopResource;
use Illuminate\Http\Request;

class ProductResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
            "price" => $this->price,
            "available" => $this->available,
            "hidden" => $this->hidden,
            "shop" => $this->mergeRelation(ShopResource::class, "shop"),
            "category" => $this->mergeRelation(CategoryResource::class, "category"),
        ];
    }
}
