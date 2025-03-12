<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends AppModel
{
    protected $fillable = [
        "shop_id", "status", "total_price", "discount_code", "discount_price","address"
    ];

    public function scopeDraft(Builder $builder): Builder
    {
        return $builder->where("status", OrderStatus::Draft->value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function item(int $userId, int $productId): HasOne
    {
        return $this->hasOne(OrderItem::class)
            ->where("user_id", $userId)
            ->where("product_id", $productId);
    }

    public function getAmount(): int
    {
        return $this->discount_price ?: $this->total_price;
    }

    public function isStatus(OrderStatus $status): bool
    {
        return $this->status == $status->value;
    }
}

