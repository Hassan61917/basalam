<?php

namespace App\ModelServices\Financial;

use App\Enums\OrderItemStatus;
use App\Exceptions\ModelException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItemService
{
    public function __construct(
        private OrderService  $orderService,
        private WalletService $walletService
    )
    {
    }

    public function getItemsFor(Shop $shop, array $relations = []): HasMany
    {
        return $shop->orderItems()->visible()->with($relations);
    }

    public function getOrder(User $user): Order
    {
        return $this->orderService->currentOrder($user);
    }

    public function order(User $user, Product $product, int $quantity = 1): OrderItem
    {
        $order = $this->getOrder($user);
        return $this->addItem($order, $product, $quantity);
    }

    public function moveToNext(User $user, OrderItem $item): void
    {
        $order = $this->orderService->nextOrder($user);
        $order->items()->save($item);
    }

    private function addItem(Order $order, Product $product, int $quantity = 1): OrderItem
    {
        $item = $order->item($order->user->id, $product->id)->first();
        $total_price = $product->price * $quantity;
        if ($item) {
            $item->increment("quantity", $quantity);
            $item->increment("total_price", $total_price);
            return $item;
        }
        return $order->items()->create([
            "user_id" => $order->user->id,
            "shop_id" => $product->shop_id,
            "product_id" => $product->id,
            "total_price" => $total_price,
            "quantity" => $quantity,
            "status" => OrderItemStatus::Waiting->value
        ]);
    }

    public function cancel(OrderItem $item, ?int $amount = null): void
    {
        $amount = $amount ?: $item->total_price;
        if (!$this->canCancel($item)) {
            throw new ModelException("item can not be canceled");
        }
        if ($item->status != OrderItemStatus::Waiting->value) {
            $amount -= $item->discount_price;
            $this->walletService->deposit($item->user->wallet, $amount);
        }
        $item->updateStatus(OrderItemStatus::Cancelled);
    }

    public function confirm(OrderItem $item): void
    {
        if ($item->status != OrderItemStatus::Processed->value) {
            throw new ModelException("item can not be confirmed");
        }
        $item->updateStatus(OrderItemStatus::Accepted);
    }

    public function ship(OrderItem $item): void
    {
        if ($item->status != OrderItemStatus::Accepted->value) {
            throw new ModelException("item can not be confirmed");
        }
        $item->updateStatus(OrderItemStatus::Shipped);
    }

    public function complete(User $user, OrderItem $item): void
    {
        if (!$user->is($item->user)) {
            throw new AuthorizationException("only buyer can complete order");
        }
        if ($item->status != OrderItemStatus::Shipped->value) {
            throw new ModelException("item can not be completed");
        }
        $commission = $item->product->category->getLastParent()->commission;
        if ($item->shop_discount) {
            $amount = $item->total_price - $item->discount_price;
        } else {
            $amount = $item->total_price;
        }
        $amount -= $commission->getAmount($item->total_price);
        $this->walletService->deposit($item->shop->user->wallet, $amount);
        $item->updateStatus(OrderItemStatus::Completed);
    }

    public function canCancel(OrderItem $item): bool
    {
        $status = $item->status;
        return $status == OrderItemStatus::Waiting->value ||
            $status == OrderItemStatus::Processed->value ||
            $status == OrderItemStatus::Accepted->value;
    }

}
