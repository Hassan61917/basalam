<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends AppModel
{
    use SoftDeletes;

    protected $fillable = [
        "user_id",
        "ip"
    ];

    public function scopeOnlyIps(Builder $builder, string $ip): Builder
    {
        return $builder->where('ip', $ip)->whereNull("user_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function visitable(): MorphTo
    {
        return $this->morphTo("visitable");
    }
}
