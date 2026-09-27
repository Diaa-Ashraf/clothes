<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Deal;
use App\Models\Bundle;
use App\Repositories\ProductRepository;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepo
    ) {}

    /**
     * Homepage with structured sections
     */
    public function index(): View
    {
        // 1. Hero banners (cached 1 hour)
        $banners = \Illuminate\Support\Facades\Cache::remember('homepage_banners', 3600, function () {
            return Banner::where('status', true)->orderBy('position', 'asc')->orderBy('id', 'desc')->get();
        });

        // 2. Categories with active products count (cached 30 minutes)
        $categories = \Illuminate\Support\Facades\Cache::remember('site_homepage_categories', 1800, function () {
            return Category::query()
                ->select(['id', 'name', 'slug', 'image'])
                ->withCount('activeProducts')
                ->get();
        });

        // 3. Featured Products / Trending (الأكثر رواجاً / المميزة)
        $trendingProducts = $this->productRepo->getFeatured(16);

        // 4. Daily Flash Deals (العروض اليومية)
        $dealsProducts = $this->productRepo->getDailyDeals(16);

        // Fallback to Deals relation if deals table has items
        $deals = \Illuminate\Support\Facades\Cache::remember('homepage_deals_list', 1800, function () {
            return Deal::query()
                ->with(['product.category'])
                ->active()
                ->ordered()
                ->take(16)
                ->get();
        });

        // 5. Best Sellers (الأكثر مبيعاً)
        $bestSellers = $this->productRepo->getBestSellers(16);

        // 6. Latest Arrivals (المنتجات الجديدة)
        $latestProducts = $this->productRepo->getLatest(16);

        // 7. Featured Categories (الفئات المميزة - 4 to 8 categories with products)
        $featuredCategories = $categories->filter(fn($c) => $c->active_products_count > 0)->take(8);
        if ($featuredCategories->isEmpty()) {
            $featuredCategories = $categories->take(8);
        }

        // Bundles (عروض البكجات - cached 30 minutes)
        $bundles = \Illuminate\Support\Facades\Cache::remember('homepage_bundles', 1800, function () {
            return Bundle::query()
                ->with(['products'])
                ->active()
                ->ordered()
                ->take(4)
                ->get();
        });

        return view('site.index', compact(
            'banners',
            'categories',
            'featuredCategories',
            'trendingProducts',
            'dealsProducts',
            'deals',
            'bestSellers',
            'latestProducts',
            'bundles'
        ));
    }

    public function getcategory(): View
    {
        $categories = Category::withCount('activeProducts')->get();
        return view('site.categories', compact('categories'));
    }
}
