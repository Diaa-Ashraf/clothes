<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            $product->deals()->delete();
            $product->reviews()->delete();
            $product->wishlistItems()->delete();
            $product->images()->delete();
            $product->bundles()->detach();
        });
    }

    protected $fillable = [
        'name',
        'sku',
        'description',
        'keywords',
        'category_id',
        'brand_id',
        'gender',
        'season',
        'material',
        'fit',
        'image',
        'quantity',
        'stock_quantity',
        'price',
        'discount_price',
        'slug',
        'featured',
        'is_weighted',
        'unit_type',
        'is_active',
        'is_available',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'keywords' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'featured' => 'boolean',
        'is_weighted' => 'boolean',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'quantity' => 'integer',
        'stock_quantity' => 'integer',
        'sort_order' => 'integer',
    ];

    protected $appends = ['image_url', 'final_price', 'has_discount', 'discount_percentage'];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://') || str_starts_with($this->image, '//')) {
            return $this->image;
        }

        if (str_starts_with($this->image, '/storage/') || str_starts_with($this->image, 'storage/')) {
            return asset(ltrim($this->image, '/'));
        }

        if (Storage::disk('public')->exists("products/{$this->image}")) {
            return Storage::disk('public')->url("products/{$this->image}");
        }

        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('id', 'asc');
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order', 'asc');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function activeDeal(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Deal::class)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest('id');
    }

    public function bundles(): BelongsToMany
    {
        return $this->belongsToMany(Bundle::class, 'bundle_products')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true)->where('is_active', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('is_available', true)
            ->where(function ($q) {
                $q->where('stock_quantity', '>', 0)
                  ->orWhere('quantity', '>', 0);
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getHasDiscountAttribute(): bool
    {
        if (!is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return true;
        }

        if ($this->relationLoaded('deals')) {
            $activeDeal = $this->deals->first(fn($d) => $d->is_valid);
            return !is_null($activeDeal);
        }

        return $this->deals()->active()->exists();
    }

    public function getFinalPriceAttribute(): float
    {
        if (!is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price) {
            return (float) $this->discount_price;
        }

        if ($this->relationLoaded('deals')) {
            $activeDeal = $this->deals->first(fn($d) => $d->is_valid);
            if ($activeDeal) {
                return (float) $activeDeal->deal_price;
            }
        } else {
            $activeDeal = $this->deals()->active()->first();
            if ($activeDeal) {
                return (float) $activeDeal->deal_price;
            }
        }

        return (float) $this->price;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->has_discount || $this->price <= 0) {
            return 0;
        }

        return (int) round((($this->price - $this->final_price) / $this->price) * 100);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2) . ' ج.م';
    }

    public function getFormattedFinalPriceAttribute(): string
    {
        return number_format($this->final_price, 2) . ' ج.م';
    }

    public function getAvailableStockAttribute(): int
    {
        return max($this->stock_quantity, $this->quantity);
    }
}
