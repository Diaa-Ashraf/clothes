<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $orderService;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderService = new OrderService();
        $this->category = Category::create([
            'name' => 'البقالة والتموين',
            'slug' => 'grocery',
            'image' => 'default-cat.jpg',
            'is_active' => true,
        ]);
    }

    public function test_creates_order_and_deducts_stock_properly(): void
    {
        $product = Product::create([
            'name' => 'سكر أبيض فاخر 1ك',
            'slug' => 'white-sugar-1kg',
            'category_id' => $this->category->id,
            'price' => 35.00,
            'stock_quantity' => 15,
            'quantity' => 15,
            'is_active' => true,
            'is_available' => true,
        ]);

        $customerData = [
            'name' => 'أحمد محمود',
            'phone' => '01012345678',
            'address' => 'العامرية، شارع السوق',
            'payment_method' => Order::PAYMENT_METHOD_CASH,
            'delivery_type' => Order::DELIVERY_TYPE_DELIVERY,
        ];

        $cartItems = [
            'product_' . $product->id => [
                'type' => 'product',
                'id' => $product->id,
                'name' => $product->name,
                'price' => 35.00,
                'quantity' => 3,
                'total' => 105.00,
            ],
        ];

        $totals = [
            'subtotal' => 105.00,
            'delivery_fee' => 15.00,
            'discount_amount' => 0.00,
            'grand_total' => 120.00,
        ];

        $order = $this->orderService->createOrder($customerData, $cartItems, $totals);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'أحمد محمود',
            'total_price' => 120.00,
            'status' => Order::STATUS_PENDING,
            'payment_method' => Order::PAYMENT_METHOD_CASH,
        ]);

        // Stock should be deducted from 15 to 12
        $product->refresh();
        $this->assertEquals(12, $product->stock_quantity);
    }

    public function test_restores_stock_when_order_is_cancelled(): void
    {
        $product = Product::create([
            'name' => 'أرز مصري عريض الحبة 1ك',
            'slug' => 'egyptian-rice-1kg',
            'category_id' => $this->category->id,
            'price' => 30.00,
            'stock_quantity' => 10,
            'quantity' => 10,
            'is_active' => true,
            'is_available' => true,
        ]);

        $order = $this->orderService->createOrder(
            [
                'name' => 'علي سالم',
                'phone' => '01298765432',
                'address' => 'العامرية، كينج مريوط',
                'payment_method' => Order::PAYMENT_METHOD_CASH,
            ],
            [
                'product_' . $product->id => [
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => 30.00,
                    'quantity' => 4,
                    'total' => 120.00,
                ],
            ],
            ['grand_total' => 120.00]
        );

        $product->refresh();
        $this->assertEquals(6, $product->stock_quantity);

        // Cancel order -> should restore stock back to 10
        $this->orderService->updateStatus($order, Order::STATUS_CANCELLED, 'العميل طلب الإلغاء');

        $product->refresh();
        $this->assertEquals(10, $product->stock_quantity);
        $this->assertEquals(Order::STATUS_CANCELLED, $order->fresh()->status);
    }
}
