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
        $result = $this->cartService->addProduct($id, $quantity);

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
     * Get live cart payload for drawer or dynamic updates.
     */
    public function getPayload(Request $request): JsonResponse
    {
        $deliveryType = $request->query('delivery_type', 'delivery');
        return response()->json([
            'items' => $this->cartService->getDetailedItems(),
            'totals' => $this->cartService->getTotals($deliveryType),
            'count' => $this->cartService->getCount(),
        ]);
    }
}
