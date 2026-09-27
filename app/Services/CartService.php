<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Bundle;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'regalest_cart';

    /**
     * Get all items currently in cart
     */
    public function getItems(): array
    {
        return Session::get($this->sessionKey, []);
    }

    /**
     * Add single product to cart with support for custom portions, weights, and slicing notes
     */
    public function addProduct(
        int $productId, 
        int $quantity = 1, 
        ?string $portionType = 'piece', 
        ?float $customAmount = null, 
        ?string $notes = null
    ): array {
        $product = Product::active()->inStock()->find($productId);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('المنتج غير متوفر حالياً أو الكمية نفدت.'),
            ];
        }

        $portionType = $portionType ?: 'piece';
        $basePrice = (float)$product->final_price;
        $portionLabel = '';
        $unitPrice = $basePrice;

        // Calculate price and label according to portion type or custom amount
        if ($portionType === '125g') {
            $unitPrice = round($basePrice * 0.125, 2);
            $portionLabel = 'ثمن كيلو (125 جم)';
        } elseif ($portionType === '250g') {
            $unitPrice = round($basePrice * 0.25, 2);
            $portionLabel = 'ربع كيلو (250 جم)';
        } elseif ($portionType === '500g') {
            $unitPrice = round($basePrice * 0.5, 2);
            $portionLabel = 'نصف كيلو (500 جم)';
        } elseif ($portionType === '1000g' || $portionType === '1kg') {
            $unitPrice = $basePrice;
            $portionLabel = 'كيلو كامل (1000 جم)';
        } elseif ($portionType === 'custom_price' && $customAmount && $customAmount > 0) {
            $unitPrice = (float)$customAmount;
            $approxGrams = $basePrice > 0 ? round(($customAmount / $basePrice) * 1000) : 0;
            $portionLabel = "بمبلغ {$customAmount} ج.م (حوالي {$approxGrams} جم)";
        } else {
            $portionType = 'piece';
            $portionLabel = $product->is_weighted ? '1 كيلو' : 'قطعة / عبوة';
        }

        $cart = $this->getItems();
        
        // Generate distinct key per portion & notes to allow ordering both 1/4 kg and 1/2 kg of same product
        $suffix = $portionType !== 'piece' ? '_' . $portionType : '';
        if ($portionType === 'custom_price') {
            $suffix .= '_' . (int)$customAmount;
        }
        if (!empty($notes)) {
            $suffix .= '_' . md5(trim($notes));
        }

        $key = 'product_' . $productId . $suffix;
        $currentQty = isset($cart[$key]) ? (int)$cart[$key]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        // Stock checks for regular piece products
        if ($portionType === 'piece' && $product->available_stock <= 5 && $newQty > 1) {
            return [
                'success' => false,
                'message' => __('عذراً، هذا المنتج ذو كمية محدودة في المخزون (5 قطع أو أقل)، الحد الأقصى المسموح به هو قطعة واحدة فقط.'),
            ];
        }

        $cart[$key] = [
            'type' => 'product',
            'id' => $product->id,
            'name' => $product->name,
            'price' => $unitPrice,
            'original_price' => (float)$product->price,
            'image' => $product->image_url,
            'quantity' => $newQty,
            'portion_type' => $portionType,
            'portion_label' => $portionLabel,
            'custom_amount' => $customAmount,
            'notes' => $notes ? trim($notes) : null,
            'is_weighted' => (bool)$product->is_weighted,
            'available_stock' => (int)$product->available_stock,
            'is_limited_stock' => $product->available_stock <= 5,
            'total' => $unitPrice * $newQty,
        ];

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'message' => __('تمت إضافة :name (:portion) إلى السلة بنجاح.', ['name' => $product->name, 'portion' => $portionLabel]),
            'cart_count' => $this->getCount(),
            'item' => $cart[$key],
        ];
    }

    /**
     * Add bundle offer to cart
     */
    public function addBundle(int $bundleId): array
    {
        $bundle = Bundle::with('products')->active()->find($bundleId);

        if (!$bundle || !$bundle->is_valid) {
            return [
                'success' => false,
                'message' => __('هذا العرض المجمع غير متاح حالياً.'),
            ];
        }

        // Verify all products in bundle are in stock
        foreach ($bundle->products as $product) {
            if ($product->available_stock < 1) {
                return [
                    'success' => false,
                    'message' => __('عذراً، أحد منتجات هذا العرض (:name) غير متوفر حالياً.', ['name' => $product->name]),
                ];
            }
        }

        $cart = $this->getItems();
        $key = 'bundle_' . $bundle->id;
        $currentQty = isset($cart[$key]) ? (int)$cart[$key]['quantity'] : 0;
        $newQty = $currentQty + 1;

        $cart[$key] = [
            'type' => 'bundle',
            'id' => $bundle->id,
            'name' => $bundle->name,
            'price' => (float)$bundle->bundle_price,
            'original_price' => (float)$bundle->original_total,
            'image' => $bundle->image_url ?? $bundle->products->first()?->image_url,
            'quantity' => $newQty,
            'total' => (float)$bundle->bundle_price * $newQty,
            'products' => $bundle->products->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'image' => $p->image_url,
            ])->toArray(),
        ];

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'message' => __('تمت إضافة العرض المجمع إلى السلة بنجاح.'),
            'cart_count' => $this->getCount(),
            'item' => $cart[$key],
        ];
    }

    /**
     * Update item quantity
     */
    public function updateQuantity(string $itemKey, int $quantity): array
    {
        $cart = $this->getItems();

        if (!isset($cart[$itemKey])) {
            return [
                'success' => false,
                'message' => __('العنصر غير موجود في السلة.'),
            ];
        }

        if ($quantity <= 0) {
            return $this->removeItem($itemKey);
        }

        // Check stock if product
        if (($cart[$itemKey]['type'] ?? 'product') === 'product') {
            $product = Product::find($cart[$itemKey]['id']);
            if ($product) {
                // If product stock is 5 or less, maximum allowed quantity is 1
                if ($product->available_stock <= 5 && $quantity > 1) {
                    return [
                        'success' => false,
                        'message' => __('عذراً، هذا المنتج ذو كمية محدودة في المخزون (5 قطع أو أقل)، الحد الأقصى المسموح به هو قطعة واحدة فقط.'),
                    ];
                }

                if ($quantity > $product->available_stock) {
                    return [
                        'success' => false,
                        'message' => __('الكمية المتاحة في المخزون هي :count فقط', ['count' => $product->available_stock]),
                    ];
                }
            }
        }

        $cart[$itemKey]['quantity'] = $quantity;
        $cart[$itemKey]['total'] = (float)$cart[$itemKey]['price'] * $quantity;

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'message' => __('تم تحديث كمية السلة بنجاح.'),
            'totals' => $this->getTotals(),
        ];
    }

    /**
     * Get detailed items with live stock and limited stock checks
     */
    public function getDetailedItems(): array
    {
        $items = $this->getItems();
        $productIds = collect($items)->where('type', 'product')->pluck('id')->filter()->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $formattedItems = [];
        $cartModified = false;

        foreach ($items as $key => $item) {
            $priceUsd = (float)$item['price'];
            $stock = 999;
            $isProduct = ($item['type'] ?? 'product') === 'product';

            if ($isProduct) {
                $prod = $products->get($item['id']);
                $stock = $prod ? (int)$prod->available_stock : (int)($item['available_stock'] ?? 999);
            }

            $isLimitedStock = $isProduct && ($stock <= 5);

            // Safeguard: If product has limited stock and session quantity is > 1, enforce 1
            $quantity = (int)$item['quantity'];
            if ($isLimitedStock && $quantity > 1) {
                $quantity = 1;
                $items[$key]['quantity'] = 1;
                $items[$key]['total'] = $priceUsd;
                $cartModified = true;
            }

            $totalUsd = $priceUsd * $quantity;

            $formattedItems[] = [
                'key' => $key,
                'id' => $item['id'],
                'type' => $item['type'] ?? 'product',
                'name' => $item['name'] ?? '',
                'image' => $item['image'] ?? null,
                'quantity' => $quantity,
                'available_stock' => $stock,
                'is_limited_stock' => $isLimitedStock,
                'price_usd' => $priceUsd,
                'formatted_price_usd' => number_format($priceUsd, 2) . ' ج.م',
                'total_usd' => $totalUsd,
                'formatted_total_usd' => number_format($totalUsd, 2) . ' ج.م',
            ];
        }

        if ($cartModified) {
            Session::put($this->sessionKey, $items);
        }

        return $formattedItems;
    }

    /**
     * Remove item from cart
     */
    public function removeItem(string $itemKey): array
    {
        $cart = $this->getItems();

        if (isset($cart[$itemKey])) {
            unset($cart[$itemKey]);
            Session::put($this->sessionKey, $cart);
        }

        return [
            'success' => true,
            'message' => __('تم حذف العنصر من السلة.'),
            'cart_count' => $this->getCount(),
            'totals' => $this->getTotals(),
        ];
    }

    /**
     * Clear all cart contents
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey);
    }

    /**
     * Get total quantity of items in cart
     */
    public function getCount(): int
    {
        $count = 0;
        foreach ($this->getItems() as $item) {
            $count += (int)($item['quantity'] ?? 0);
        }
        return $count;
    }

    /**
     * Calculate delivery fee based on delivery type and subtotal
     */
    public function getDeliveryFee(?string $deliveryType = 'delivery', float $subtotal = 0.0): float
    {
        if ($deliveryType === 'pickup') {
            return 0.0;
        }

        $freeThreshold = (float) settings('free_delivery_threshold', 0.0);
        if ($freeThreshold > 0 && $subtotal >= $freeThreshold) {
            return 0.0;
        }

        return (float) settings('delivery_fee', 15.0);
    }

    /**
     * Calculate financial totals: subtotal, tax, delivery fee, grand total
     */
    public function getTotals(?string $deliveryType = 'delivery'): array
    {
        $subtotal = 0.0;
        foreach ($this->getItems() as $item) {
            $subtotal += (float)($item['total'] ?? 0);
        }

        $taxRate = (float) settings('tax_rate', 0); // Configurable, default 0%
        $taxAmount = $subtotal * ($taxRate / 100);
        $deliveryFee = $this->getDeliveryFee($deliveryType, $subtotal);
        $freeThreshold = (float) settings('free_delivery_threshold', 0.0);
        $grandTotal = $subtotal + $taxAmount + $deliveryFee;

        return [
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'delivery_fee' => $deliveryFee,
            'free_delivery_threshold' => $freeThreshold,
            'is_free_delivery' => ($deliveryType === 'pickup') || ($freeThreshold > 0 && $subtotal >= $freeThreshold),
            'delivery_type' => $deliveryType,
            'grand_total' => $grandTotal,
            'formatted_subtotal' => format_currency($subtotal),
            'formatted_tax' => format_currency($taxAmount),
            'formatted_delivery_fee' => $deliveryFee > 0 ? format_currency($deliveryFee) : __('مجاناً'),
            'formatted_total' => format_currency($grandTotal),
        ];
    }
}