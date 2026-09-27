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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_weighted')) {
                $table->boolean('is_weighted')->default(false)->after('featured')->comment('هل المنتج يباع بالوزن/المبلغ (أجبان، ألبان، لانشون)');
            }
            if (!Schema::hasColumn('products', 'unit_type')) {
                $table->string('unit_type')->default('piece')->after('is_weighted')->comment('piece, kg, gram, liter');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'portion_type')) {
                $table->string('portion_type')->nullable()->after('quantity')->comment('piece, 125g, 250g, 500g, 1000g, custom_price');
            }
            if (!Schema::hasColumn('order_items', 'portion_label')) {
                $table->string('portion_label')->nullable()->after('portion_type')->comment('مثال: ربع كيلو، بـ 20 جنيه، 500 جم');
            }
            if (!Schema::hasColumn('order_items', 'custom_amount')) {
                $table->decimal('custom_amount', 10, 2)->nullable()->after('portion_label')->comment('المبلغ المالي المخصص إن وجد');
            }
            if (!Schema::hasColumn('order_items', 'notes')) {
                $table->text('notes')->nullable()->after('custom_amount')->comment('ملاحظات التقطيع أو التجهيز');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_weighted', 'unit_type']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['portion_type', 'portion_label', 'custom_amount', 'notes']);
        });
    }
};
