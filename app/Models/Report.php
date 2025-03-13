<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends AppModel
{
    protected $fillable = [
        "category_id", "reportable_id", "reportable_type", "body"
    ];
    public function scopeBetween(Builder $builder,Carbon $from, Carbon $to):Builder
    {
        return $builder->whereBetween('created_at', [$from, $to]);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(ReportCategory::class, "category_id");
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }
}
