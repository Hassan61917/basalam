<?php

namespace Tests\Feature\User;

use App\Enums\OrderItemStatus;
use App\Models\Category;
use App\Models\Commission;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Tests\UserTest;

class ClientOrderItemTest extends UserTest
{
    public function test_order_should_create_order_if_user_has_no_order()
    {
        $product = $this->makeProduct($this->makeShop());
        $data = ["product_id" => $product->id];
        $this->postJson(route("v1.user.order-items.store"), $data);
        $this->assertDatabaseCount("orders", 1);
    }

    public function test_order_should_add_item_to_order_if_order_exists()
    {
        $order = $this->makeOrder();
        $products = Product::factory(2)->create();
        $this->postJson(route("v1.user.order-items.store"), ["product_id" => $products[0]->id]);
        $this->postJson(route("v1.user.order-items.store"), ["product_id" => $products[1]->id]);
        $this->assertCount(2, $order->fresh()->items);
    }

    public function test_order_should_increment_quantity_if_product_exists()
    {
        $quantity = 2;
        $order = $this->makeOrder();
        $product = $this->makeProduct($this->makeShop());
        $item = $this->makeItem([
            "product_id" => $product->id,
            "order_id" => $order->id,
            "total_price" => $product->price
        ]);
        $data = [
            "product_id" => $product->id,
            "quantity" => $quantity,
        ];
        $this->postJson(route("v1.user.order-items.store"), $data);
        $this->assertCount(1, $order->fresh()->items);
        $this->assertEquals($item->fresh()->quantity, $item->quantity * 3);
        $this->assertEquals($item->fresh()->total_price, $item->total_price * 3);
    }

    public function test_next_order_should_create_next_order()
    {
        $order = $this->makeOrder();
        $item = $this->makeItem(["order_id" => $order->id]);
        $this->assertDatabaseCount("orders", 1);
        $this->postJson(route("v1.user.order-items.next", $item));
        $this->assertDatabaseCount("orders", 2);
    }

    public function test_next_order_should_move_item_to_next_order()
    {
        $order1 = $this->makeOrder();
        $order2 = $this->makeOrder(["created_at" => now()->addMinute()]);
        $item = $this->makeItem(["order_id" => $order1->id]);
        $this->assertCount(1, $order1->fresh()->items);
        $this->assertCount(0, $order2->fresh()->items);
        $this->postJson(route("v1.user.order-items.next", $item));
        $this->assertCount(0, $order1->fresh()->items);
        $this->assertCount(1, $order2->fresh()->items);
    }

    public function test_cancel_should_cancel_order_item()
    {
        $total_price = 1000;
        $item = $this->makeItem([
            "status" => OrderItemStatus::Processed->value,
            "total_price" => $total_price,
        ]);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.order-items.cancel", $item));
        $this->assertEquals($this->user->wallet->balance, $total_price);
        $this->assertEquals($item->fresh()->status, OrderItemStatus::Cancelled->value);
    }

    public function test_cancel_should_not_cancel_order_item_twice()
    {
        $item = $this->makeItem(["status" => OrderItemStatus::Cancelled->value]);
        $this->postJson(route("v1.user.order-items.cancel", $item))
            ->assertStatus(422);
    }

    public function test_cancel_should_not_cancel_order_item_if_it_is_shipped()
    {
        $item = OrderItem::factory()->for($this->user)->create(["status" => OrderItemStatus::Shipped->value]);
        $this->postJson(route("v1.user.order-items.cancel", $item))
            ->assertStatus(422);
    }

    public function test_cancel_should_not_cancel_order_item_if_it_is_completed()
    {
        $item = $this->makeItem(["status" => OrderItemStatus::Completed->value]);
        $this->postJson(route("v1.user.order-items.cancel", $item))
            ->assertStatus(422);
    }

    public function test_cancel_should_deposit_amount_with_discount()
    {
        $total_price = 1000;
        $discount_price = 100;
        $item = $this->makeItem([
            "status" => OrderItemStatus::Accepted->value,
            "total_price" => 1000,
            "discount_price" => $discount_price,
        ]);
        $this->postJson(route("v1.user.order-items.cancel", $item));
        $this->assertEquals($this->user->wallet->balance, $total_price - $discount_price);
    }

