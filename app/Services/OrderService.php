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
            $currency = function_exists('get_active_currency') ? get_active_currency() : 'EGP';
            $exchangeRate = function_exists('get_exchange_rate') ? get_exchange_rate() : 1.0;
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

            // Determine initial status based on payment method
            $paymentMethod = $customerData['payment_method'] ?? Order::PAYMENT_METHOD_CASH;
            $initialStatus = ($paymentMethod === Order::PAYMENT_METHOD_INSTAPAY)
                ? Order::STATUS_UNDER_REVIEW
                : Order::STATUS_PENDING;

            // 2. Create the order record
            $order = Order::create([
                'public_order_id' => $customerData['public_order_id'] ?? $this->generatePublicOrderId(),
                'order_number' => $customerData['order_number'] ?? $this->generateOrderNumber(),
                'user_id' => $user?->id,
                'customer_name' => $customerName,
                'total_price' => (float)($totals['grand_total'] ?? 0),
                'status' => $customerData['status'] ?? $initialStatus,
                'delivery_type' => $customerData['delivery_type'] ?? Order::DELIVERY_TYPE_DELIVERY,
                'branch_id' => $customerData['branch_id'] ?? null,
                'address' => $customerData['address'] ?? '',
                'phone' => $customerData['phone'] ?? '',
                'payment_method' => $paymentMethod,
                'coupon_code' => $customerData['coupon_code'] ?? ($totals['coupon_code'] ?? null),
                'discount_amount' => (float)($customerData['discount_amount'] ?? ($totals['discount_amount'] ?? 0)),
                'payment_proof_image' => $customerData['payment_proof_image'] ?? null,
                'instapay_reference' => $customerData['instapay_reference'] ?? null,
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
                        'portion_type' => $item['portion_type'] ?? 'piece',
                        'portion_label' => $item['portion_label'] ?? null,
                        'custom_amount' => $item['custom_amount'] ?? null,
                        'notes' => $item['notes'] ?? null,
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
     * Allowed order status transitions (State Machine)
     */
    public const ALLOWED_TRANSITIONS = [
        Order::STATUS_PENDING => [
            Order::STATUS_CONFIRMED,
            Order::STATUS_PROCESSING,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_UNDER_REVIEW => [
            Order::STATUS_CONFIRMED,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_CONFIRMED => [
            Order::STATUS_PROCESSING,
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_ON_THE_WAY,
            Order::STATUS_SHIPPED,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_PROCESSING => [
            Order::STATUS_READY_FOR_PICKUP,
            Order::STATUS_ON_THE_WAY,
            Order::STATUS_SHIPPED,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_READY_FOR_PICKUP => [
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_ON_THE_WAY => [
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_SHIPPED => [
            Order::STATUS_DELIVERED,
            Order::STATUS_CANCELLED,
        ],
        Order::STATUS_DELIVERED => [], // Terminal state
        Order::STATUS_CANCELLED => [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
        ], // Reactivation
    ];

    /**
     * Update order status with state machine validation, corresponding timestamp and automatic stock synchronization
     */
    public function updateStatus(Order $order, string $status, ?string $reason = null): Order
    {
        $oldStatus = $order->status;

        if ($oldStatus !== $status) {
            $allowed = self::ALLOWED_TRANSITIONS[$oldStatus] ?? [];
            if (!in_array($status, $allowed)) {
                throw new Exception("لا يمكن تحويل حالة الطلب من '{$oldStatus}' إلى '{$status}'.");
            }
        }

        return DB::transaction(function () use ($order, $status, $reason, $oldStatus) {

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
            } elseif (in_array($status, [Order::STATUS_SHIPPED, Order::STATUS_ON_THE_WAY, Order::STATUS_READY_FOR_PICKUP])) {
                $updateData['shipped_at'] = now();
            } elseif ($status === Order::STATUS_DELIVERED) {
                $updateData['delivered_at'] = now();
            } elseif ($status === Order::STATUS_CANCELLED) {
                $updateData['cancelled_at'] = now();
                $updateData['cancel_reason'] = $reason;
            }

            $order->update($updateData);

            // Trigger email notification to customer if email is available
            try {
                $recipientEmail = $order->user?->email;
                if ($recipientEmail) {
                    \Illuminate\Support\Facades\Mail::to($recipientEmail)->queue(new \App\Mail\OrderStatusChanged($order));
                }
            } catch (\Throwable $e) {
                // Log and continue without breaking the transaction
                \Illuminate\Support\Facades\Log::warning("Failed to queue order status email: " . $e->getMessage());
            }

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
        $prefix = 'TWH-' . date('Ymd') . '-';
        do {
            $number = $prefix . strtoupper(Str::random(4));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Generate unique public order ID (e.g. TWH-20260926-XXXX)
     */
    public function generatePublicOrderId(): string
    {
        $prefix = 'TWH-' . date('Ymd') . '-';
        do {
            $candidate = $prefix . strtoupper(Str::random(4));
        } while (Order::where('public_order_id', $candidate)->exists());

        return $candidate;
    }
}
