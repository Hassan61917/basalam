<?php

namespace App\Models\Trait\Relations;

use App\Models\Page;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserSocialRelations
{
    public function page(): HasOne
    {
        return $this->hasOne(Page::class);
    }
}
