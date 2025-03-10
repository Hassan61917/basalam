<?php

namespace App\ModelServices\Shop;

use App\Enums\ShopStatus;
use App\Events\ShopWasCreated;
use App\Events\ShopWasDeleted;
use App\Exceptions\ModelException;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ShopService
{
    public function getAllShop(array $relations = []): Builder
    {
        return Shop::query()->notDraft()->with($relations);
    }

    public function make(User $user, array $data)
    {
        $data["status"] = ShopStatus::Draft->value;
        if ($this->hasShop($user)) {
            throw new ModelException("you have already created a shop");
        }
        $shop = $user->shop()->create($data);
        ShopWasCreated::dispatch($shop);
        return $shop;
    }

    public function hasShop(User $user): bool
    {
        return $user->shop()->exists();
    }

    public function open(Shop $shop): void
    {
        if ($shop->status != ShopStatus::Closed->value) {
            return;
        }
        $this->updateStatus($shop, ShopStatus::Opened);
    }

    public function close(Shop $shop): void
    {
        if ($this->isInProcess($shop)) {
            throw new ModelException("shop can not be deleted");
        }
        $this->updateStatus($shop, ShopStatus::Closed);
    }

    public function suspend(Shop $shop, bool $suspend): void
    {
        if ($suspend && $this->isInProcess($shop)) {
            throw new ModelException("shop can not be suspended");
        }
        if (!$suspend && $shop->status != ShopStatus::Suspend->value) {
            return;
        }
        $status = $suspend ? ShopStatus::Suspend : ShopStatus::Opened;
        $this->updateStatus($shop, $status);
    }

    public function updateStatus(Shop $shop, ShopStatus $status): void
    {
        $shop->update(["status" => $status->value]);
    }

    public function update(Shop $shop, array $data): Shop
    {
        if ($shop->category_id != null) {
            $data['category_id'] = $shop->category_id;
        }
        $shop->update($data);
        return $shop;
    }

    public function delete(Shop $shop): void
    {
        if ($this->isInProcess($shop)) {
            throw new ModelException("shop can not be deleted");
        }
        ShopWasDeleted::dispatch($shop);
        $shop->delete();
    }

    private function isInProcess(Shop $shop): bool
    {
        return $shop->status == ShopStatus::InProcess->value;
    }
}
