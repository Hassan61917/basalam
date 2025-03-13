<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use Illuminate\Http\Request;

class TicketCategoryResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->name,
            "priority" => $this->priority,
            "autoClose" => $this->auto_close,
            "tickets" => $this->mergeRelations(TicketResource::class, "tickets"),
            "ticketsCount" => $this->mergeCount("tickets")
        ];
    }
}
