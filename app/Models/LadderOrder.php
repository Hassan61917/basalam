<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LadderOrder extends ShowModel
{
    protected $fillable = [
        "shop_id", "ladder_id","item_id", "status", "show_at", "end_at"
    ];
    public function lastOrder(): ?ShowModel
    {
        return $this->ladder->lastOrder();
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function ladder(): BelongsTo
    {
        return $this->BelongsTo(Ladder::class);
    }

    protected function product(): Product
    {
        $product = new Product();
        $product->name = "system ladder";
        $product->price = $this->ladder->price;
        $product->description = "System ladder";
        return $product;
    }
}
