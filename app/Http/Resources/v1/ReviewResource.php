<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class ReviewResource extends AppJsonResource
{
    protected array $resources = [];

    public function toArray(Request $request): array
    {
        return [
            "rate" => $this->rate,
            "body" => $this->body,
            "reply" => $this->reply,
            "order" => $this->mergeRelation(OrderItemResource::class, "order",["product"])
        ];
    }
}
