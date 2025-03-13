<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Like extends AppModel
{
    protected $fillable = ["user_id", "isLike"];
    public function scopeGetItems(Builder $builder,bool $isLike = true): Builder
    {
        return $builder->where("isLike", $isLike);
    }
    protected function casts(): array
    {
        return [
            "isLike" => "bool"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}

