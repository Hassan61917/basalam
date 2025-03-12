<?php

namespace Tests\Feature\User;

use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use Tests\UserTest;

class ClientDiscountTest extends UserTest
{
    public function test_apply_should_apply_discount_to_order()
    {
        $price = 1000;
        $discountAmount = 100;
        $order = $this->makeOrder($price);
        $discount = $this->makeDiscount(["amount" => $discountAmount]);
        $data = ["code" => $discount->code];
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.order.discount", $order), $data);
        $this->assertEquals($order->fresh()->discount_code, $discount->code);
        $this->assertEquals($order->fresh()->discount_price, $price - $discountAmount);
    }

    public function test_apply_should_not_apply_discount_if_discount_is_expired()
    {
        $order = $this->makeOrder();
        $discount = $this->makeDiscount(["expired_at" => now()->subHour()]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.user.order.discount", $order), $data)
            ->assertStatus(422);
    }

    public function test_apply_should_not_apply_discount_if_client_is_not_same()
    {
        $order = $this->makeOrder();
        $discount = $this->makeDiscount(["client_id" => $this->makeUser()->id]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.user.order.discount", $order), $data)
            ->assertStatus(422);
    }

    public function test_apply_should_not_apply_discount_if_client_reached_limit()
    {
        $order = $this->makeOrder();
        $discount = $this->makeDiscount(["limit" => 1]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.user.order.discount", $order), $data);
        $this->postJson(route("v1.user.order.discount", $order), $data)
            ->assertStatus(422);
    }

    public function test_apply_should_not_apply_discount_if_discount_balance_price_is_less_then_order_total_price()
    {
        $order = $this->makeOrder();
        $discount = $this->makeDiscount(["total_balance" => $order->total_price + 1]);
        $data = ["code" => $discount->code];
        $this->postJson(route("v1.user.order.discount", $order), $data)
            ->assertStatus(422);
    }

    public function test_should_apply_discount_for_discount_shop()
    {
        $shop = Shop::factory()->create();
        $discount_amount = 100;
        $discount = $this->makeDiscount([
            "shop_id" => $shop->id,
            "amount" => $discount_amount,
        ]);
        $total_price = 1000;
        $order = $this->makeOrder($total_price);
        $item = OrderItem::factory()
            ->for($order)
            ->for($shop)
            ->create(["total_price" => $total_price]);

        $data = ["code" => $discount->code];
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.order.discount", $order), $data);
        $this->assertEquals($item->fresh()->discount_price, $discount_amount);
        $this->assertTrue($item->fresh()->shop_discount);
    }

    public function test_should_not_apply_for_other_discount_shop()
    {
        $shop = Shop::factory()->create();
        $discount_amount = 100;
        $discount = $this->makeDiscount([
            "shop_id" => $shop->id,
            "amount" => $discount_amount,
        ]);
        $total_price = 1000;
        $order = $this->makeOrder($total_price);
        OrderItem::factory()
            ->for($order)
            ->for($shop)
            ->create(["total_price" => $total_price]);

        $item2 = OrderItem::factory()
            ->for($order)
            ->create(["total_price" => $total_price]);

        $data = ["code" => $discount->code];
        $this->postJson(route("v1.user.order.discount", $order), $data);
        $this->assertEquals(0, $item2->fresh()->discount_price);
        $this->assertFalse($item2->fresh()->shop_discount);
    }

    public function test_should_apply_for_all_item_if_discount_has_no_shop()
    {
        $shop = Shop::factory()->create();
        $discount_amount = 100;
        $discount = $this->makeDiscount([
            "shop_id" => $shop->id,
            "amount" => $discount_amount,
        ]);
        $total_price = 1000;
        $order = $this->makeOrder($total_price);
        $items = OrderItem::factory(2)->for($order)->create(["total_price" => $total_price]);
        $data = ["code" => $discount->code];
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.order.discount", $order), $data);
        $discount_value = $discount_amount / $items->count();
        $this->assertEquals($items[0]->fresh()->discount_price, $discount_value);
        $this->assertEquals($items[1]->fresh()->discount_price, $discount_value);
    }

    private function makeOrder(int $price = 1000): Order
    {
        return Order::factory()
            ->for($this->user)
            ->create(["total_price" => $price]);
    }

    private function makeDiscount(array $data = []): Discount
    {
        return Discount::factory()->create($data);
    }
}
