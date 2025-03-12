<?php

namespace App\Models;

use App\Enums\OrderItemStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends AppModel
{
    protected $with = ["product"];
    protected $fillable = [
        "user_id", "order_id", "shop_id", "product_id", "status",
        "quantity", "total_price", "discount_price", "shop_discount"
    ];

    public function casts(): array
    {
        return [
            "shop_discount" => "boolean"
        ];
    }

    public function updateStatus(OrderItemStatus $status): void
    {
        $this->update(["status" => $status->value]);
    }

    public function scopeVisible(Builder $builder): Builder
    {
        return $builder->where("status", "!=", OrderItemStatus::Waiting->value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
