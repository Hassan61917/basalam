<?php

namespace App\Models;

use App\Enums\OrderItemStatus;
use App\Models\Trait\Relations\OrderItemRelations;
use Illuminate\Database\Eloquent\Builder;

class OrderItem extends AppModel
{
    use OrderItemRelations;
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
}
