<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add fields to categories table if they don't exist
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'name')) {
                $table->string('name')->default('')->after('id');
            }
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
        });

        // 2. Add fields to products table if they don't exist
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'name')) {
                $table->string('name')->default('')->after('id');
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'keywords')) {
                $table->json('keywords')->nullable()->after('description');
            }
        });

        // 3. Migrate category translations into categories table
        if (Schema::hasTable('category_translations')) {
            $categories = DB::table('categories')->get();
            foreach ($categories as $category) {
                $trans = DB::table('category_translations')
                    ->where('category_id', $category->id)
                    ->where('locale', 'ar')
                    ->first();

                if (!$trans) {
                    $trans = DB::table('category_translations')
                        ->where('category_id', $category->id)
                        ->first();
                }

                if ($trans) {
                    DB::table('categories')->where('id', $category->id)->update([
                        'name' => $trans->name ?? '',
                        'description' => $trans->description ?? null,
                    ]);
                }
            }
        }

        // 4. Migrate product translations into products table
        if (Schema::hasTable('product_translations')) {
            $products = DB::table('products')->get();
            foreach ($products as $product) {
                $trans = DB::table('product_translations')
                    ->where('product_id', $product->id)
                    ->where('locale', 'ar')
                    ->first();

                if (!$trans) {
                    $trans = DB::table('product_translations')
                        ->where('product_id', $product->id)
                        ->first();
                }

                if ($trans) {
                    // Check if keywords is already json or string
                    $keywords = $trans->keywords;
                    DB::table('products')->where('id', $product->id)->update([
                        'name' => $trans->name ?? '',
                        'description' => $trans->description ?? null,
                        'keywords' => $keywords,
                    ]);
                }
            }
        }

        // 5. Drop foreign keys and translation tables
        if (Schema::hasTable('category_translations')) {
            Schema::dropIfExists('category_translations');
        }

        if (Schema::hasTable('product_translations')) {
            Schema::dropIfExists('product_translations');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate category_translations table
        if (!Schema::hasTable('category_translations')) {
            Schema::create('category_translations', function (Blueprint $table) {
                $table->id();
                $table->string('locale')->index();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->unique(['category_id', 'locale']);
            });

            // Backfill AR from categories
            $categories = DB::table('categories')->get();
            foreach ($categories as $cat) {
                DB::table('category_translations')->insert([
                    'category_id' => $cat->id,
                    'locale' => 'ar',
                    'name' => $cat->name,
                    'description' => $cat->description,
                ]);
            }
        }

        // Recreate product_translations table
        if (!Schema::hasTable('product_translations')) {
            Schema::create('product_translations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('locale')->index();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->json('keywords')->nullable();
                $table->unique(['product_id', 'locale']);
            });

            // Backfill AR from products
            $products = DB::table('products')->get();
            foreach ($products as $prod) {
                DB::table('product_translations')->insert([
                    'product_id' => $prod->id,
                    'locale' => 'ar',
                    'name' => $prod->name,
                    'description' => $prod->description,
                    'keywords' => $prod->keywords,
                ]);
            }
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'keywords']);
        });
    }
};
