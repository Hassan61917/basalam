<?php

use App\Enums\TicketStatus;
use App\Utils\EnumHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId("category_id")->constrained("ticket_categories");
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade");
            $table->string("title");
            $table->enum("status", EnumHelper::toArray(TicketStatus::class))->default(TicketStatus::Waiting);
            $table->timestamp("closed_at")->nullable();
            $table->unsignedInteger("rate")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
