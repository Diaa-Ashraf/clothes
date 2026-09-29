<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Coupon;
use App\Services\CouponService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CouponServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CouponService $couponService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->couponService = new CouponService();
    }

    public function test_validates_percentage_coupon_and_calculates_discount(): void
    {
        Coupon::create([
            'code' => 'TAWHID10',
            'type' => 'percentage',
            'value' => 10.00,
            'is_active' => true,
            'min_order_amount' => 100.00,
            'usage_limit' => 50,
            'used_count' => 0,
        ]);

        // Below minimum order amount -> invalid
        $lowSubtotalResult = $this->couponService->validateCoupon('TAWHID10', 80.00);
        $this->assertFalse($lowSubtotalResult['valid']);
        $this->assertStringContainsString('الحد الأدنى', $lowSubtotalResult['message']);

        // Valid subtotal -> 10% of 200 = 20.00 discount
        $validResult = $this->couponService->validateCoupon('TAWHID10', 200.00);
        $this->assertTrue($validResult['valid']);
        $this->assertEquals(20.00, $validResult['discount']);
    }

    public function test_validates_fixed_coupon_and_rejects_expired_or_inactive_coupon(): void
    {
        // Expired coupon
        Coupon::create([
            'code' => 'EXPIRED50',
            'type' => 'fixed',
            'value' => 50.00,
            'is_active' => true,
            'expires_at' => Carbon::now()->subDay(),
        ]);

        $expiredResult = $this->couponService->validateCoupon('EXPIRED50', 300.00);
        $this->assertFalse($expiredResult['valid']);
        $this->assertStringContainsString('انتهت صلاحية', $expiredResult['message']);

        // Inactive coupon
        Coupon::create([
            'code' => 'DISABLED20',
            'type' => 'fixed',
            'value' => 20.00,
            'is_active' => false,
        ]);

        $inactiveResult = $this->couponService->validateCoupon('DISABLED20', 300.00);
        $this->assertFalse($inactiveResult['valid']);
        $this->assertStringContainsString('غير مفعل', $inactiveResult['message']);
    }

    public function test_records_coupon_usage_properly(): void
    {
        $coupon = Coupon::create([
            'code' => 'USEME',
            'type' => 'fixed',
            'value' => 15.00,
            'is_active' => true,
            'used_count' => 5,
        ]);

        $recorded = $this->couponService->recordUsage('USEME');
        $this->assertTrue($recorded);
        $this->assertEquals(6, $coupon->fresh()->used_count);
    }
}
