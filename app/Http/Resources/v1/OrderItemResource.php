<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class OrderItemResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "quantity" => $this->quantity,
            "total_price" => $this->total_price,
            "status" => $this->status,
            "address" => $this->order->address,
            "product" => $this->mergeRelation(ProductResource::class, "product"),
            "shop" => $this->mergeRelation(ShopResource::class, "shop"),
            "user" => $this->mergeRelation(UserResource::class, "user"),
        ];
    }
}
