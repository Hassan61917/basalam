<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class CommissionResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "percent" => $this->percent,
            "maxAmount" => $this->max_amount,
            "applied_at" => $this->applied_at,
            "expired_at" => $this->expired_at,
            "category" => $this->mergeRelation(CategoryResource::class, "category")
        ];
    }
}
