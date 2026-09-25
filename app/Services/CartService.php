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
     * Add single product to cart
     */
    public function addProduct(int $productId, int $quantity = 1): array
    {
        $product = Product::active()->inStock()->find($productId);

        if (!$product) {
            return [
                'success' => false,
                'message' => __('المنتج غير متوفر حالياً أو الكمية نفدت.'),
            ];
        }

        $cart = $this->getItems();
        $key = 'product_' . $productId;
        $currentQty = isset($cart[$key]) ? (int)$cart[$key]['quantity'] : 0;
        $newQty = $currentQty + $quantity;

        // If product stock is 5 or less, allow only 1 piece max per customer
        if ($product->available_stock <= 5 && $newQty > 1) {
            return [
                'success' => false,
                'message' => __('عذراً، هذا المنتج ذو كمية محدودة في المخزون (5 قطع أو أقل)، الحد الأقصى المسموح به هو قطعة واحدة فقط.'),
            ];
        }

        if ($newQty > $product->available_stock) {
            return [
                'success' => false,
                'message' => __('الكمية المتاحة في المخزون هي :count فقط', ['count' => $product->available_stock]),
            ];
        }

        $price = (float)$product->final_price;

        $cart[$key] = [
            'type' => 'product',
            'id' => $product->id,
            'name' => $product->name,
            'price' => $price,
            'original_price' => (float)$product->price,
            'image' => $product->image_url,
            'quantity' => $newQty,
            'available_stock' => (int)$product->available_stock,
            'is_limited_stock' => $product->available_stock <= 5,
            'total' => $price * $newQty,
        ];

        Session::put($this->sessionKey, $cart);

        return [
            'success' => true,
            'message' => __('تمت إضافة المنتج إلى السلة بنجاح.'),
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
                'formatted_price_usd' => '$' . number_format($priceUsd, 2),
                'total_usd' => $totalUsd,
                'formatted_total_usd' => '$' . number_format($totalUsd, 2),
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
     * Calculate financial totals: subtotal, tax, grand total
     */
    public function getTotals(): array
    {
        $subtotal = 0.0;
        foreach ($this->getItems() as $item) {
            $subtotal += (float)($item['total'] ?? 0);
        }
    

        $taxRate = (float) settings('tax_rate', 0); // Configurable, default 0%
        $taxAmount = $subtotal * ($taxRate / 100);
        $grandTotal = $subtotal + $taxAmount;

        return [
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
            'formatted_subtotal' => format_currency($subtotal),
            'formatted_tax' => format_currency($taxAmount),
            'formatted_total' => format_currency($grandTotal),
        ];
    }
}