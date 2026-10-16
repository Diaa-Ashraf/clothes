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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('reviewer_name')->nullable()->after('user_id');
            $table->string('governorate')->nullable()->after('reviewer_name');
            $table->boolean('is_featured')->default(true)->after('approved');
            $table->index(['approved', 'is_featured']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['approved', 'is_featured']);
            $table->dropColumn(['reviewer_name', 'governorate', 'is_featured']);
        });
    }
};
