<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertiseOrder extends ShowModel
{
    protected $fillable = [
        "ads_id", "link", "image", "item_id", "status", "show_at", "end_at"
    ];

    public function lastOrder(): ?ShowModel
    {
        return $this->ads->lastOrder();
    }

    public function ads(): BelongsTo
    {
        return $this->belongsTo(Advertise::class, "ads_id");
    }

    protected function product(): Product
    {
        $product = new Product();
        $product->name = "system advertise";
        $product->price = $this->ads->price;
        $product->description = "System advertise";
        return $product;
    }
}


