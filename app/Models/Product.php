<?php

namespace App\Models;

use App\Models\Trait\Relations\ProductRelations;
use Illuminate\Database\Eloquent\Builder;

class Product extends AppModel
{
    use ProductRelations;

    protected $fillable = [
        "category_id","name", "description", "price", "available", "hidden"
    ];

    public function scopeAvailable(Builder $builder): Builder
    {
        return $builder
            ->where("available", true)
            ->where("hidden", false);
    }
    public function scopeWithHidden(Builder $builder): Builder
    {
        return $builder->where("available", true);
    }

    public function addOptions(array $options = []): void
    {
        foreach ($options as $key => $option) {
            $this->options()->create(["key" => $key, "value" => $option]);
        }
    }

    public function casts(): array
    {
        return [
            "available" => "boolean",
            "hidden" => "boolean"
        ];
    }


}
