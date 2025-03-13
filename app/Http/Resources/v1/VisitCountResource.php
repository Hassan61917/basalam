<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class VisitCountResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "visitsCount" => $this->mergeCount("visits")
        ];
    }
}
