<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOption extends AppModel
{
    protected $fillable = [
        "product_id", "key", "value"
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
