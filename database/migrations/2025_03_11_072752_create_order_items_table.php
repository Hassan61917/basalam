<?php

use App\Enums\OrderItemStatus;
use App\Utils\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("order_id")->constrained("orders");
            $table->foreignId("shop_id")->constrained("shops");
            $table->foreignId("product_id")->constrained("products");
            $table->unsignedInteger("quantity")->default(1);
            $table->unsignedInteger("total_price");
            $table->unsignedInteger("discount_price")->default(0);
            $table->boolean("shop_discount")->default(false);
            $table->enum("status", EnumHelper::toArray(OrderItemStatus::class))->default(OrderItemStatus::Waiting);
            $table->unique(["user_id", "product_id", "order_id"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
