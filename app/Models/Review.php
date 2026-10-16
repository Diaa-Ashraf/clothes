<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'reviewer_name',
        'governorate',
        'rating',
        'comment',
        'approved',
        'is_featured'
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'approved' => 'boolean',
        'is_featured' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getAuthorNameAttribute(): string
    {
        if (!empty($this->reviewer_name)) {
            return $this->reviewer_name;
        }

        return $this->user?->name ?? 'عميل موثق';
    }

    public function getAuthorLocationAttribute(): string
    {
        if (!empty($this->governorate)) {
            return $this->governorate;
        }

        return $this->user?->address ?? 'جمهورية مصر العربية';
    }
}