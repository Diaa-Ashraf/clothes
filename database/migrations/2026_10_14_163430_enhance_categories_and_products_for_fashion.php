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
        // 1. Categories enhancements (Parent hierarchy, status, ordering)
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->index()->after('slug');
            }
            if (!Schema::hasColumn('categories', 'sort_order')) {
                $table->integer('sort_order')->default(0)->index()->after('is_active');
            }
        });

        // 2. Products enhancements (Brands, Gender, Season, Material, Fit, SKU)
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->after('category_id')->constrained('brands')->nullOnDelete();
            }
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->index()->after('name');
            }
            if (!Schema::hasColumn('products', 'gender')) {
                $table->string('gender')->nullable()->index()->after('brand_id'); // men, women, kids, unisex
            }
            if (!Schema::hasColumn('products', 'season')) {
                $table->string('season')->nullable()->index()->after('gender'); // summer, winter, all_season
            }
            if (!Schema::hasColumn('products', 'material')) {
                $table->string('material')->nullable()->after('season'); // e.g. قطن 100%, كتان, حرير
            }
            if (!Schema::hasColumn('products', 'fit')) {
                $table->string('fit')->nullable()->after('material'); // Regular, Slim, Oversized
            }
        });

        // 3. Product Variants (Size × Color matrix items)
        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('sku')->unique();
                $table->string('barcode')->nullable()->unique();
                $table->decimal('price', 10, 2)->nullable(); // override product base price if needed
                $table->integer('stock_quantity')->default(0);
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 4. Pivot between Product Variants and Attribute Values (e.g. Variant has Size M and Color Black)
        if (!Schema::hasTable('product_variant_values')) {
            Schema::create('product_variant_values', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
                $table->foreignId('attribute_value_id')->constrained('attribute_values')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['product_variant_id', 'attribute_value_id'], 'variant_attr_val_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variant_values');
        Schema::dropIfExists('product_variants');

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn(['brand_id', 'sku', 'gender', 'season', 'material', 'fit']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'is_active', 'sort_order']);
        });
    }
};
