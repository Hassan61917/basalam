<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends AppModel
{
    protected $fillable = [
        "category_id", "title", "status", "closed_at", "rate"
    ];

    protected function casts(): array
    {
        return [
            "closed_at" => "datetime",
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class,"category_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class);
    }
}
