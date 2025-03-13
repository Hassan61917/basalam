<?php

use App\Enums\ShowStatus;
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
        Schema::create('ladder_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("shop_id")->constrained("shops");
            $table->foreignId("ladder_id")->constrained("ladders");
            $table->foreignId("item_id")->nullable()->constrained("order_items");
            $table->enum("status", EnumHelper::toArray(ShowStatus::class))->default(ShowStatus::Waiting);
            $table->timestamp("show_at");
            $table->timestamp("end_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ladder_orders');
    }
};
