<?php

namespace App\Filters\Visit;

use App\Filters\ModelFilter;
use App\Models\Post;
use App\Models\ServiceItem;
use Illuminate\Database\Eloquent\Builder;

class VisitFilter extends ModelFilter
{
    protected array $methodFilters = ["model"];
    protected array $before = ["groupBySubject"];

    public function model(Builder $builder, ?string $value): Builder
    {
        $models = ["post", "item"];
        $value = !$value || !in_array($value, $models) ? "item" : $value;
        $type = $value == "post" ? Post::class : ServiceItem::class;
        return $builder->where("visitable_type", $type);
    }

    public function groupBySubject(Builder $builder, ?string $value = null): Builder
    {
        return $builder
            ->groupBy("visitable_id");
    }
}
