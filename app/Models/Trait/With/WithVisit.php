<?php

namespace App\Models\Trait\With;

use App\Models\Visit;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait WithVisit
{
    public function visits(): MorphMany
    {
        return $this->morphMany(Visit::class, 'visitable');
    }
}
