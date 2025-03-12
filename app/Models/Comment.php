<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends AppModel
{
    protected $fillable = [
        "post_id", "comment_id", "comment"
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, "parent_id");
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, "parent_id");
    }
}

