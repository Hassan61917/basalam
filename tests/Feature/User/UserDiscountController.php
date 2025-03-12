<?php

namespace Tests\Feature\User;

use App\Models\Category;
use App\Models\Commission;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Shop;
use Tests\UserTest;

class UserDiscountController extends UserTest
{
    public function test_store_should_store_discount_for_the_shop()
    {
        $shop = $this->makeShop();
        $discount = Discount::factory()->for($shop)->raw();
        $this->postJson(route("v1.user.shop.discounts.store"), $discount);
        $this->assertCount(1, $shop->fresh()->discounts);
    }

    public function test_store_should_not_store_if_user_has_no_shop()
    {
        $discount = Discount::factory()->raw();
        $this->postJson(route("v1.user.shop.discounts.store"), $discount)
            ->assertStatus(403);
    }

    public function test_store_should_not_store_if_shop_has_product()
    {
        $shop = $this->makeShop();
        $product = Product::factory()->for($shop)->create();
        $discount = Discount::factory()
            ->for($shop)
            ->for($product)
            ->raw();
        $this->withoutExceptionHandling();
        $this->postJson(route("v1.user.shop.discounts.store"), $discount);
        $this->assertCount(1, $shop->fresh()->discounts);
    }

    public function test_store_should_not_store_if_shop_does_not_product()
    {
        $shop = $this->makeShop();
        $product = Product::factory()->create();
        $discount = Discount::factory()
            ->for($shop)
            ->for($product)
            ->raw();
        $this->postJson(route("v1.user.shop.discounts.store"), $discount)
            ->assertStatus(422);
    }
    public function test_store_should_not_store_amount_is_more_then_commission()
    {
        $percent = 10;
        $price = 1000;
        $category = Category::factory()->create();
        Commission::factory()->for($category)->create(["percent" => $percent]);
        $shop = $this->makeShop();
        $product = Product::factory()->for($category)->for($shop)->create(["price" => $price]);
        $discount = Discount::factory()
            ->for($shop)
            ->for($product)
            ->raw(["percent" => (100 - $percent) + 1]);

        $this->postJson(route("v1.user.shop.discounts.store"), $discount)
            ->assertStatus(422);
    }
    public function test_store_should_not_store_if_category_is_not_same()
    {
        $category1 = Category::factory()->create();
        $shop = $this->makeShop(["category_id" => $category1->id]);
        $product = Product::factory()
            ->for($category1)
            ->for($shop)
            ->create();
        $category2 = Category::factory()->create();
        $discount = Discount::factory()
            ->for($shop)
            ->for($product)
            ->for($category2)
            ->raw();
        $this->postJson(route("v1.user.shop.discounts.store"), $discount)
            ->assertStatus(422);
    }

    private function makeShop(array $data = []): Shop
    {
        return Shop::factory()->for($this->user)->create($data);
    }
}

