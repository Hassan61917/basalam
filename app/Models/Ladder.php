<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ladder extends AppModel
{
    protected $fillable = [
        "category_id", "duration", "price"
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(LadderOrder::class);
    }

    public function lastOrder(): ?LadderOrder
    {
        $take = 5;
        $orders = $this->orders()->showing()->latest()->take($take);
        return $orders->count() == $take ? $orders->first() : null;
    }
}

