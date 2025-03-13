<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Models\Trait\Relations\QuestionRelations;
use App\Models\Trait\With\WithLike;
use Illuminate\Database\Eloquent\Builder;

class Question extends AppModel implements Likeable
{
    use QuestionRelations,
        WithLike;

    protected $fillable = [
        "shop_id", "product_id", "question", "answer"
    ];

    public function scopeAnswered(Builder $builder): Builder
    {
        return $builder->whereNotNull("answer");
    }
}
