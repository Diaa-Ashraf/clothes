<?php

namespace App\Services;

use App\Models\Coupon;
use Carbon\Carbon;

class CouponService
{
    /**
     * Validate coupon code against an amount and user/phone.
     */
    public function validateCoupon(string $code, float $subtotal, ?int $userId = null, ?string $phone = null): array
    {
        $code = trim(strtoupper($code));
        
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'كوبون الخصم غير موجود.',
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        if (!$coupon->is_active) {
            return [
                'valid' => false,
                'message' => 'كوبون الخصم غير مفعل حالياً.',
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        $now = Carbon::now();

        if ($coupon->starts_at && $now->lt($coupon->starts_at)) {
            return [
                'valid' => false,
                'message' => 'كوبون الخصم لم يبدأ تفعيله بعد.',
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        if ($coupon->expires_at && $now->gt($coupon->expires_at)) {
            return [
                'valid' => false,
                'message' => 'عذراً، انتهت صلاحية هذا الكوبون.',
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        if ($coupon->usage_limit !== null && $coupon->used_count >= $coupon->usage_limit) {
            return [
                'valid' => false,
                'message' => 'تم استنفاد الحد الأقصى لاستخدام هذا الكوبون.',
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        if ($coupon->min_order_amount !== null && $subtotal < (float) $coupon->min_order_amount) {
            return [
                'valid' => false,
                'message' => "الحد الأدنى لتفعيل هذا الكوبون هو {$coupon->min_order_amount} ج.م.",
                'coupon' => null,
                'discount' => 0.00,
            ];
        }

        // Check usage_per_user limit
        if ($coupon->usage_per_user !== null) {
            $userUsageQuery = \App\Models\CouponUsage::where('coupon_id', $coupon->id);
            if ($userId) {
                $userUsageQuery->where(function ($q) use ($userId, $phone) {
                    $q->where('user_id', $userId);
                    if ($phone) {
                        $q->orWhere('customer_phone', $phone);
                    }
                });
            } elseif ($phone) {
                $userUsageQuery->where('customer_phone', $phone);
            }

            if ($userId || $phone) {
                $existingCount = $userUsageQuery->count();
                if ($existingCount >= $coupon->usage_per_user) {
                    return [
                        'valid' => false,
                        'message' => "لقد استنفدت الحد الأقصى المسموح به لاستخدام هذا الكوبون ({$coupon->usage_per_user} مرة).",
                        'coupon' => null,
                        'discount' => 0.00,
                    ];
                }
            }
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid' => true,
            'message' => 'تم تطبيق كوبون الخصم بنجاح.',
            'coupon' => $coupon,
            'discount' => $discount,
        ];
    }

    /**
     * Increment usage count for a coupon and record detailed entry.
     */
    public function recordUsage(string $code, ?int $userId = null, ?int $orderId = null, ?string $phone = null, float $discountAmount = 0.0): bool
    {
        $coupon = Coupon::where('code', trim(strtoupper($code)))->first();
        if ($coupon) {
            $coupon->increment('used_count');

            \App\Models\CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $userId,
                'order_id' => $orderId,
                'customer_phone' => $phone,
                'discount_amount' => $discountAmount,
            ]);

            return true;
        }
        return false;
    }
}
