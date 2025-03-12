<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends AppModel
{
    protected $fillable = [
        "username",
        "is_private",
        "can_comment"
    ];

    public function casts(): array
    {
        return [
            "is_private" => "boolean",
            "can_comment" => "boolean",
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
