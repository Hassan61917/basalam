<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ReportCategory extends AppModel
{
    protected $fillable = [
        "name", "priority", "ban_duration"
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class,"category_id");
    }

    public function rule(): HasOne
    {
        return $this->hasOne(ReportRule::class, "category_id");
    }
}
