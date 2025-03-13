<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class LadderOrderResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "status" => $this->status,
            "showedAt" => $this->show_at,
            "endAt" => $this->end_at ? $this->end_at->DiffForHumans() : null,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "shop" => $this->mergeRelation(ShopResource::class, "shop"),
            "ladder" => $this->mergeRelation(LadderResource::class, "ladder")
        ];
    }
}
