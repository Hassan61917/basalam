<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class BlockResource extends AppJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user" => $this->mergeRelation(UserResource::class, "user", ["profile"]),
            "block" => $this->mergeRelation(UserResource::class, "block", ["profile"]),
            "until"=>$this->until
        ];
    }
}
