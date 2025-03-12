<?php

namespace App\ModelServices\Financial;

use App\Enums\OrderItemStatus;
use App\Enums\OrderStatus;
use App\Events\OrderWasPaid;
use App\Handlers\Discount\DiscountHandler;
use App\Models\Discount;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderService
{
    public function __construct(
        private WalletService   $walletService,
        private DiscountHandler $discountHandler
    )
    {
    }

    public function getOrdersFor(User $user, array $relations = []): HasMany
    {
        return $user->orders()->with($relations);
    }

    public function getOrders(array $relations = [])
    {
        return Order::query()->with($relations);
    }

    public function draftOrders(User $user): HasMany
    {
        return $this->getOrdersFor($user)->draft()->latest();
    }

    public function currentOrder(User $user): Order
    {
        $order = $this->draftOrders($user)->first();
        return $order ?: $this->makeOrder($user);
    }

    public function nextOrder(User $user): Order
    {
        $draft = $this->draftOrders($user);
        if ($draft->count() < 2) {
            return $this->makeOrder($user);
        }
        return $this->draftOrders($user)->first();
    }

    public function makeOrder(User $user): Order
    {
        return $user->orders()->create();
    }

    public function pay(Order $order): Order
    {
        $amount = $order->getAmount();
        $this->walletService->withdraw($order->user->wallet, $amount);
        $order->update(["status" => OrderStatus::Paid->value]);
        $order->items()->update(["status" => OrderItemStatus::Accepted->value]);
        OrderWasPaid::dispatch($order);
        return $order;
    }

    public function applyDiscount(Order $order, string $code): Order
    {
        $discount = $this->findDiscount($code);
        $this->discountHandler->handle($discount, [$order]);
        $discount->users()->save($order->user);
        $this->applyToItems($order, $discount);
        $order->update([
            "discount_code" => $code,
            "discount_price" => $order->total_price - $discount->getValue($order->total_price)
        ]);
        return $order;
    }

    public function updateStatus(Order $order, OrderStatus $status): void
    {
        $order->update([
            "status" => $status->value
        ]);
    }

    public function findDiscount(string $code): Discount
    {
        return Discount::where('code', $code)->first();
    }

    private function applyToItems(Order $order, Discount $discount): void
    {
        $item = $order->items()->where("shop_id", $discount->shop_id)->first();
        $items = $item ? [$item] : $order->items;
        $discount_value = $item ? $discount->getValue($item->total_price) : $discount->getValue($order->total_price) / $items->count();
        $this->updateDiscountItems($items, $discount_value, $item != null);
    }

    private function updateDiscountItems(Arrayable $items, int $value, bool $shop_discount): void
    {
        foreach ($items as $item) {
            $item->update([
                "discount_price" => $value,
                "shop_discount" => $shop_discount
            ]);
        }
    }
}
