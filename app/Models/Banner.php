<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'url',
        'button_text',
        'bg_color',
        'position',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeCollection($query)
    {
        return $query->where('position', 'home_collection');
    }

    public function scopeSlider($query)
    {
        return $query->where('position', 'main_slider');
    }

    // Helper to get image URL
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return asset('assets/site/img/product-placeholder.png');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists("banners/{$this->image}")) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url("banners/{$this->image}");
        }

        return asset('storage/' . ltrim($this->image, '/'));
    }
}
