<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class OrderService
{
    /**
     * Create an order from cart items, check stock, deduct inventory and save customer info
     */
    public function createOrder(array $customerData, array $cartItems, array $totals, ?User $user = null): Order
    {
        return DB::transaction(function () use ($customerData, $cartItems, $totals, $user) {
            $currency = get_active_currency();
            $exchangeRate = get_exchange_rate();
            $customerName = trim($customerData['name'] ?? '') ?: ($user?->name ?? __('عميل مباشر'));

            // 1. Validate stock availability before making any changes
            foreach ($cartItems as $item) {
                if (($item['type'] ?? 'product') === 'product') {
                    $product = Product::lockForUpdate()->find($item['id']);
                    if (!$product) {
                        throw new Exception(__('المنتج المطلوب لم يعد متوفراً.'));
                    }
                    if ($product->available_stock <= 5 && $item['quantity'] > 1) {
                        throw new Exception(__('المنتج ":product" ذو كمية محدودة بالمخزون (5 قطع أو أقل)، الحد الأقصى المسموح به هو قطعة واحدة فقط.', [
                            'product' => $product->name,
                        ]));
                    }
                    if ($product->available_stock < $item['quantity']) {
                        throw new Exception(__('الكمية المطلوبة من ":product" غير متوفرة حالياً بالمخزون (المتبقي: :stock قطعة).', [
                            'product' => $product->name,
                            'stock' => $product->available_stock,
                        ]));
                    }
                } elseif (($item['type'] ?? '') === 'bundle') {
                    if (isset($item['products']) && is_array($item['products'])) {
                        foreach ($item['products'] as $bundleProduct) {
                            $product = Product::lockForUpdate()->find($bundleProduct['id']);
                            if (!$product || $product->stock_quantity < $item['quantity']) {
                                throw new Exception(__('أحد منتجات العرض الترويجي غير متوفر بالكمية المطلوبة.'));
                            }
                        }
                    }
                }
            }

            // 2. Create the order record
            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $user?->id,
                'customer_name' => $customerName,
                'total_price' => (float)($totals['grand_total'] ?? 0),
                'status' => Order::STATUS_PENDING,
                'address' => $customerData['address'] ?? '',
                'phone' => $customerData['phone'] ?? '',
                'payment_method' => 'whatsapp_checkout',
                'notes' => $customerData['notes'] ?? null,
                'currency' => $currency,
                'exchange_rate' => $exchangeRate,
            ]);

            // 3. Create order items and deduct stock
            foreach ($cartItems as $item) {
                if (($item['type'] ?? 'product') === 'product') {
                    $order->orderItems()->create([
                        'product_id' => $item['id'],
                        'price' => $item['price'],
                        'total_price' => $item['total'],
                        'quantity' => $item['quantity'],
                    ]);

                    // Deduct stock
                    $product = Product::lockForUpdate()->find($item['id']);
                    if ($product) {
                        $newStock = max(0, $product->stock_quantity - $item['quantity']);
                        $product->update([
                            'stock_quantity' => $newStock,
                            'is_available' => $newStock > 0,
                        ]);
                    }
                } elseif (($item['type'] ?? '') === 'bundle') {
                    if (isset($item['products']) && is_array($item['products'])) {
                        foreach ($item['products'] as $bundleProduct) {
                            $product = Product::lockForUpdate()->find($bundleProduct['id']);
                            if ($product) {
                                $newStock = max(0, $product->stock_quantity - $item['quantity']);
                                $product->update([
                                    'stock_quantity' => $newStock,
                                    'is_available' => $newStock > 0,
                                ]);
                            }
                        }
                    }
                }
            }

            return $order;
        });
    }

    /**
     * Update order status with corresponding timestamp and automatic stock synchronization
     */
    public function updateStatus(Order $order, string $status, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($order, $status, $reason) {
            $oldStatus = $order->status;

            // 1. If moving TO cancelled from an active status -> restore reserved stock
            if ($oldStatus !== Order::STATUS_CANCELLED && $status === Order::STATUS_CANCELLED) {
                $this->restoreStockForOrder($order);
            }
            // 2. If moving FROM cancelled to an active status -> re-deduct stock
            elseif ($oldStatus === Order::STATUS_CANCELLED && $status !== Order::STATUS_CANCELLED) {
                $this->reDeductStockForOrder($order);
            }

            $updateData = ['status' => $status];

            if ($status === Order::STATUS_CONFIRMED) {
                $updateData['confirmed_at'] = now();
            } elseif ($status === Order::STATUS_SHIPPED) {
                $updateData['shipped_at'] = now();
            } elseif ($status === Order::STATUS_DELIVERED) {
                $updateData['delivered_at'] = now();
            } elseif ($status === Order::STATUS_CANCELLED) {
                $updateData['cancelled_at'] = now();
                $updateData['cancel_reason'] = $reason;
            }

            $order->update($updateData);

            return $order;
        });
    }

    /**
     * Restore stock for all items in an order (e.g. when order is cancelled or abandoned)
     */
    public function restoreStockForOrder(Order $order): void
    {
        $order->loadMissing('orderItems.product');

        foreach ($order->orderItems as $item) {
            if ($item->product_id) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $newStock = $product->stock_quantity + $item->quantity;
                    $product->update([
                        'stock_quantity' => $newStock,
                        'is_available' => true,
                    ]);
                }
            }
        }
    }

    /**
     * Re-deduct stock for all items in an order (e.g. when reactivating a cancelled order)
     */
    public function reDeductStockForOrder(Order $order): void
    {
        $order->loadMissing('orderItems.product');

        foreach ($order->orderItems as $item) {
            if ($item->product_id) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    $newStock = max(0, $product->stock_quantity - $item->quantity);
                    $product->update([
                        'stock_quantity' => $newStock,
                        'is_available' => $newStock > 0,
                    ]);
                }
            }
        }
    }

    /**
     * Generate unique sequential order number
     */
    public function generateOrderNumber(): string
    {
        $prefix = 'RGL-' . date('Ymd') . '-';
        $random = strtoupper(Str::random(4));
        $number = $prefix . $random;

        while (Order::where('order_number', $number)->exists()) {
            $number = $prefix . strtoupper(Str::random(4));
        }

        return $number;
    }
}
