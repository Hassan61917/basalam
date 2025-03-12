<?php

namespace Database\Factories;

use App\Enums\OrderItemStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends AppFactory
{
    public function definition(): array
    {
        $product = Product::factory()->create();
        return [
            "user_id" => User::factory(),
            "order_id" => Order::factory(),
            "shop_id" => $product->shop_id,
            "product_id" => $product->id,
            "status" => OrderItemStatus::Waiting->value,
            "quantity" => 1,
            "total_price" => $product->price,
            "discount_price" => 0,
            "shop_discount" => false
        ];
    }
}
