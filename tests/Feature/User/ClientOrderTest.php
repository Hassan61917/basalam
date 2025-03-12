<?php

namespace Tests\Feature\User;

use App\Enums\OrderStatus;
use App\Models\Order;
use Tests\UserTest;

class ClientOrderTest extends UserTest
{
    public function test_current()
    {
        $order1 = Order::factory()->for($this->user)->create();
        $res = $this->getJson(route("v1.user.orders.show",$order1));
        dd($res->json());
    }

    public function test_pay_should_pay_order()
    {
        $price = 1000;
        $order = Order::factory()->for($this->user)->create(["total_price" => $price]);
        $this->user->wallet()->update(["balance" => $price]);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.me.order.pay", $order));
        $this->assertEquals(0, $this->user->wallet->balance);
        $this->assertEquals($order->fresh()->status, OrderStatus::Paid->value);
    }

    public function test_cancel_should_cancel_order()
    {
        $order = Order::factory()->for($this->user)->create();
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.me.order.cancel", $order));
        $this->assertEquals($order->fresh()->status, OrderStatus::Cancelled->value);
    }

    public function test_cancel_should_return_price_if_order_is_paid()
    {
        $price = 1000;
        $order = Order::factory()->for($this->user)->create([
            "total_price" => $price,
            "status" => OrderStatus::Paid->value
        ]);
        $this->user->wallet()->update(["balance" => 0]);
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.me.order.cancel", $order));
        $this->assertEquals($price, $this->user->wallet->balance);
        $this->assertEquals($order->fresh()->status, OrderStatus::Cancelled->value);
    }

    public function test_cancel_should_not_cancel_if_order_is_complete()
    {
        $order = Order::factory()->for($this->user)->create([
            "status" => OrderStatus::Completed->value
        ]);
        $this->postJson(route("v1.me.order.cancel", $order))
            ->assertStatus(422);
    }
}
