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
     * Normalize Arabic text variations (alef, teh marbuta, yeh)
     */
    protected function normalizeArabic(string $text): string
    {
        $text = str_replace(['أ', 'إ', 'آ'], 'ا', $text);
        $text = str_replace('ة', 'ه', $text);
        $text = str_replace('ى', 'ي', $text);
        return trim($text);
    }

    /**
     * Apply intelligent Arabic multi-token search
     */
    protected function applySearch($query, string $queryText)
    {
        $rawQuery = trim($queryText);
        if (empty($rawQuery)) {
            return $query;
        }

        $terms = array_filter(explode(' ', $rawQuery), fn($t) => mb_strlen(trim($t)) > 0);

        return $query->where(function ($q) use ($terms, $rawQuery) {
            // 1. Direct phrase search
            $q->where(function ($sub) use ($rawQuery) {
                $sub->where('name', 'like', "%{$rawQuery}%")
                    ->orWhere('description', 'like', "%{$rawQuery}%");
            });

            // 2. Tokenized multi-term search with Arabic letter variants
            foreach ($terms as $term) {
                $variants = [$term];
                $norm = $this->normalizeArabic($term);
                $variants[] = $norm;
                $variants[] = 'أ' . mb_substr($norm, 1);
                $variants[] = 'إ' . mb_substr($norm, 1);
                $variants[] = 'ا' . mb_substr($norm, 1);
                $variants[] = $term . 'ة';
                $variants[] = $term . 'ه';
                
                $variants = array_unique(array_filter($variants));

                $q->orWhere(function ($sub) use ($variants) {
                    foreach ($variants as $v) {
                        $sub->orWhere('name', 'like', "%{$v}%")
                            ->orWhere('description', 'like', "%{$v}%");
                    }
                });
            }
        });
    }

    /**
     * Display the full search results page.
     */
    public function index(Request $request): View
    {
        $queryText = trim((string) $request->input('q', $request->input('search', '')));

        $productsQuery = Product::select([
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
        ->with(['category:id,name']);

        if (!empty($queryText)) {
            $this->applySearch($productsQuery, $queryText);
        }

        $products = $productsQuery
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('storefront.search', compact('products', 'queryText'));
    }

    /**
     * Live search autocomplete returning top 6 matching items as JSON.
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $queryText = trim((string) $request->input('q', $request->input('search', '')));

        if (mb_strlen($queryText) < 1) {
            return response()->json(['results' => []]);
        }

        $productsQuery = Product::select([
            'id',
            'name',
            'slug',
            'image',
            'price',
            'discount_price',
            'category_id',
        ])
        ->active()
        ->with(['category:id,name']);

        $this->applySearch($productsQuery, $queryText);

        $products = $productsQuery
            ->take(6)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => format_currency($p->final_price ?? $p->price),
                    'image_url' => $p->image_url ?? asset('assets/site/img/product/product-1.jpg'),
                    'category' => $p->category?->name ?? 'بقالة وألبان',
                    'url' => route('product.details', $p->slug ?? $p->id),
                ];
            });

        return response()->json(['results' => $products]);
    }
}
