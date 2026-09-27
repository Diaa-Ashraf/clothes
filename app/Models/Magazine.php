<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Magazine extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'badge_text',
        'subtitle',
        'branch_note',
        'cover_image',
        'starts_at',
        'ends_at',
        'is_active',
        'show_countdown',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_countdown' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($magazine) {
            if (empty($magazine->slug)) {
                $magazine->slug = Str::slug($magazine->title) . '-' . Str::random(5);
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(MagazineItem::class)->orderBy('sort_order', 'asc');
    }

    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(
            Product::class,
            MagazineItem::class,
            'magazine_id',
            'id',
            'id',
            'product_id'
        );
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    public function getIsCurrentlyActiveAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getFormattedPeriodAttribute(): string
    {
        $start = $this->starts_at ? $this->starts_at->translatedFormat('d F') : now()->startOfWeek()->translatedFormat('d F');
        $end = $this->ends_at ? $this->ends_at->translatedFormat('d F Y') : now()->addDays(6)->translatedFormat('d F Y');

        return "من {$start} حتى {$end}";
    }
}
