<?php

namespace App\ModelServices\Ads;

use App\Enums\ShowStatus;
use App\Events\LadderWasOrdered;
use App\Exceptions\ModelException;
use App\Handlers\LadderOrder\LadderOrderHandler;
use App\Models\Ladder;
use App\Models\LadderOrder;
use App\Models\Shop;
use App\Models\User;
use App\ModelServices\Financial\OrderItemService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LadderService
{
    use OrderShowable;

    public function __construct(
        private OrderItemService $orderService,
        private LadderOrderHandler $orderHandler
    )
    {
    }

    public function getLadders(array $relations = []): Builder
    {
        return Ladder::query()->with($relations);
    }

    public function makeLadder(array $data): Ladder
    {
        return Ladder::create($data);
    }

    public function getOrders(array $relations = []): Builder
    {
        return LadderOrder::query()->with($relations);
    }

    public function getAvailableOrders(array $relations = []): Builder
    {
        return LadderOrder::showing()->with($relations);
    }

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->ladderOrders()->with($relations);
    }

    public function makeOrder(User $user, array $data): LadderOrder
    {
        $data["status"] = ShowStatus::Waiting->value;
        $order = $user->ladderOrders()->make($data);
        $this->orderHandler->handle($order);
        $this->updateShowingTime($order);
        $orderItem = $this->orderService->order($user, $order->toProduct());
        $order->update(["item_id" => $orderItem->id]);
        LadderWasOrdered::dispatch($order);
        return $order;
    }

    public function cancelOrder(LadderOrder $order): LadderOrder
    {
        $this->cancel($order);
        return $order;
    }

    public function showOrder(LadderOrder $order): LadderOrder
    {
        $this->show($order, $order->ladder->duration);
        return $order;
    }

    public function completeOrder(LadderOrder $order): LadderOrder
    {
        $this->complete($order);
        return $order;
    }
}
