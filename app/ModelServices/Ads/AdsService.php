<?php

namespace App\ModelServices\Ads;

use App\Enums\ShowStatus;
use App\Events\AdsWasOrdered;
use App\Exceptions\ModelException;
use App\Models\Advertise;
use App\Models\AdvertiseOrder;
use App\Models\User;
use App\ModelServices\Financial\OrderItemService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdsService
{
    use OrderShowable;

    public function __construct(
        private OrderItemService $orderService,
    )
    {
    }

    public function getAdvertises(): Builder
    {
        return Advertise::query();
    }

    public function makeAds(array $data): Advertise
    {
        return Advertise::create($data);
    }

    public function getOrders(array $relations = []): Builder
    {
        return AdvertiseOrder::query()->with($relations);
    }

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->adsOrders()->with($relations);
    }

    public function getAvailableOrders(): Builder
    {
        return AdvertiseOrder::query()->showing()->with("ads");
    }

    public function makeOrder(User $user, array $data): AdvertiseOrder
    {
        $data["status"] = ShowStatus::Waiting->value;
        if ($user->adsOrders()->where([
            "ads_id" => $data["ads_id"],
            "status" => $data["status"]
        ])->exists()) {
            throw new ModelException("ads cannot be ordered");
        }
        $order = $user->adsOrders()->make($data);
        $this->updateShowingTime($order);
        $orderItem = $this->orderService->order($user, $order->toProduct());
        $order->update(["item_id" => $orderItem->id]);
        AdsWasOrdered::dispatch($order);
        return $order;
    }

    private function order(AdvertiseOrder $order): AdvertiseOrder
    {
        $this->updateShowingTime($order);
        return $order;
    }

    public function cancelOrder(AdvertiseOrder $order): AdvertiseOrder
    {
        $this->cancel($order);
        return $order;
    }

    public function showOrder(AdvertiseOrder $order): AdvertiseOrder
    {
        $this->show($order, $order->ads->duration);
        return $order;
    }


    public function completeOrder(AdvertiseOrder $order): AdvertiseOrder
    {
        $this->complete($order);
        return $order;
    }
}
