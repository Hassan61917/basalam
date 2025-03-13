<?php

namespace App\Models\Trait\With;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait WithLike
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')
            ->where("isLike", true);
    }

    public function dislikes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable')
            ->where("isLike", false);
    }
}
