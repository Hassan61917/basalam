<?php

namespace App\Models\Trait\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait DiscountScopes
{
    public function scopePublic(Builder $builder,): Builder
    {
        return $builder->whereNull(["shop_id"]);
    }

    public function scopeForClient(Builder $builder, int $clientId): Builder
    {
        return $builder->where("client_id", $clientId);
    }

    public function scopeForShop(Builder $builder, array $shopId): Builder
    {
        return $builder->whereIn('shop_id', $shopId);
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder
            ->whereNull("expire_at")
            ->orWhere("expire_at", ">", now());
    }
}
