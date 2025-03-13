<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class AdsOrderResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "link" => $this->link,
            "image" => $this->image,
            "status" => $this->status,
            "showAt" => $this->show_at,
            "endAt" => $this->end_at,
            "user" => $this->mergeRelation(UserResource::class, "user", ["profile"]),
            "ads" => $this->mergeRelation(AdsResource::class, "ads"),
        ];
    }
}
