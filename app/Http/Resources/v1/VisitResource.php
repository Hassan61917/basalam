<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class VisitResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "ip" => $this->ip,
            "trashed" => $this->trashed(),
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "ads" => $this->mergeRelation(AdsResource::class, "ads"),
        ];
    }
}
