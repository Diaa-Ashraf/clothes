<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of all active supermarket categories.
     */
    public function index(): View
    {
        $categories = Cache::remember('storefront_all_categories_page_' . request('page', 1), 1800, function () {
            return Category::select(['id', 'name', 'slug', 'image', 'description'])
                ->active()
                ->withCount(['products' => function ($q) {
                    $q->active();
                }])
                ->ordered()
                ->paginate(16);
        });

        return view('storefront.categories.index', compact('categories'));
    }

    /**
     * Display products belonging to a specific category with filters.
     */
    public function show(Request $request, int|string $id): View
    {
        // Find by ID or Slug
        $category = is_numeric($id)
            ? Category::findOrFail($id)
            : Category::where('slug', $id)->firstOrFail();

        $query = Product::select([
            'id',
            'name',
            'slug',
            'image',
            'price',
            'discount_price',
            'featured',
            'is_available',
            'stock_quantity',
            'quantity',
            'category_id',
            'created_at',
        ])
        ->where('category_id', $category->id)
        ->active()
        ->with(['category:id,name']);

        // Filter: Deals only
        if ($request->boolean('deals_only')) {
            $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
        }

        // Filter: In stock only
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // Filter: Price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Sort
        $sort = $request->query('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(16)->withQueryString();

        // Other categories for sidebar (cached for 1 hour)
        $otherCategories = Cache::remember('storefront_sidebar_categories', 3600, function () {
            return Category::select(['id', 'name', 'slug'])
                ->active()
                ->withCount(['products' => fn($q) => $q->active()])
                ->ordered()
                ->get();
        });

        return view('storefront.categories.show', compact('category', 'products', 'otherCategories', 'sort'));
    }
}
