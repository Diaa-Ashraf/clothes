<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\AbandonedCartService;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected AbandonedCartService $abandonedCartService
    ) {}

    /**
     * Display the shopping cart page.
     */
    public function index(Request $request): View
    {
        $deliveryType = $request->query('delivery_type', 'delivery');
        $items = $this->cartService->getDetailedItems();
        $totals = $this->cartService->getTotals($deliveryType);
        $branches = Branch::select(['id', 'name', 'address', 'phone'])->active()->ordered()->get();

        // Capture abandoned cart snapshot
        if (!empty($items)) {
            $this->abandonedCartService->recordSnapshot($this->cartService->getItems(), $totals, auth()->user());
        }

        return view('storefront.cart', compact('items', 'totals', 'branches', 'deliveryType'));
    }

    /**
     * Add product to cart via AJAX.
     */
    public function add(Request $request, int $id): JsonResponse
    {
        $quantity = max(1, (int)$request->input('quantity', 1));
        $portionType = $request->input('portion_type', 'piece');
        $customAmount = $request->filled('custom_amount') ? (float)$request->input('custom_amount') : null;
        $notes = $request->input('notes');

        $result = $this->cartService->addProduct($id, $quantity, $portionType, $customAmount, $notes);

        if ($result['success']) {
            $this->abandonedCartService->recordSnapshot(
                $this->cartService->getItems(),
                $this->cartService->getTotals(),
                auth()->user()
            );
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Add bundle to cart via AJAX.
     */
    public function addBundle(Request $request): JsonResponse
    {
        $bundleId = (int)$request->input('bundle_id');
        $result = $this->cartService->addBundle($bundleId);

        if ($result['success']) {
            $this->abandonedCartService->recordSnapshot(
                $this->cartService->getItems(),
                $this->cartService->getTotals(),
                auth()->user()
            );
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Update item quantity via AJAX.
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $itemKey = (string)$request->input('key');
        $quantity = (int)$request->input('quantity', 1);

        $result = $this->cartService->updateQuantity($itemKey, $quantity);

        if ($result['success']) {
            $this->abandonedCartService->recordSnapshot(
                $this->cartService->getItems(),
                $result['totals'],
                auth()->user()
            );
            $result['cart_count'] = $this->cartService->getCount();
        }

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Remove item from cart via AJAX.
     */
    public function removeItem(Request $request): JsonResponse
    {
        $itemKey = (string)$request->input('key');
        $result = $this->cartService->removeItem($itemKey);

        $this->abandonedCartService->recordSnapshot(
            $this->cartService->getItems(),
            $result['totals'],
            auth()->user()
        );

        return response()->json($result);
    }

    /**
     * Clear all items from cart.
     */
    public function clearCart(Request $request): JsonResponse|RedirectResponse
    {
        $this->cartService->clear();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('تم تفريغ سلة المشتريات بالكامل.'),
                'cart_count' => 0,
                'totals' => $this->cartService->getTotals(),
            ]);
        }

        return redirect()->route('cart')->with('status', __('تم تفريغ السلة بنجاح.'));
    }

    /**
     * Get live cart count.
     */
    public function getCount(): JsonResponse
    {
        return response()->json([
            'count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * Get complete cart payload (items, totals, count) for drawer and AJAX components.
     */
    public function getPayload(Request $request): JsonResponse
    {
        $deliveryType = $request->query('delivery_type', 'delivery');
        $items = $this->cartService->getDetailedItems();
        $totals = $this->cartService->getTotals($deliveryType);

        return response()->json([
            'success' => true,
            'items' => $items,
            'totals' => $totals,
            'cart_count' => $this->cartService->getCount(),
            'count' => $this->cartService->getCount(),
        ]);
    }

    /**
     * Apply a discount coupon to the cart.
     */
    public function applyCoupon(Request $request, \App\Services\CouponService $couponService): JsonResponse
    {
        $code = (string)$request->input('coupon_code', '');
        
        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => __('يرجى إدخال كود الكوبون.'),
            ], 422);
        }

        $totals = $this->cartService->getTotals();
        $subtotal = (float)$totals['subtotal'];

        if ($subtotal <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('السلة فارغة، لا يمكن تطبيق الكوبون.'),
            ], 422);
        }

        $result = $couponService->validateCoupon($code, $subtotal);

        if (!$result['valid']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        $this->cartService->applyCoupon($code, (float)$result['discount']);
        $newTotals = $this->cartService->getTotals();

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'discount' => $result['discount'],
            'totals' => $newTotals,
        ]);
    }

    /**
     * Remove the currently applied coupon.
     */
    public function removeCoupon(): JsonResponse
    {
        $this->cartService->removeCoupon();

        return response()->json([
            'success' => true,
            'message' => __('تم إلغاء تفعيل الكوبون بنجاح.'),
            'totals' => $this->cartService->getTotals(),
        ]);
    }
}
