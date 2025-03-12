<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends AppModel
{
    public function scopeFollowRequests(Builder $builder, User $user): Builder
    {
        return $builder->where("user_id", $user->id)->whereNull("accepted");
    }

    public function scopeFollowingRequests(Builder $builder, User $user): Builder
    {
        return $builder->where("follow_id", $user->id)->whereNull("accepted");
    }

    protected $fillable = [
        "follow_id", "accepted"
    ];

    public function casts(): array
    {
        return [
            "accepted" => "boolean"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function follow(): BelongsTo
    {
        return $this->belongsTo(User::class, "follow_id");
    }
}

