<?php

namespace App\Models\Trait\Relations;

use App\Models\Category;
use App\Models\Commission;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }
    public function commission(): HasOne
    {
        return $this->hasOne(Commission::class)->unExpired();
    }
}
