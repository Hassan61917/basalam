<?php

namespace Admin;

use App\Enums\ShopStatus;
use App\Models\Shop;
use Tests\AdminTest;

class AdminShopTest extends AdminTest
{
    public function test_index_should_not_draft_shops()
    {
        $shop1 = Shop::factory()->create(["status" => ShopStatus::Draft->value]);
        $shop2 = Shop::factory()->create(["status" => ShopStatus::Opened->value]);
        $res = $this->getJson(route("v1.admin.shops.index"));
        $res->assertDontSee($shop1->name);
        $res->assertSee($shop2->name);
    }

    public function test_suspend_should_suspend_shop()
    {
        $shop = Shop::factory()->create(["status" => ShopStatus::Opened->value]);
        $this->postJson(route("v1.admin.shops.suspend", $shop));
        $this->assertEquals($shop->fresh()->status, ShopStatus::Suspend->value);
    }

    public function test_suspend_should_not_suspend_if_shop_is_in_process()
    {
        $shop = Shop::factory()->create(["status" => ShopStatus::InProcess->value]);
        $this->postJson(route("v1.admin.shops.suspend", $shop))
            ->assertStatus(422);
        $this->assertEquals($shop->fresh()->status, ShopStatus::InProcess->value);
    }

    public function test_suspend_should_unsuspend_shop()
    {
        $shop = Shop::factory()->create(["status" => ShopStatus::Suspend->value]);
        $this->postJson(route("v1.admin.shops.unsuspend", $shop));
        $this->assertEquals($shop->fresh()->status, ShopStatus::Opened->value);
    }

    public function test_suspend_should_not_unsuspend_shop_if_shop_has_not_been_suspended()
    {
        $shop = Shop::factory()->create(["status" => ShopStatus::Closed->value]);
        $this->postJson(route("v1.admin.shops.unsuspend", $shop));
        $this->assertEquals($shop->fresh()->status, ShopStatus::Closed->value);
    }
}
