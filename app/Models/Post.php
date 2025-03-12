<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends AppModel
{
    protected $fillable = [
        "title",
        "slug",
        "body",
        "status",
        "can_comment",
        "published_at"
    ];

    public function scopeWithStatus(Builder $builder, PostStatus $status): Builder
    {
        return $builder->where('status', $status->value);
    }

    public function scopePublished(Builder $builder): Builder
    {
        return $builder->withStatus($builder, PostStatus::Published);
    }

    public function isPublished(): bool
    {
        return $this->status == PostStatus::Published->value;
    }

    protected function casts(): array
    {
        return [
            "can_comment" => "boolean",
            "published_at" => "datetime"
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
