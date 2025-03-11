<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->text("description");
            $table->string("code")->unique();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("category_id")->nullable()->constrained("categories");
            $table->foreignId("client_id")->nullable()->constrained("users");
            $table->foreignId("shop_id")->nullable()->constrained("shops");
            $table->foreignId("product_id")->nullable()->constrained("products");
            $table->unsignedInteger("limit")->default(1);
            $table->unsignedInteger("amount")->default(0);
            $table->unsignedInteger("percent")->default(0);
            $table->unsignedInteger("total_balance")->default(0);
            $table->unsignedInteger("max_amount")->nullable();
            $table->timestamp("expired_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
