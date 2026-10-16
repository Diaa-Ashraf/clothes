<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Adjust stock for a product or specific variant, and append an immutable movement log.
     */
    public function adjustStock(
        int $productId,
        ?int $variantId,
        int $delta,
        string $type = 'adjustment',
        ?string $reason = null,
        ?int $branchId = null,
        ?int $userId = null
    ): StockMovement {
        return DB::transaction(function () use ($productId, $variantId, $delta, $type, $reason, $branchId, $userId) {
            $product = Product::findOrFail($productId);
            $quantityAfter = 0;

            if ($variantId) {
                $variant = ProductVariant::where('product_id', $product->id)->findOrFail($variantId);
                $newVarStock = max(0, (int)$variant->stock_quantity + $delta);
                $variant->update(['stock_quantity' => $newVarStock]);

                // Recalculate parent product total stock
                $totalStock = (int)ProductVariant::where('product_id', $product->id)->sum('stock_quantity');
                $product->update([
                    'stock_quantity' => $totalStock,
                    'quantity' => $totalStock,
                    'is_available' => $totalStock > 0,
                ]);

                $quantityAfter = $newVarStock;
            } else {
                $newStock = max(0, (int)$product->stock_quantity + $delta);
                $product->update([
                    'stock_quantity' => $newStock,
                    'quantity' => $newStock,
                    'is_available' => $newStock > 0,
                ]);

                $quantityAfter = $newStock;
            }

            return StockMovement::create([
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'branch_id' => $branchId,
                'type' => $type,
                'quantity_delta' => $delta,
                'quantity_after' => $quantityAfter,
                'reason' => $reason,
                'created_by' => $userId ?? auth()->id(),
            ]);
        });
    }

    /**
     * Get aggregate inventory summary metrics.
     */
    public function getInventoryMetrics(): array
    {
        $totalVariants = ProductVariant::count();
        $totalStockUnits = (int)Product::sum('stock_quantity');
        $lowStockCount = Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5)->count();
        $outOfStockCount = Product::where('stock_quantity', '<=', 0)->count();

        return [
            'total_units' => $totalStockUnits,
            'total_variants' => $totalVariants,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
        ];
    }
}
