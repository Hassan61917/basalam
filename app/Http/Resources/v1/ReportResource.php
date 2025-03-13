<?php

namespace App\Http\Resources\v1;

use App\Http\Resources\AppJsonResource;
use App\Models\Post;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends AppJsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "body" => $this->body,
            "user" => $this->mergeRelation(UserResource::class, "user"),
            "category" => $this->mergeRelation(ReportCategoryResource::class, "category"),
            "subject" => $this->mergeWhenLoaded("subject", $this->getSubjectResource()),
        ];
    }

    private function getSubjectResource(): JsonResource
    {
        $resource = match ($this->subject->type) {
            Post::class => PostResource::class,
            ServiceItem::class => ServiceItemResource::class,
        };
        return $resource::make($this->subject->id);
    }

}
