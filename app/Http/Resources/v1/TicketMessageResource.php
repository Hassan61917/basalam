<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class TicketMessageResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "body" => $this->body,
            "seenAt" => $this->seen_at,
            "ticket" => $this->mergeRelation(TicketResource::class, "ticket"),
            "user" => $this->mergeRelation(UserResource::class, "user"),
        ];
    }
}
