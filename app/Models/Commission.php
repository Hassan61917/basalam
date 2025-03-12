<?php

namespace App\Models;

use App\Casts\DateCast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends AppModel
{
    protected $fillable = [
        "category_id", "percent", "max_amount", "applied_at", "expired_at"
    ];

    public function scopeUnExpired(Builder $builder): Builder
    {
        return $builder->where('expired_at', '>=', now());
    }

    public function isExpired(): bool
    {
        return $this->expired_at < now();
    }

    public function casts(): array
    {
        return [
            "applied_at" => DateCast::class,
            "expired_at" => DateCast::class
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
