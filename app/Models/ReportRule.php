<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportRule extends AppModel
{
    protected $fillable = ["count", "duration"];
    public function category():BelongsTo
    {
        return $this->belongsTo(ReportCategory::class,"category_id");
    }
}
