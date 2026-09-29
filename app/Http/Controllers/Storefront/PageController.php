<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the About Us page with Alban Al-Tawheed's story, values & branches overview.
     */
    public function about(): View
    {
        $branches = Branch::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        $settings = Setting::all()->pluck('formatted_value', 'key');

        return view('storefront.about', compact('branches', 'settings'));
    }

    /**
     * Display all physical branches with working hours, contact info and maps.
     */
    public function branches(): View
    {
        $branches = Branch::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return view('storefront.branches.index', compact('branches'));
    }

    /**
     * Display Terms & Conditions page.
     */
    public function terms(): View
    {
        return view('storefront.pages.terms');
    }

    /**
     * Display Privacy Policy page.
     */
    public function privacy(): View
    {
        return view('storefront.pages.privacy');
    }

    /**
     * Display Return & Exchange Policy page.
     */
    public function returnPolicy(): View
    {
        return view('storefront.pages.return-policy');
    }

    /**
     * Generate dynamic XML sitemap for search engines.
     */
    public function sitemap(): \Illuminate\Http\Response
    {
        $urls = [];

        // Static Pages
        $staticRoutes = [
            'site.home' => ['priority' => '1.0', 'changefreq' => 'daily'],
            'shop' => ['priority' => '0.9', 'changefreq' => 'daily'],
            'offers.index' => ['priority' => '0.9', 'changefreq' => 'daily'],
            'categories.index' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            'branches.index' => ['priority' => '0.8', 'changefreq' => 'weekly'],
            'pages.about' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            'contact.index' => ['priority' => '0.7', 'changefreq' => 'monthly'],
            'careers.index' => ['priority' => '0.6', 'changefreq' => 'weekly'],
            'pages.terms' => ['priority' => '0.4', 'changefreq' => 'yearly'],
            'pages.privacy' => ['priority' => '0.4', 'changefreq' => 'yearly'],
            'pages.return-policy' => ['priority' => '0.4', 'changefreq' => 'yearly'],
        ];

        foreach ($staticRoutes as $route => $meta) {
            $urls[] = [
                'loc' => route($route),
                'lastmod' => now()->toDateString(),
                'changefreq' => $meta['changefreq'],
                'priority' => $meta['priority'],
            ];
        }

        // Active Categories
        $categories = \App\Models\Category::select(['id', 'updated_at'])->get();
        foreach ($categories as $cat) {
            $urls[] = [
                'loc' => route('category.product', $cat->id),
                'lastmod' => $cat->updated_at ? $cat->updated_at->toDateString() : now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // Active Products
        $products = \App\Models\Product::select(['id', 'slug', 'updated_at'])
            ->where('is_active', true)
            ->where('is_available', true)
            ->latest('updated_at')
            ->take(500)
            ->get();

        foreach ($products as $prod) {
            $urls[] = [
                'loc' => route('product.show', $prod->slug ?: $prod->id),
                'lastmod' => $prod->updated_at ? $prod->updated_at->toDateString() : now()->toDateString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return response()->view('storefront.pages.sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}
