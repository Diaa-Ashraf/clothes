<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\AbandonedCartService;
use App\Http\Requests\Storefront\PlaceOrderRequest;
use App\Models\Branch;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
        protected AbandonedCartService $abandonedCartService
    ) {}

    /**
     * Display the checkout page.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $items = $this->cartService->getDetailedItems();

        if (empty($items)) {
            return redirect()->route('cart')->with('error', __('سلة الشراء فارغة، يرجى إضافة منتجات أولاً للمتابعة.'));
        }

        $deliveryType = $request->query('delivery_type', 'delivery');
        $totals = $this->cartService->getTotals($deliveryType);
        $branches = Branch::select(['id', 'name', 'address', 'phone', 'working_hours'])->active()->ordered()->get();
        $user = auth()->user();

        return view('storefront.checkout', compact('items', 'totals', 'branches', 'user', 'deliveryType'));
    }

    /**
     * Handle checkout order placement.
     */
    public function placeOrder(PlaceOrderRequest $request): JsonResponse|RedirectResponse
    {
        $items = $this->cartService->getItems();

        if (empty($items)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('سلة الشراء فارغة، يرجى إضافة منتجات أولاً.')
                ], 422);
            }
            return redirect()->route('cart')->with('error', __('سلة الشراء فارغة.'));
        }

        $customerData = $request->validated();
        $deliveryType = $customerData['delivery_type'] ?? 'delivery';
        $totals = $this->cartService->getTotals($deliveryType);

        // Handle InstaPay receipt image upload
        if ($request->hasFile('payment_proof_image')) {
            $file = $request->file('payment_proof_image');
            $path = $file->store('receipts', 'public');
            $customerData['payment_proof_image'] = $path;
        }

        try {
            $user = auth()->user();

            // 1. Create order & deduct stock
            $order = $this->orderService->createOrder($customerData, $items, $totals, $user);

            // 2. Dispatch real-time Admin Notification
            try {
                \App\Models\AdminNotification::createForOrder($order);
            } catch (\Throwable $e) {
                // Ignore notification error so order process doesn't fail
            }

            // 3. Mark abandoned cart recovered
            $this->abandonedCartService->markRecoveredForUserOrSession($user);

            // 4. Record coupon usage if applied
            if (!empty($totals['coupon_code'])) {
                app(\App\Services\CouponService::class)->recordUsage(
                    $totals['coupon_code'],
                    $user?->id,
                    $order->id,
                    $order->phone,
                    (float)($totals['discount_amount'] ?? 0)
                );
                $this->cartService->removeCoupon();
            }

            // 5. Clear shopping cart
            $this->cartService->clear();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('تم تسجيل طلبك بنجاح!'),
                    'order_id' => $order->id,
                    'public_order_id' => $order->public_order_id,
                    'redirect_url' => route('checkout.confirmation', $order->public_order_id),
                ]);
            }

            return redirect()->route('checkout.confirmation', $order->public_order_id)
                ->with('order_success', true);

        } catch (Throwable $e) {
            // Delete uploaded receipt file on failure
            if (!empty($customerData['payment_proof_image'])) {
                Storage::disk('public')->delete($customerData['payment_proof_image']);
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Display order confirmation and tracking instructions.
     */
    public function confirmation(string $publicOrderId): View
    {
        $order = Order::with(['orderItems.product', 'branch'])
            ->where('public_order_id', $publicOrderId)
            ->orWhere('id', $publicOrderId)
            ->firstOrFail();

        return view('storefront.checkout.confirmation', compact('order'));
    }
}
