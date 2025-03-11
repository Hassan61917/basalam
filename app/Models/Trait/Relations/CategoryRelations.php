<?php

namespace App\Models\Trait\Relations;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CategoryRelations
{
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }
}
