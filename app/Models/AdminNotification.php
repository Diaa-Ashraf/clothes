<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'link',
        'icon',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Human-readable Arabic time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at ? $this->created_at->locale('ar')->diffForHumans() : '';
    }

    /**
     * Helper to create an order notification
     */
    public static function createForOrder(Order $order): self
    {
        $orderNumber = $order->order_number ?? '#' . $order->id;
        $customerName = $order->customer_name ?? 'عميل جديد';
        $total = number_format($order->total_price ?? 0, 2);

        return self::create([
            'type' => 'order',
            'title' => '🛒 طلب شراء جديد ' . $orderNumber,
            'message' => "طلب جديد من {$customerName} بقيمة {$total} ج.م",
            'link' => route('orders.show', $order->id),
            'icon' => 'shopping-cart',
            'data' => [
                'order_id' => $order->id,
                'order_number' => $orderNumber,
                'customer_name' => $customerName,
                'total' => $order->total_price,
            ],
            'is_read' => false,
        ]);
    }

    /**
     * Helper to create a contact message notification
     */
    public static function createForMessage(ContactMessage $message): self
    {
        $sender = $message->name ?? 'زائر';
        $subject = $message->subject ?? 'استفسار جديد';

        return self::create([
            'type' => 'message',
            'title' => '💬 رسالة تواصل جديدة',
            'message' => "رسالة جديدة من {$sender}: {$subject}",
            'link' => route('admin.contact-messages.show', $message->id),
            'icon' => 'mail',
            'data' => [
                'message_id' => $message->id,
                'sender' => $sender,
                'phone' => $message->phone,
            ],
            'is_read' => false,
        ]);
    }

    /**
     * Helper to create a job application notification
     */
    public static function createForJobApplication(JobApplication $application): self
    {
        $applicant = $application->full_name ?? 'متقدم جديد';
        $position = $application->position_applied ?? 'وظيفة';

        return self::create([
            'type' => 'job',
            'title' => '💼 طلب توظيف جديد',
            'message' => "طلب توظيف جديد من {$applicant} لوظيفة ({$position})",
            'link' => route('admin.job-applications.show', $application->id),
            'icon' => 'briefcase',
            'data' => [
                'application_id' => $application->id,
                'applicant' => $applicant,
                'position' => $position,
            ],
            'is_read' => false,
        ]);
    }

    /**
     * Helper to create a review notification
     */
    public static function createForReview(Review $review): self
    {
        $name = $review->name ?? 'عميل';
        $stars = str_repeat('⭐', $review->rating ?? 5);

        return self::create([
            'type' => 'review',
            'title' => '⭐ تقييم جديد للمنتج',
            'message' => "تقييم {$stars} من {$name}",
            'link' => route('admin.products.index'),
            'icon' => 'star',
            'data' => [
                'review_id' => $review->id,
                'rating' => $review->rating,
            ],
            'is_read' => false,
        ]);
    }
}