    public function test_complete_should_complete_order_item()
    {
        $shop = $this->makeShop();
        $item = $this->makeItem([
            "shop_id" => $shop->id,
            "status" => OrderItemStatus::Shipped->value
        ]);
        $this->makeCommission($item->product->category);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.order-items.complete", $item));
        $this->assertEquals($item->fresh()->status, OrderItemStatus::Completed->value);
    }

    public function test_complete_should_deposit_amount_with_commission_to_shop_owner()
    {
        $price = 1000;
        $percent = 10;
        $shop = $this->makeShop();
        $product = $this->makeProduct($shop);
        $commission = $this->makeCommission($product->category, ["percent" => $percent]);
        $item = $this->makeItem([
            "shop_id" => $shop->id,
            "product_id" => $product->id,
            "status" => OrderItemStatus::Shipped->value,
            "total_price" => $price,
        ]);
        $this->postJson(route("v1.user.order-items.complete", $item));
        $amount = $commission->getAmount($item->total_price);
        $this->assertEquals($shop->user->wallet->balance, $price - $amount);
    }

    public function test_complete_should_deposit_total_amount_commission()
    {
        $price = 1000;
        $percent = 10;
        $max_amount = 100;
        $quantity = 2;
        $shop = $this->makeShop();
        $product = $this->makeProduct($shop, ["price" => $price]);
        $this->makeCommission($product->category, ["percent" => $percent, "max_amount" => $max_amount]);
        $item = $this->makeItem([
            "shop_id" => $shop->id,
            "product_id" => $product->id,
            "status" => OrderItemStatus::Shipped->value,
            "quantity" => $quantity,
            "total_price" => $price * $quantity
        ]);
        $this->postJson(route("v1.user.order-items.complete", $item));
        $amount = ($price * $quantity) - $max_amount;
        $this->assertEquals($shop->user->wallet->balance, $amount);
    }

    public function test_complete_should_deposit_amount_with_commission_with_system_discounts()
    {
        $price = 1000;
        $percent = 10;
        $discount_price = 200;
        $shop = $this->makeShop();
        $product = $this->makeProduct($shop);
        $commission = $this->makeCommission($product->category, ["percent" => $percent]);
        $item = $this->makeItem([
            "shop_id" => $shop->id,
            "product_id" => $product->id,
            "status" => OrderItemStatus::Shipped->value,
            "total_price" => $price,
            "discount_price" => $discount_price,
            "shop_discount" => false,
        ]);
        $this->postJson(route("v1.user.order-items.complete", $item));
        $amount = $commission->getAmount($item->total_price);
        $this->assertEquals($shop->user->wallet->balance, $price - $amount);
    }

    public function test_complete_should_deposit_amount_with_commission_with_shop_discounts()
    {
        $price = 1000;
        $percent = 10;
        $discount_price = 200;
        $shop = $this->makeShop();
        $product = $this->makeProduct($shop);
        $commission = $this->makeCommission($product->category, ["percent" => $percent]);
        $item = $this->makeItem([
            "shop_id" => $shop->id,
            "product_id" => $product->id,
            "status" => OrderItemStatus::Shipped->value,
            "total_price" => $price,
            "discount_price" => $discount_price,
            "shop_discount" => true,
        ]);
        $this->postJson(route("v1.user.order-items.complete", $item));
        $amount = ($price - $discount_price) - $commission->getAmount($item->total_price);
        $this->assertEquals($shop->user->wallet->balance, $amount);
    }

    private function makeShop(): Shop
    {
        return Shop::factory()->for($this->makeUser())->create();
    }

    private function makeProduct(Shop $shop, array $data = []): Product
    {
        return Product::factory()->for($shop)->create($data);
    }

    private function makeCommission(Category $category, array $data = []): Commission
    {
        return Commission::factory()
            ->for($category)
            ->create($data);
    }

    public function makeOrder(array $data = []): Order
    {
        return Order::factory()->for($this->user)->create($data);
    }

    private function makeItem(array $data = []): OrderItem
    {
        return OrderItem::factory()->for($this->user)->create($data);
    }
}
