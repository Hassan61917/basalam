<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Advertise extends AppModel
{
    protected $fillable = [
        "title", "slug", "duration", "price"
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(AdvertiseOrder::class, "ads_id");
    }

    public function lastOrder(): ?AdvertiseOrder
    {
        return $this->hasOne(AdvertiseOrder::class, "ads_id")
            ->showing()
            ->latest()
            ->first();
    }
}
