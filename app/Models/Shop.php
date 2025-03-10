<?php

namespace App\Models;

use App\Enums\ShopStatus;
use App\Models\Trait\Relations\ShopRelations;
use Illuminate\Database\Eloquent\Builder;

class Shop extends AppModel
{
    use ShopRelations;

    protected $fillable = [
        "category_id", "name", "description", "status"
    ];

    public function scopeNotDraft(Builder $builder): Builder
    {
        return $builder->where("status","!=", ShopStatus::Draft->value);
    }

    public function scopeIsOpen(Builder $builder): Builder
    {
        return $builder->where('status', ShopStatus::Opened->value);
    }
}
