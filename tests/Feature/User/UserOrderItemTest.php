<?php

namespace Tests\Feature\User;

use App\Enums\OrderItemStatus;
use App\Models\OrderItem;
use App\Models\Shop;
use Tests\UserTest;

class UserOrderItemTest extends UserTest
{
    public function test_index_should_see_shop_orders()
    {
        $this->makeItem(["status" => OrderItemStatus::Processed->value]);
        $this->makeItem([], Shop::factory()->create());
        $res = $this->getJson(route("v1.user.shop.items.index"));
        $this->assertCount(1, $res->json());
    }

    public function test_cancel_should_cancel_order_item()
    {
        $user = $this->makeUser();
        $item = $this->makeItem(["user_id" => $user->id]);
        $this->postJson(route("v1.user.shop.items.cancel", $item));

    }

    private function makeShop(array $data = []): Shop
    {
        return Shop::factory()->for($this->user)->create($data);
    }

    private function makeItem(array $data = [], ?Shop $shop = null): OrderItem
    {
        $shop = $shop ?: $this->makeShop();
        return OrderItem::factory()->for($shop)->create($data);
    }
}
