<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductRepository
{
    /**
     * Columns strictly selected for listings to optimize memory and speed
     */
    protected array $selectColumns = [
        'id',
        'name',
        'category_id',
        'image',
        'price',
        'discount_price',
        'quantity',
        'stock_quantity',
        'slug',
        'featured',
        'is_active',
        'is_available',
        'created_at',
    ];

    /**
     * Get 8 featured products for homepage (cached 1 hour)
     */
    public function getFeatured(int $limit = 8): Collection
    {
        return Cache::remember("homepage_featured_products_{$limit}", 3600, function () use ($limit) {
            return Product::query()
                ->select($this->selectColumns)
                ->with(['category'])
                ->featured()
                ->inStock()
                ->ordered()
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get latest products for homepage (cached 30 minutes)
     */
    public function getLatest(int $limit = 12): Collection
    {
        return Cache::remember("homepage_latest_products_{$limit}", 1800, function () use ($limit) {
            return Product::query()
                ->select($this->selectColumns)
                ->with(['category'])
                ->active()
                ->inStock()
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get Best Selling / Top Products for homepage (ordered by order items count or featured/latest)
     */
    public function getBestSellers(int $limit = 8): Collection
    {
        return Cache::remember("homepage_bestsellers_{$limit}", 1800, function () use ($limit) {
            return Product::query()
                ->select($this->selectColumns)
                ->with(['category'])
                ->withCount('orderItems')
                ->active()
                ->inStock()
                ->orderByDesc('order_items_count')
                ->orderByDesc('id')
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get Daily Deals / Discounted products
     */
    public function getDailyDeals(int $limit = 8): Collection
    {
        return Cache::remember("homepage_daily_deals_{$limit}", 1800, function () use ($limit) {
            return Product::query()
                ->select($this->selectColumns)
                ->with(['category', 'deals'])
                ->active()
                ->inStock()
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price');
                    })->orWhereHas('deals', function ($sub) {
                        $sub->active();
                    });
                })
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    /**
     * Get filtered and paginated products for Shop Grid
     */
    public function getFiltered(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::query()
            ->select($this->selectColumns)
            ->with(['category', 'deals'])
            ->active();

        // Filter by Category (Single ID or Array of IDs)
        if (!empty($filters['category_ids']) && is_array($filters['category_ids'])) {
            $query->whereIn('category_id', array_filter($filters['category_ids']));
        } elseif (!empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $query->whereIn('category_id', array_filter($filters['category_id']));
            } else {
                $query->where('category_id', $filters['category_id']);
            }
        }

        // Filter by Featured
        if (!empty($filters['featured'])) {
            $query->where('featured', true);
        }

        // Filter by In Stock only
        if (!empty($filters['in_stock'])) {
            $query->inStock();
        }

        // Filter by Price Range
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('price', '>=', (float)$filters['min_price'])
                  ->orWhere('discount_price', '>=', (float)$filters['min_price'])
                  ->orWhereHas('deals', function ($sub) use ($filters) {
                      $sub->active()->where('deal_price', '>=', (float)$filters['min_price']);
                  });
            });
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] > 0) {
            $query->where(function ($q) use ($filters) {
                $q->where('price', '<=', (float)$filters['max_price'])
                  ->orWhere('discount_price', '<=', (float)$filters['max_price'])
                  ->orWhereHas('deals', function ($sub) use ($filters) {
                      $sub->active()->where('deal_price', '<=', (float)$filters['max_price']);
                  });
            });
        }

        // Search in products
        if (!empty($filters['search'])) {
            $searchTerm = trim($filters['search']);
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filter by Discount only (Products with direct discount_price OR active deals)
        if (!empty($filters['discount_only'])) {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price');
                })->orWhereHas('deals', function ($sub) {
                    $sub->active();
                });
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(discount_price, price) ASC'),
            'price_desc' => $query->orderByRaw('COALESCE(discount_price, price) DESC'),
            'discount' => $query->where(function ($q) {
                $q->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price')
                  ->orWhereHas('deals', fn($sub) => $sub->active());
            })->latest(),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Find single active product by ID or Slug with relations or fail
     */
    public function findProductOrFail(int|string $identifier): Product
    {
        $query = Product::query()
            ->with(['category', 'reviews.user'])
            ->active();

        if (is_numeric($identifier)) {
            return $query->where('id', (int)$identifier)->firstOrFail();
        }

        return $query->where('slug', (string)$identifier)->firstOrFail();
    }
}
