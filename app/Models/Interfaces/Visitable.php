<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Visitable
{
    public function visits(): MorphMany;
}
