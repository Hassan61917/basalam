<?php

namespace App\Models\Trait\Relations;

use App\Models\Ban;
use App\Models\Discount;
use App\Models\Profile;
use App\Models\Question;
use App\Models\Review;
use App\Models\Role;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait UserRelations
{
    use UserFinancialRelations;
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, "role_user");
    }
    public function banHistory(): HasMany
    {
        return $this->hasMany(Ban::class);
    }
    public function bannedUsers(): HasMany
    {
        return $this->hasMany(Ban::class, 'admin_id');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
