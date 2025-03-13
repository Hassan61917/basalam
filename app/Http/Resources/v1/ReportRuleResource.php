<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class ReportRuleResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "count" => $this->count,
            "duration" => $this->duration,
            "category" => $this->mergeRelation(ReportCategoryResource::class, "category")
        ];
    }
}
