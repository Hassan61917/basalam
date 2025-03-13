<?php

namespace App\Models;

use App\Enums\ShopStatus;
use App\Models\Trait\Relations\ShopRelations;
use Illuminate\Database\Eloquent\Builder;

class Shop extends AppModel
{
    use ShopRelations;

    protected $fillable = [
        "category_id", "name", "description", "status"
    ];

    public static function systemShop(): Shop
    {
        $admin = User::query()
            ->whereHas("roles", fn($query) => $query->where("title", "admin"))
            ->first();

        return $admin->shop()->firstOrCreate(["name" => "system shop", "status" => ShopStatus::Opened->value]);
    }
    public function scopeNotDraft(Builder $builder): Builder
    {
        return $builder->where("status","!=", ShopStatus::Draft->value);
    }
    public function scopeAvailable(Builder $builder): Builder
    {
        return $builder->whereIn("status",[ShopStatus::Opened->value,ShopStatus::InProcess->value]);
    }
    public function scopeIsOpen(Builder $builder): Builder
    {
        return $builder->where('status', ShopStatus::Opened->value);
    }

    public function isAvailable(): bool
    {
        $status = $this->status;
        return $status == ShopStatus::Opened->value ||
            $status == ShopStatus::InProcess->value;
    }
}
