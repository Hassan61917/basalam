<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class UserResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "registerAt" => $this->created_at,
            "roles" => $this->mergeRelations(RoleResource::class, "roles"),
            "profile" => $this->mergeRelation(ProfileResource::class, "profile"),
            "wallet" => $this->mergeRelation(WalletResource::class, "wallet"),
            "page" => $this->mergeRelation(PageResource::class, "page"),
        ];
    }
}
