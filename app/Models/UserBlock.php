<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBlock extends AppModel
{
    protected $fillable = ["block_id", "until"];

    public function casts(): array
    {
        return [
            "until" => "datetime"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blocked(): BelongsTo
    {
        return $this->belongsTo(User::class, "blocked_id");
    }
}

