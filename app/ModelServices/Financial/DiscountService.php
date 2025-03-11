<?php

namespace App\ModelServices\Financial;


use App\Handlers\Discount\DiscountCreateHandler;
use App\Models\Discount;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountService
{
    public function __construct(
        private DiscountCreateHandler $discountHandler
    )
    {
    }

    public function create(User $user, array $data): Discount
    {
        return $user->discounts()->create($data);
    }

    public function makeFor(User $user, array $data): Discount
    {
        $discount = $user->discounts()->make($data);
        $this->discountHandler->handle($discount);
        return $this->create($user, $data);
    }

    public function getAll(array $relations = []): Builder
    {
        return Discount::query()->with($relations);
    }

    public function getAllFor(User $user, array $relations = []): HasMany
    {
        return $user->discounts()->with($relations);
    }

    public function getUsedDiscounts(User $user, array $relations = []): HasMany
    {
        return $user->usedDiscounts()->with($relations);
    }

    public function getMyDiscounts(User $user, array $relations = []): HasMany
    {
        return $user->myDiscounts()->with($relations);
    }

    public function expire(Discount $discount): void
    {
        $discount->update(['expired_at' => now()]);
    }
}
