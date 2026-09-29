<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display the full supermarket shop catalog with multi-filters and pagination.
     */
    public function shop(Request $request): View
    {
        $query = Product::select([
            'id',
            'name',
            'slug',
            'description',
            'image',
            'price',
            'discount_price',
            'featured',
            'is_available',
            'stock_quantity',
            'quantity',
            'category_id',
            'is_weighted',
            'unit_type',
            'created_at',
        ])
        ->active()
        ->with(['category:id,name']);

        // Filter: Category
        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->category_id);
        }

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

        // Filter: Search keyword
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->query('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'asc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(16)->withQueryString();

        $categories = Category::select(['id', 'name', 'slug'])
            ->active()
            ->withCount(['products' => fn($q) => $q->active()])
            ->ordered()
            ->get();

        return view('storefront.products.shop', compact('products', 'categories', 'sort'));
    }

    /**
     * Display a specific product details page.
     */
    public function show(int|string $id): View
    {
        $product = is_numeric($id)
            ? Product::with(['category', 'images', 'reviews' => fn($q) => $q->approved()->latest()->with('user:id,name')])->findOrFail($id)
            : Product::with(['category', 'images', 'reviews' => fn($q) => $q->approved()->latest()->with('user:id,name')])->where('slug', $id)->firstOrFail();

        // Related products from the same category
        $relatedProducts = Product::select([
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
            'is_weighted',
            'unit_type',
        ])
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->active()
        ->with(['category:id,name'])
        ->take(4)
        ->get();

        return view('storefront.products.show', compact('product', 'relatedProducts'));
    }
}
