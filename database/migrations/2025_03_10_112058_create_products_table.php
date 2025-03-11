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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("shop_id")->constrained("shops");
            $table->foreignId("category_id")->constrained("categories");
            $table->string("name");
            $table->text("description");
            $table->unsignedInteger("price");
            $table->boolean("available")->default(true);
            $table->boolean("hidden")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
