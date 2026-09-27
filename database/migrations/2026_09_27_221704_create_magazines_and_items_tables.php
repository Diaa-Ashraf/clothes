<?php

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
        Schema::create('magazines', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('badge_text')->nullable()->default('إصدار مجلة التوفير الأسبوعية');
            $table->text('subtitle')->nullable();
            $table->string('branch_note')->nullable()->default('متاحة بجميع الفروع والتوصيل المنزلي');
            $table->string('cover_image')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('show_countdown')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('magazine_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magazine_id')->constrained('magazines')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('original_price', 10, 2);
            $table->decimal('deal_price', 10, 2);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->string('badge_text')->nullable()->default('عرض خاص');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['magazine_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magazine_items');
        Schema::dropIfExists('magazines');
    }
};
