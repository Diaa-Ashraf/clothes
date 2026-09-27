<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'branch_id',
        'position',
        'cv_file',
        'message',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['cv_url', 'status_label'];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function getCvUrlAttribute(): ?string
    {
        if (!$this->cv_file) {
            return null;
        }

        if (Storage::disk('public')->exists($this->cv_file)) {
            return Storage::disk('public')->url($this->cv_file);
        }

        return null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد الانتظار',
            'reviewed' => 'تمت المراجعة',
            'accepted' => 'مقبول',
            'rejected' => 'مرفوض',
            default => 'غير محدد',
        };
    }
}
