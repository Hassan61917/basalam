<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AdsResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "duration"=>$this->duration,
            "price" => $this->price,
            "ordersCount" => $this->mergeCount("orders"),
            "orders" => $this->mergeRelations(AdsOrderResource::class, "orders")
        ];
    }
}
