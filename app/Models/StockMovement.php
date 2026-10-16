<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'branch_id',
        'type',
        'quantity_delta',
        'quantity_after',
        'reason',
        'reference_type',
        'reference_id',
        'created_by',
    ];

    protected $casts = [
        'quantity_delta' => 'integer',
        'quantity_after' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'purchase' => 'توريد جديد',
            'sale' => 'خصم بيع',
            'return' => 'مرتجع',
            'adjustment' => 'تسوية جردية',
            'damaged' => 'تالف / هالك',
            'reservation' => 'حجز مؤقت',
            'release' => 'إلغاء حجز',
            default => 'حركة مخزنية',
        };
    }

    public function getTypeBadgeClassAttribute(): string
    {
        return match ($this->type) {
            'purchase' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'sale' => 'bg-blue-100 text-blue-800 border-blue-200',
            'return' => 'bg-amber-100 text-amber-800 border-amber-200',
            'adjustment' => 'bg-purple-100 text-purple-800 border-purple-200',
            'damaged' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-stone-100 text-stone-800 border-stone-200',
        };
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
    }
}
