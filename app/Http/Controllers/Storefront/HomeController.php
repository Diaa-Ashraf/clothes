<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Deal;
use App\Models\Bundle;
use App\Models\Branch;
use App\Models\Review;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display the storefront home page.
     */
    public function index(): View
    {
        $cacheTtl = 1800; // 30 minutes

        // 1. Hero Banners
        $banners = Cache::remember('storefront_home_banners', $cacheTtl, function () {
            return Banner::select(['id', 'title', 'subtitle', 'description', 'image', 'url', 'position', 'button_text', 'bg_color'])
                ->where('status', true)
                ->where(function ($q) {
                    $q->where('position', 'main_slider')
                      ->orWhereNull('position');
                })
                ->latest('id')
                ->take(5)
                ->get();
        });

        // 1.5 Mid-Page Collection Banners (Displays exactly 2 collection campaigns as requested)
        $collectionBanners = Cache::remember('storefront_home_collection_banners', $cacheTtl, function () {
            return Banner::select(['id', 'title', 'subtitle', 'description', 'image', 'url', 'position', 'button_text', 'bg_color'])
                ->where('status', true)
                ->where('position', 'home_collection')
                ->latest('id')
                ->take(2)
                ->get();
        });

        // 2. Active Categories with products count
        $categories = Cache::remember('storefront_home_categories', $cacheTtl, function () {
            return Category::select(['id', 'name', 'slug', 'image'])
                ->active()
                ->withCount(['products' => function ($q) {
                    $q->active();
                }])
                ->ordered()
                ->take(12)
                ->get();
        });

        // 3. Today's Deals
        $deals = Cache::remember('storefront_home_deals', $cacheTtl, function () {
            return Deal::select(['id', 'product_id', 'discount_percent', 'original_price', 'deal_price', 'badge_text', 'starts_at', 'ends_at'])
                ->active()
                ->with(['product' => function ($q) {
                    $q->select(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'is_available', 'stock_quantity', 'quantity', 'category_id'])
                        ->with(['category:id,name']);
                }])
                ->ordered()
                ->take(8)
                ->get();
        });

        // 4. Featured Dairy & Groceries
        $featuredProducts = Cache::remember('storefront_home_featured_products', $cacheTtl, function () {
            return Product::select(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'featured', 'is_available', 'stock_quantity', 'quantity', 'category_id'])
                ->active()
                ->featured()
                ->with(['category:id,name'])
                ->latest('id')
                ->take(10)
                ->get();
        });

        // 5. Latest Fresh Additions
        $latestProducts = Cache::remember('storefront_home_latest_products', $cacheTtl, function () {
            return Product::select(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'featured', 'is_available', 'stock_quantity', 'quantity', 'category_id'])
                ->active()
                ->with(['category:id,name'])
                ->latest('id')
                ->take(10)
                ->get();
        });

        // 6. Active Bundles
        $bundles = Cache::remember('storefront_home_bundles', $cacheTtl, function () {
            return Bundle::select(['id', 'name', 'description', 'image', 'original_total', 'bundle_price', 'discount_percent'])
                ->active()
                ->with(['products' => function ($q) {
                    $q->select(['products.id', 'products.name', 'products.image', 'products.price', 'products.discount_price']);
                }])
                ->ordered()
                ->take(6)
                ->get();
        });

        // 7. Ameriya Branches
        $branches = Cache::remember('storefront_home_branches', $cacheTtl, function () {
            return Branch::select(['id', 'name', 'address', 'phone', 'whatsapp', 'working_hours', 'google_maps_url', 'image'])
                ->active()
                ->ordered()
                ->get();
        });

        // 8. Real Customer Reviews from Egyptian Governorates
        $reviews = Cache::remember('storefront_home_reviews', $cacheTtl, function () {
            return Review::select(['id', 'product_id', 'user_id', 'reviewer_name', 'governorate', 'rating', 'comment', 'created_at'])
                ->approved()
                ->featured()
                ->with([
                    'product:id,name,slug,image',
                    'user:id,name,address',
                ])
                ->latest('id')
                ->take(6)
                ->get();
        });

        return view('storefront.home', compact(
            'banners',
            'collectionBanners',
            'categories',
            'deals',
            'featuredProducts',
            'latestProducts',
            'bundles',
            'branches',
            'reviews'
        ));
    }
}
