<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends AppModel
{
    protected $fillable = [
        "order_id", "rate", "body", "reply"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, "order_id");
    }
}
