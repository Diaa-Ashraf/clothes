<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService();
        $this->category = Category::create([
            'name' => 'الأجبان والألبان',
            'slug' => 'dairy-cheese',
            'image' => 'default-cat.jpg',
            'is_active' => true,
        ]);
    }

    public function test_can_add_product_to_cart_with_different_portions(): void
    {
        $product = Product::create([
            'name' => 'جبنة رومي فاخرة',
            'slug' => 'roomy-cheese',
            'category_id' => $this->category->id,
            'price' => 200.00,
            'stock_quantity' => 20,
            'quantity' => 20,
            'is_active' => true,
            'is_available' => true,
            'is_weighted' => true,
        ]);

        // Add 250g (Quarter kg) -> 200 * 0.25 = 50.00
        $resultQuarter = $this->cartService->addProduct($product->id, 1, '250g');
        $this->assertTrue($resultQuarter['success']);
        $this->assertEquals(50.00, $resultQuarter['item']['price']);
        $this->assertEquals('250g', $resultQuarter['item']['portion_type']);

        // Add 500g (Half kg) -> 200 * 0.5 = 100.00
        $resultHalf = $this->cartService->addProduct($product->id, 1, '500g');
        $this->assertTrue($resultHalf['success']);
        $this->assertEquals(100.00, $resultHalf['item']['price']);
        $this->assertEquals('500g', $resultHalf['item']['portion_type']);

        // Add 125g (1/8 kg) -> 200 * 0.125 = 25.00
        $resultEighth = $this->cartService->addProduct($product->id, 1, '125g');
        $this->assertTrue($resultEighth['success']);
        $this->assertEquals(25.00, $resultEighth['item']['price']);

        // Add custom price (e.g. 75 EGP)
        $resultCustom = $this->cartService->addProduct($product->id, 1, 'custom_price', 75.00);
        $this->assertTrue($resultCustom['success']);
        $this->assertEquals(75.00, $resultCustom['item']['price']);
        $this->assertEquals(75.00, $resultCustom['item']['custom_amount']);

        $items = $this->cartService->getItems();
        $this->assertCount(4, $items);
    }

    public function test_enforces_stock_limit_of_max_one_piece_when_stock_is_five_or_less(): void
    {
        $limitedProduct = Product::create([
            'name' => 'زبدة بلدي طبيعي نادرة',
            'slug' => 'natural-butter-rare',
            'category_id' => $this->category->id,
            'price' => 150.00,
            'stock_quantity' => 3, // <= 5 limited stock
            'quantity' => 3,
            'is_active' => true,
            'is_available' => true,
            'is_weighted' => false,
        ]);

        // Adding 1 piece should succeed
        $firstAdd = $this->cartService->addProduct($limitedProduct->id, 1, 'piece');
        $this->assertTrue($firstAdd['success']);

        // Attempting to add a 2nd piece should fail with limit error
        $secondAdd = $this->cartService->addProduct($limitedProduct->id, 1, 'piece');
        $this->assertFalse($secondAdd['success']);
        $this->assertStringContainsString('5 قطع أو أقل', $secondAdd['message']);
    }

    public function test_can_update_quantity_and_remove_items(): void
    {
        $product = Product::create([
            'name' => 'حليب كامل الدسم',
            'slug' => 'full-cream-milk',
            'category_id' => $this->category->id,
            'price' => 40.00,
            'stock_quantity' => 50,
            'quantity' => 50,
            'is_active' => true,
            'is_available' => true,
        ]);

        $this->cartService->addProduct($product->id, 2, 'piece');
        $this->assertEquals(2, $this->cartService->getCount());

        $this->cartService->updateQuantity('product_' . $product->id, 5);
        $this->assertEquals(5, $this->cartService->getCount());

        $this->cartService->removeItem('product_' . $product->id);
        $this->assertEquals(0, $this->cartService->getCount());
    }
}
