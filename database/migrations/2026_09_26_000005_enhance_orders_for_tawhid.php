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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'public_order_id')) {
                $table->string('public_order_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('orders', 'delivery_type')) {
                $table->string('delivery_type')->default('delivery')->after('status');
                $table->index('delivery_type');
            }
            if (!Schema::hasColumn('orders', 'branch_id')) {
                $table->foreignId('branch_id')->nullable()->after('delivery_type')->constrained('branches')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'payment_proof_image')) {
                $table->string('payment_proof_image')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'instapay_reference')) {
                $table->string('instapay_reference')->nullable()->after('payment_proof_image');
            }
        });

        // Backfill existing orders with public_order_id
        $orders = DB::table('orders')->whereNull('public_order_id')->get();
        foreach ($orders as $order) {
            $date = date('Ymd', strtotime($order->created_at ?? 'now'));
            $publicId = 'TWH-' . $date . '-' . str_pad((string)$order->id, 4, '0', STR_PAD_LEFT);
            DB::table('orders')->where('id', $order->id)->update([
                'public_order_id' => $publicId,
                'delivery_type' => 'delivery',
            ]);
        }

        // Add unique index on public_order_id
        Schema::table('orders', function (Blueprint $table) {
            $table->unique('public_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'branch_id')) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');
            }
            $table->dropColumn([
                'public_order_id',
                'delivery_type',
                'payment_proof_image',
                'instapay_reference',
            ]);
        });
    }
};
