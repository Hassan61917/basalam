<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class OrderResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "totalPrice" => $this->total_price,
            "status" => $this->status,
            "priceAfterDiscount" => $this->getAmount(),
            "discountCode" => $this->discount_code,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "items" => $this->mergeRelations(OrderItemResource::class, "items"),
            "itemsCount" => $this->mergeCount("items")
        ];
    }
}
