<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class LadderResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "duration" => $this->duration,
            "price" => $this->price,
            "category" => $this->mergeRelation(CategoryResource::class, "category"),
            "orders" => $this->mergeRelations(LadderOrderResource::class, "orders"),
            "ordersCount" => $this->mergeCount("orders")
        ];
    }
}
