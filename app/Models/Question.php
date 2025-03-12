<?php

namespace App\Models;

use App\Models\Trait\Relations\QuestionRelations;
use Illuminate\Database\Eloquent\Builder;

class Question extends AppModel
{
    use QuestionRelations;

    protected $fillable = [
        "shop_id", "product_id", "question", "answer"
    ];

    public function scopeAnswered(Builder $builder): Builder
    {
        return $builder->whereNotNull("answer");
    }
}
