<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MagazineItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'magazine_id',
        'product_id',
        'original_price',
        'deal_price',
        'discount_percent',
        'badge_text',
        'sort_order',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'deal_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function magazine(): BelongsTo
    {
        return $this->belongsTo(Magazine::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return number_format($this->original_price, 2);
    }

    public function getFormattedDealPriceAttribute(): string
    {
        return number_format($this->deal_price, 2);
    }

    public function getSavingsAmountAttribute(): float
    {
        return max(0, round($this->original_price - $this->deal_price, 2));
    }

    public function getFormattedSavingsAttribute(): string
    {
        return number_format($this->savings_amount, 2);
    }
}
