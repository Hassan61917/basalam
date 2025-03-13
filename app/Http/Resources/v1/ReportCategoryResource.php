<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class ReportCategoryResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "priority" => $this->priority,
            "reports" => $this->mergeRelations(ReportResource::class, "reports"),
            "reportsCount" => $this->mergeCount("reports")
        ];
    }
}
