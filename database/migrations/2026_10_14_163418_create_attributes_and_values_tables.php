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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. المقاس, اللون, الخامة, القصة
            $table->string('code')->unique(); // e.g. size, color, fabric, fit
            $table->string('type')->default('select'); // select, color, button, text
            $table->boolean('is_filterable')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_id')->constrained('attributes')->cascadeOnDelete();
            $table->string('value'); // e.g. S, M, L, XL or أسود, كحلي, بيج
            $table->string('color_code')->nullable(); // e.g. #000000, #1B2A47
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();

            $table->unique(['attribute_id', 'value']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
        Schema::dropIfExists('attributes');
    }
};
