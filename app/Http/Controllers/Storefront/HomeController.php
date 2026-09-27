<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Deal;
use App\Models\Bundle;
use App\Models\Branch;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display the storefront home page.
     */
    public function index(): View
    {
        // 1. Hero Banners
        $banners = Banner::select(['id', 'title', 'description', 'image', 'url', 'position'])
            ->where('status', true)
            ->latest('id')
            ->take(5)
            ->get();

        // 2. Active Categories with products count
        $categories = Category::select(['id', 'name', 'slug', 'image'])
            ->active()
            ->withCount(['products' => function ($q) {
                $q->active();
            }])
            ->ordered()
            ->take(12)
            ->get();

        // 3. Today's Deals
        $deals = Deal::select(['id', 'product_id', 'discount_percent', 'original_price', 'deal_price', 'badge_text', 'starts_at', 'ends_at'])
            ->active()
            ->with(['product' => function ($q) {
                $q->select(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'is_available', 'stock_quantity', 'quantity', 'category_id'])
                    ->with(['category:id,name']);
            }])
            ->ordered()
            ->take(8)
            ->get();

        // 4. Featured Dairy & Groceries
        $featuredProducts = Product::select(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'featured', 'is_available', 'stock_quantity', 'quantity', 'category_id'])
            ->active()
            ->featured()
            ->with(['category:id,name'])
            ->latest('id')
            ->take(10)
            ->get();

        // 5. Latest Fresh Additions
        $latestProducts = Product::select(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'featured', 'is_available', 'stock_quantity', 'quantity', 'category_id'])
            ->active()
            ->with(['category:id,name'])
            ->latest('id')
            ->take(10)
            ->get();

        // 6. Active Bundles
        $bundles = Bundle::select(['id', 'name', 'description', 'image', 'original_total', 'bundle_price', 'discount_percent'])
            ->active()
            ->with(['products' => function ($q) {
                $q->select(['products.id', 'products.name']);
            }])
            ->ordered()
            ->take(6)
            ->get();

        // 7. Ameriya Branches
        $branches = Branch::select(['id', 'name', 'address', 'phone', 'whatsapp', 'working_hours', 'google_maps_url', 'image'])
            ->active()
            ->ordered()
            ->get();

        return view('storefront.home', compact(
            'banners',
            'categories',
            'deals',
            'featuredProducts',
            'latestProducts',
            'bundles',
            'branches'
        ));
    }
}
