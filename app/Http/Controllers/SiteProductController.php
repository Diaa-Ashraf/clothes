<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Models\Category;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SiteProductController extends Controller
{
    public function __construct(
        protected ProductRepository $productRepo,
        protected WhatsappService $whatsappService
    ) {}

    public function shopGrid(Request $request): View
    {
        $selectedCategories = $request->input('categories', $request->input('category', []));
        if (!is_array($selectedCategories)) {
            $selectedCategories = $selectedCategories ? [$selectedCategories] : [];
        }

        $filters = [
            'category_ids' => $selectedCategories,
            'category_id' => $selectedCategories,
            'featured' => $request->boolean('featured'),
            'discount_only' => $request->boolean('discount_only') || $request->input('sort') === 'discount',
            'bundles_only' => $request->boolean('bundles_only'),
            'in_stock' => $request->boolean('in_stock', true),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'search' => $request->input('search'),
            'sort' => $request->input('sort', 'latest'),
        ];

        $bundlesQuery = \App\Models\Bundle::with(['products'])->active();
        
        if (!empty($filters['search'])) {
            $bundlesQuery->where('name', 'like', '%' . trim($filters['search']) . '%');
        }
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $bundlesQuery->where('bundle_price', '>=', (float)$filters['min_price']);
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] > 0) {
            $bundlesQuery->where('bundle_price', '<=', (float)$filters['max_price']);
        }

        $bundles = $bundlesQuery->ordered()->get();
        $products = $this->productRepo->getFiltered($filters, 16);
        
        $categories = \Illuminate\Support\Facades\Cache::remember('site_all_categories', 1800, function () {
            return Category::query()
                ->select(['id', 'name', 'slug', 'image'])
                ->withCount('activeProducts')
                ->get();
        });

        return view('site.shop_grid', compact('products', 'categories', 'filters', 'selectedCategories', 'bundles'));
    }

    public function show(int|string $id): View
    {
        $product = $this->productRepo->findProductOrFail($id);

        $whatsappInquiryUrl = $this->whatsappService->buildProductInquiryUrl(
            $product->name,
            $product->final_price,
            $product->image_url,
            auth()->user()
        );

        $relatedProducts = $this->productRepo->getFiltered([
            'category_id' => $product->category_id,
        ], 4);

        return view('site.product_details', compact('product', 'whatsappInquiryUrl', 'relatedProducts'));
    }

    public function getProductByCategory(int $id): View
    {
        $filters = ['category_id' => $id];
        $products = $this->productRepo->getFiltered($filters, 12);
        $categories = Category::withCount('activeProducts')->get();
        $currentCategory = Category::findOrFail($id);

        return view('site.shop_grid', compact('products', 'categories', 'filters', 'currentCategory'));
    }

    public function searchAjax(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = trim((string)$request->input('q', ''));
        if (empty($query)) {
            return response()->json(['results' => []]);
        }

        $products = $this->productRepo->getFiltered(['search' => $query], 6);

        $results = [];
        foreach ($products as $product) {
            $priceUsd = (float)$product->final_price;
            $results[] = [
                'id' => $product->id,
                'name' => $product->name,
                'url' => route('product.details', $product->slug ?? $product->id),
                'image_url' => $product->image_url,
                'price_usd' => $priceUsd,
                'formatted_price_usd' => number_format($priceUsd, 2) . ' ج.م',
            ];
        }

        return response()->json(['results' => $results]);
    }
}
