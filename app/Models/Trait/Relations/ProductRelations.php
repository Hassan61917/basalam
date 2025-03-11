<?php

namespace App\Models\Trait\Relations;

use App\Models\Category;
<<<<<<< Updated upstream
use App\Models\ProductOption;
=======
use App\Models\Discount;
>>>>>>> Stashed changes
use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ProductRelations
{
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

<<<<<<< Updated upstream
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
=======
    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
>>>>>>> Stashed changes
    }
}
