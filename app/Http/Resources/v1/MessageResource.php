<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class MessageResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "sender" => $this->mergeRelation(UserResource::class, "sender", ["profile"]),
            "receiver" => $this->mergeRelation(UserResource::class, "receiver", ["profile"]),
            "reply" => $this->mergeRelation(MessageResource::class, "reply"),
            "message" => $this->message,
            "seen_at" => $this->seen_at,
        ];
    }
}


