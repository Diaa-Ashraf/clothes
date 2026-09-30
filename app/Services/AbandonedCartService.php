<?php

namespace App\Services;

use App\Models\AbandonedCart;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AbandonedCartService
{
    /**
     * Record or update an abandoned cart snapshot
     */
    public function recordSnapshot(array $cartItems, array $totals, ?User $user = null, ?array $customerInfo = null): ?AbandonedCart
    {
        if (empty($cartItems)) {
            return null;
        }

        $sessionId = Session::getId();

        return AbandonedCart::updateOrCreate(
            ['session_id' => $sessionId, 'is_recovered' => false],
            [
                'user_id' => $user?->id,
                'cart_data' => $cartItems,
                'total_amount' => $totals['grand_total'],
                'item_count' => count($cartItems),
                'customer_name' => $customerInfo['name'] ?? $user?->name,
                'customer_phone' => $customerInfo['phone'] ?? $user?->phone,
                'customer_email' => $customerInfo['email'] ?? $user?->email,
                'last_activity_at' => now(),
            ]
        );
    }

    /**
     * Mark cart as recovered for user or current session
     */
    public function markRecoveredForUserOrSession(?User $user = null, ?string $sessionId = null): void
    {
        $id = $sessionId ?? Session::getId();

        $query = AbandonedCart::where('is_recovered', false);

        if ($user?->id) {
            $query->where(function ($q) use ($user, $id) {
                $q->where('user_id', $user->id)
                  ->orWhere('session_id', $id);
            });
        } else {
            $query->where('session_id', $id);
        }

        $query->update(['is_recovered' => true]);
    }

    /**
     * Mark cart as recovered once an order is placed
     */
    public function markAsRecovered(?string $sessionId = null): void
    {
        $this->markRecoveredForUserOrSession(null, $sessionId);
    }
}
