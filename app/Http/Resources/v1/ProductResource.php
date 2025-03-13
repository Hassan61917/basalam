<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class ProductResource extends AppJsonResource
{
    protected array $resources = [VisitCountResource::class];
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
            "wishlistCount"=>$this->mergeCount("wishlist")
        ];
    }
}
