<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Deal;
use App\Models\Magazine;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferController extends Controller
{
    /**
     * Display all special offers, active weekly magazine, flash deals, bundles, and discounted products.
     */
    public function index(Request $request): View
    {
        // 1. Fetch Active Weekly Magazine Edition (Phase 7 - Unified Magazine System)
        $activeMagazine = Magazine::with(['items.product.category'])
            ->where('is_active', true)
            ->latest('starts_at')
            ->first();

        // 2. Active Flash Deals (Fallback / Direct standalone deals)
        $deals = Deal::with(['product.category'])
            ->active()
            ->ordered()
            ->get();

        // 3. Active Bundles (عروض التوفير المجمعة)
        $bundles = Bundle::with(['products'])
            ->active()
            ->ordered()
            ->get();

        // 4. Discounted products catalog (المنتجات المخفضة العامة)
        $discountedProducts = Product::select([
                'id', 'name', 'slug', 'image', 'price', 'discount_price',
                'category_id', 'is_available', 'quantity', 'stock_quantity'
            ])
            ->with(['category:id,name,slug'])
            ->where('is_active', true)
            ->where('is_available', true)
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->whereRaw('discount_price < price')
            ->orderBy('sort_order', 'asc')
            ->orderBy('updated_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('storefront.offers.index', compact('activeMagazine', 'deals', 'bundles', 'discountedProducts'));
    }
}
