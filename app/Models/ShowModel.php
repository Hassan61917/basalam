<?php

namespace App\Models;

use App\Enums\ShowStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class ShowModel extends AppModel
{
    public function scopeShowing(Builder $builder): Builder
    {
        return $builder->where("status", ShowStatus::Showing->value);
    }

    public function casts(): array
    {
        return [
            "show_at" => "datetime",
            "end_at" => "datetime",
        ];
    }

    public function lastOrder(): ?ShowModel
    {
        return null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class,"item_id");
    }

    public function toProduct(): Product
    {
        $shop = Shop::systemShop();
        $product = $this->product();
        $product->category_id = Category::query()->firstOrCreate([
            "name" => "system product",
            "slug" => "system-product"
        ])->id;
        return $shop->products()->firstOrCreate($product->toArray());
    }

    protected abstract function product(): Product;
}
