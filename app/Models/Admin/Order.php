<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    // Order Statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_UNDER_REVIEW = 'under_review';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    public const STATUS_ON_THE_WAY = 'on_the_way';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';

    // Delivery Types
    public const DELIVERY_TYPE_DELIVERY = 'delivery';
    public const DELIVERY_TYPE_PICKUP = 'pickup';

    // Payment Methods
    public const PAYMENT_METHOD_CASH = 'cash_on_delivery';
    public const PAYMENT_METHOD_INSTAPAY = 'instapay';
    public const PAYMENT_METHOD_BRANCH = 'cash_in_branch';

    protected $fillable = [
        'public_order_id',
        'order_number',
        'user_id',
        'customer_name',
        'client_id',
        'branch_id',
        'total_price',
        'status',
        'delivery_type',
        'address',
        'phone',
        'payment_method',
        'coupon_code',
        'discount_amount',
        'payment_proof_image',
        'instapay_reference',
        'notes',
        'cancel_reason',
        'currency',
        'exchange_rate',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'exchange_rate' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_total',
        'customer_display_name',
        'payment_proof_url',
        'status_label',
        'delivery_type_label',
        'payment_method_label',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUnderReview(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeDelivered(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DELIVERED);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_DELIVERED]);
    }

    // Accessors
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_price, 2) . ' ج.م';
    }

    public function getCustomerDisplayNameAttribute(): string
    {
        if (!empty($this->customer_name)) {
            return $this->customer_name;
        }

        if ($this->user && !empty($this->user->name)) {
            return $this->user->name;
        }

        return __('طلب مباشر (عميل)');
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof_image) {
            return null;
        }

        if (Storage::disk('public')->exists($this->payment_proof_image)) {
            return Storage::disk('public')->url($this->payment_proof_image);
        }

        return null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'قيد الانتظار',
            self::STATUS_UNDER_REVIEW => 'قيد مراجعة التحويل',
            self::STATUS_CONFIRMED => 'تم التأكيد',
            self::STATUS_PROCESSING => 'جاري التجهيز',
            self::STATUS_READY_FOR_PICKUP => 'جاهز للاستلام من الفرع',
            self::STATUS_ON_THE_WAY => 'في الطريق مع المندوب',
            self::STATUS_SHIPPED => 'تم الشحن',
            self::STATUS_DELIVERED => 'تم التوصيل بنجاح',
            self::STATUS_CANCELLED => 'ملغي',
            default => $this->status,
        };
    }

    public function getDeliveryTypeLabelAttribute(): string
    {
        return match ($this->delivery_type) {
            self::DELIVERY_TYPE_PICKUP => 'استلام من الفرع',
            default => 'توصيل للمنزل',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            self::PAYMENT_METHOD_INSTAPAY => 'تحويل إنستاباي (InstaPay)',
            self::PAYMENT_METHOD_BRANCH => 'دفع في الفرع عند الاستلام',
            'whatsapp_checkout' => 'طلب واتساب',
            default => 'الدفع عند الاستلام (كاش)',
        };
    }
}
