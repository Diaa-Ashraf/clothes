<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;

class ProductVariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizeAttr = Attribute::where('code', 'size')->first();
        $colorAttr = Attribute::where('code', 'color')->first();

        if (!$sizeAttr || !$colorAttr) {
            return;
        }

        // Standard apparel sizes
        $sizes = AttributeValue::where('attribute_id', $sizeAttr->id)
            ->whereIn('value', ['S', 'M', 'L', 'XL', 'XXL'])
            ->get();

        // Standard apparel colors
        $colors = AttributeValue::where('attribute_id', $colorAttr->id)
            ->whereIn('value', ['أسود كلاسيك', 'أبيض ناصع', 'أزرق كحلي داكن', 'بيج فانيلا', 'زيتي ملكي'])
            ->get();

        $products = Product::all();

        foreach ($products as $product) {
            // Pick 2 to 3 sizes and 2 to 3 colors for each product
            $prodSizes = $sizes->random(min(3, $sizes->count()));
            $prodColors = $colors->random(min(2, $colors->count()));

            $totalStock = 0;

            foreach ($prodSizes as $size) {
                foreach ($prodColors as $color) {
                    $sku = ($product->sku ?: 'TAW-' . $product->id) . '-' . $size->value . '-' . substr(md5($color->value), 0, 3);
                    $stock = rand(5, 20);
                    $totalStock += $stock;

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => strtoupper($sku),
                        'barcode' => rand(6220000000000, 6229999999999),
                        'price' => $product->final_price ?? $product->price,
                        'stock_quantity' => $stock,
                        'is_active' => true,
                    ]);

                    $variant->attributeValues()->sync([$size->id, $color->id]);
                }
            }

            // Sync product total stock
            $product->update([
                'stock_quantity' => $totalStock,
                'quantity' => $totalStock,
                'is_available' => true,
            ]);
        }
    }
}
