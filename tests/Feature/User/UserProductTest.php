<?php

namespace Tests\Feature\User;

use App\Enums\ShopStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Tests\UserTest;

class UserProductTest extends UserTest
{
    public function test_store_should_add_product_to_store()
    {
        $shop = $this->makeShop();
        $data = Product::factory()->for($shop->category)->raw();
        $this->postJson(route("v1.user.products.store"), $data);
        $this->assertCount(1, $shop->fresh()->products);
    }

    public function test_store_should_not_if_user_has_no_shop()
    {
        $data = Product::factory()->raw();
        $this->postJson(route("v1.user.products.store"), $data)
            ->assertStatus(403);
    }

    public function test_store_should_not_if_shop_is_suspended()
    {
        $this->makeShop(["status" => ShopStatus::Suspend->value]);
        $data = Product::factory()->raw();
        $this->postJson(route("v1.user.products.store"), $data)
            ->assertStatus(422);
    }

    public function test_store_should_not_if_product_category_is_not_child_of_shop_category()
    {
        $digital = Category::factory()->create(["name" => "digital"]);
        $dress = Category::factory()->create(["name" => "dress"]);
        $this->makeShop(["category_id" => $digital->id]);
        $data = Product::factory()->for($dress)->raw();
        $this->postJson(route("v1.user.products.store"), $data)
            ->assertStatus(422);
    }

    private function makeShop(array $data = []): Shop
    {
        return Shop::factory()->for($this->user)->create($data);
    }
}
