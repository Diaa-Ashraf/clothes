<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;
    protected Product $product;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'الأجبان الطبيعية',
            'slug' => 'natural-cheeses',
            'image' => 'default-cat.jpg',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'name' => 'جبن قريش فلاحي',
            'slug' => 'cottage-cheese-baladi',
            'category_id' => $this->category->id,
            'price' => 80.00,
            'stock_quantity' => 25,
            'quantity' => 25,
            'is_active' => true,
            'is_available' => true,
            'is_weighted' => true,
        ]);

        $this->branch = Branch::create([
            'name' => 'فرع العامرية الرئيسي',
            'slug' => 'ameriya-main-branch',
            'address' => 'شارع المستشفى بجوار الإسعاف',
            'phone' => '01011112222',
            'is_active' => true,
        ]);
    }

    public function test_complete_checkout_flow_with_cash_on_delivery(): void
    {
        // 1. Add product to cart (500g half kg -> 40 EGP)
        $this->postJson(route('AddToCart', $this->product->id), [
            'quantity' => 1,
            'portion_type' => '500g',
        ])->assertOk();

        // 2. Submit checkout order with Cash on Delivery
        $checkoutData = [
            'name' => 'محمد إبراهيم',
            'phone' => '01012345678',
            'address' => 'العامرية، شارع المركز التجاري',
            'delivery_type' => Order::DELIVERY_TYPE_DELIVERY,
            'payment_method' => Order::PAYMENT_METHOD_CASH,
            'notes' => 'يرجى الاتصال قبل الوصول بـ 10 دقائق',
        ];

        $response = $this->post(route('checkout.place'), $checkoutData);
        $response->assertSessionHasNoErrors();

        // Should create order
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'محمد إبراهيم',
            'phone' => '01012345678',
            'payment_method' => Order::PAYMENT_METHOD_CASH,
            'status' => Order::STATUS_PENDING,
        ]);

        $order = Order::where('phone', '01012345678')->first();
        $this->assertNotNull($order);
        $this->assertCount(1, $order->orderItems);
        $this->assertEquals(40.00, $order->orderItems->first()->price);

        // Stock should be deducted
        $this->assertEquals(24, $this->product->fresh()->stock_quantity);
    }

    public function test_complete_checkout_flow_with_instapay_and_receipt_upload(): void
    {
        // 1. Add product to cart
        $this->postJson(route('AddToCart', $this->product->id), [
            'quantity' => 2,
            'portion_type' => 'piece',
        ])->assertOk();

        $receiptFile = UploadedFile::fake()->image('receipt.jpg', 600, 600);

        // 2. Submit checkout with InstaPay
        $checkoutData = [
            'name' => 'سارة حسام',
            'phone' => '01122334455',
            'address' => 'العامرية، مساكن العرائس',
            'delivery_type' => Order::DELIVERY_TYPE_DELIVERY,
            'payment_method' => Order::PAYMENT_METHOD_INSTAPAY,
            'instapay_reference' => 'INSTA-88992211',
            'payment_proof_image' => $receiptFile,
        ];

        $response = $this->post(route('checkout.place'), $checkoutData);
        $response->assertSessionHasNoErrors();

        // Should create order with status UNDER_REVIEW for InstaPay verification
        $this->assertDatabaseHas('orders', [
            'customer_name' => 'سارة حسام',
            'payment_method' => Order::PAYMENT_METHOD_INSTAPAY,
            'instapay_reference' => 'INSTA-88992211',
            'status' => Order::STATUS_UNDER_REVIEW,
        ]);

        $order = Order::where('phone', '01122334455')->first();
        $this->assertNotNull($order);
        $this->assertNotNull($order->payment_proof_image);

        // Stock should be deducted by 2
        $this->assertEquals(23, $this->product->fresh()->stock_quantity);
    }
}
