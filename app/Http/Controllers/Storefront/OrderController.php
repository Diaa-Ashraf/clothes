<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * Display a paginated listing of the authenticated user's orders.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = auth()->user()->orders()
            ->with(['orderItems.product', 'branch'])
            ->latest('id');

        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('storefront.orders.index', compact('orders', 'status'));
    }

    /**
     * Display a specific order with visual lifecycle timeline.
     */
    public function show(string $id): View
    {
        $order = auth()->user()->orders()
            ->with(['orderItems.product', 'branch'])
            ->where(function ($q) use ($id) {
                $q->where('id', $id)
                  ->orWhere('public_order_id', $id);
            })
            ->firstOrFail();

        return view('storefront.orders.show', compact('order'));
    }

    /**
     * Re-order items from a past order with 1 click.
     */
    public function reorder(string $id): RedirectResponse
    {
        $order = auth()->user()->orders()
            ->with('orderItems.product')
            ->where(function ($q) use ($id) {
                $q->where('id', $id)
                  ->orWhere('public_order_id', $id);
            })
            ->firstOrFail();

        $addedCount = 0;

        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->is_active && $item->product->stock_quantity > 0) {
                $this->cartService->addProduct(
                    $item->product_id,
                    $item->quantity,
                    $item->portion_type ?? 'piece',
                    $item->custom_amount,
                    $item->notes
                );
                $addedCount++;
            }
        }

        if ($addedCount > 0) {
            return redirect()->route('cart')->with('success', "تمت إضافة {$addedCount} من منتجات طلبك السابق إلى السلة بنجاح.");
        }

        return redirect()->back()->with('error', 'عذراً، المنتجات في هذا الطلب غير متوفرة حالياً في المخزون.');
    }

    /**
     * Public order tracking page (No login required).
     */
    public function track(Request $request, ?string $publicOrderId = null): View
    {
        $searchTerm = trim((string)($publicOrderId ?: $request->input('q', $request->input('order_id', ''))));
        $order = null;
        $searched = !empty($searchTerm);

        if ($searched) {
            $order = Order::with(['orderItems.product', 'branch'])
                ->where('public_order_id', $searchTerm)
                ->orWhere('order_number', $searchTerm)
                ->first();
        }

        return view('storefront.orders.track', compact('order', 'searchTerm', 'searched'));
    }
}
