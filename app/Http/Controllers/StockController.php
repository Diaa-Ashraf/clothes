<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\AdjustStockRequest;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:manage-products'])->only(['index', 'adjust', 'movements']);
    }

    /**
     * Display live fashion inventory overview and stock levels.
     */
    public function index(Request $request, InventoryService $inventoryService): View
    {
        $query = Product::query()
            ->select([
                'id',
                'name',
                'sku',
                'category_id',
                'brand_id',
                'image',
                'stock_quantity',
                'is_available',
                'created_at',
            ])
            ->with([
                'category:id,name',
                'brand:id,name',
                'variants' => fn($q) => $q->select(['id', 'product_id', 'sku', 'barcode', 'price', 'stock_quantity', 'is_active'])
                    ->with(['attributeValues:id,attribute_id,value,color_code']),
            ])
            ->withCount('variants');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhereHas('variants', function ($vQ) use ($search) {
                      $vQ->where('sku', 'LIKE', "%{$search}%")
                         ->orWhere('barcode', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('stock_status')) {
            $status = $request->input('stock_status');
            if ($status === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($status === 'low_stock') {
                $query->where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 5);
            } elseif ($status === 'in_stock') {
                $query->where('stock_quantity', '>', 5);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $metrics = $inventoryService->getInventoryMetrics();
        $branches = Branch::select(['id', 'name'])->active()->ordered()->get();
        $categories = Category::select(['id', 'name', 'parent_id'])->ordered()->get();
        $brands = Brand::select(['id', 'name'])->ordered()->get();

        return view('admin.stocks.index', compact('products', 'metrics', 'branches', 'categories', 'brands'));
    }

    /**
     * Adjust stock for product or specific size/color variant.
     */
    public function adjust(AdjustStockRequest $request, InventoryService $inventoryService): RedirectResponse
    {
        $validated = $request->validated();

        $inventoryService->adjustStock(
            productId: (int)$validated['product_id'],
            variantId: !empty($validated['variant_id']) ? (int)$validated['variant_id'] : null,
            delta: (int)$validated['quantity_delta'],
            type: $validated['type'],
            reason: $validated['reason'] ?? null,
            branchId: !empty($validated['branch_id']) ? (int)$validated['branch_id'] : null,
            userId: auth()->id()
        );

        return redirect()->back()->with('success', 'تم تعديل كمية المخزون وتسجيل الحركة المخزنية بنجاح.');
    }

    /**
     * Display immutable stock movement audit trail.
     */
    public function movements(Request $request): View
    {
        $query = StockMovement::query()
            ->with([
                'product:id,name,sku,image',
                'variant:id,sku',
                'variant.attributeValues:id,attribute_id,value,color_code',
                'branch:id,name',
                'creator:id,name',
            ]);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('reason', 'LIKE', "%{$search}%")
                  ->orWhereHas('product', fn($pQ) => $pQ->where('name', 'LIKE', "%{$search}%")->orWhere('sku', 'LIKE', "%{$search}%"))
                  ->orWhereHas('variant', fn($vQ) => $vQ->where('sku', 'LIKE', "%{$search}%"));
            });
        }

        $movements = $query->latestFirst()->paginate(20)->withQueryString();

        return view('admin.stocks.movements', compact('movements'));
    }
}
