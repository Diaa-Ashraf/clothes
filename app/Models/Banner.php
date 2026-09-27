<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'url',
        'position',
        'status',
    ];

    // Helper to get image URL
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists("banners/{$this->image}")) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url("banners/{$this->image}");
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->image)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->image);
        }

        return asset('storage/banners/' . $this->image);
    }
}
