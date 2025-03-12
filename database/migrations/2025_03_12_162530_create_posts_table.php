<?php

use App\Enums\PostStatus;
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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string("title");
            $table->String("slug")->unique();
            $table->text("body");
            $table->enum("status", EnumHelper::toArray(PostStatus::class))->default(PostStatus::Draft);
            $table->boolean("can_comment")->default(true);
            $table->timestamp("published_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
