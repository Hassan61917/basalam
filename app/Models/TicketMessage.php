<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketMessage extends AppModel
{
    protected $fillable = [
        "user_id", "message_id", "body", "seen_at"
    ];

    public function scopeUnseen(Builder $builder, int $userId): Builder
    {
        return $builder
            ->where("user_id", "!=", $userId)
            ->whereNull("seen_at");
    }

    protected function casts(): array
    {
        return [
            "seen_at" => "datetime",
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
