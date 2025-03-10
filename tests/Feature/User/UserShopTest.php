<?php

namespace Tests\Feature\User;

use App\Enums\ShopStatus;
use App\Models\Category;
use App\Models\Shop;
use Tests\UserTest;

class UserShopTest extends UserTest
{
    public function test_index_should_not_other_users_shop()
    {
        $shop1 = Shop::factory()->for($this->user)->create();
        $shop2 = Shop::factory()->create();
        $res = $this->getJson(route('v1.user.shop.index'));
        $res->assertSee($shop1->name);
        $res->assertDontSee($shop2->name);
    }

    public function test_store_should_create_new_shop()
    {
        $data = Shop::factory()->for($this->user)->raw();
        $this->postJson(route('v1.user.shop.store'), $data);
        $this->assertDatabaseHas("shops", $data);
    }

    public function test_store_should_not_create_if_user_has_shop()
    {
        Shop::factory()->for($this->user)->create();
        $data = Shop::factory()->for($this->user)->raw();
        $this->postJson(route('v1.user.shop.store'), $data)
            ->assertStatus(422);
        $this->assertDatabaseMissing("shops", $data);
    }

    public function test_update_should_update_shop()
    {
        $shop = Shop::factory()->for($this->user)->create();
        $data = ["name" => $shop->name . " updated"];
        $this->putJson(route('v1.user.shop.update'), $data);
        $this->assertEquals($shop->fresh()->name, $data["name"]);
    }

    public function test_update_should_not_update_if_user_has_no_shop()
    {
        $data = ["name" => "name updated"];
        $this->putJson(route('v1.user.shop.update'), $data)
            ->assertStatus(422);
    }

    public function test_update_should_not_update_category_if_shop_has_category()
    {
        $shop = Shop::factory()->for($this->user)->create();
        $data = [
            "name" => $shop->name . " updated",
            "category_id" => Category::factory()->create()->id
        ];
        $this->putJson(route('v1.user.shop.update'), $data);
        $this->assertEquals($shop->fresh()->name, $data["name"]);
        $this->assertNotEquals($shop->fresh()->category_id, $data["category_id"]);
    }

    public function test_destory_should_delete_shop()
    {
        Shop::factory()->for($this->user)->create();
        $this->deleteJson(route('v1.user.shop.destroy'));
        $this->assertDatabaseCount("shops", 0);
    }

    public function test_destory_should_not_delete_shop_if_user_has_no_shop()
    {
        $data = ["name" => "name updated"];
        $this->deleteJson(route('v1.user.shop.destroy'), $data)
            ->assertStatus(422);
    }

    public function test_destory_should_not_delete_shop_if_shop_is_in_process()
    {
        Shop::factory()->for($this->user)->create(["status" => ShopStatus::InProcess->value]);
        $this->deleteJson(route('v1.user.shop.destroy'))
            ->assertStatus(422);
        $this->assertDatabaseCount("shops", 1);
    }
}
