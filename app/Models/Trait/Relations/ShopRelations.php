<?php

namespace App\Models\Trait\Relations;

use App\Models\Category;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ShopRelations
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
