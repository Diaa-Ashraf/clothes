<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display the full search results page.
     */
    public function index(Request $request): View
    {
        $queryText = trim((string) $request->input('q', $request->input('search', '')));

        $products = Product::select([
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
        ])
        ->active()
        ->with(['category:id,name'])
        ->when($queryText, function ($q) use ($queryText) {
            $q->where(function ($sub) use ($queryText) {
                $sub->where('name', 'like', "%{$queryText}%")
                    ->orWhere('description', 'like', "%{$queryText}%");
            });
        })
        ->latest('id')
        ->paginate(16)
        ->withQueryString();

        return view('storefront.search', compact('products', 'queryText'));
    }

    /**
     * Live search autocomplete returning top 5 matching items as JSON.
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $queryText = trim((string) $request->input('q', ''));

        if (mb_strlen($queryText) < 2) {
            return response()->json(['results' => []]);
        }

        $products = Product::select([
            'id',
            'name',
            'slug',
            'image',
            'price',
            'discount_price',
            'category_id',
        ])
        ->active()
        ->with(['category:id,name'])
        ->where(function ($q) use ($queryText) {
            $q->where('name', 'like', "%{$queryText}%")
              ->orWhere('description', 'like', "%{$queryText}%");
        })
        ->take(5)
        ->get()
        ->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'price' => format_currency($p->final_price ?? $p->price),
                'image_url' => $p->image_url,
                'category' => $p->category?->name,
                'url' => route('product.details', $p->id),
            ];
        });

        return response()->json(['results' => $products]);
    }
}
