<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Admin\Order;

class OrderStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $statusLabels = [
            Order::STATUS_PENDING => 'قيد المراجعة',
            Order::STATUS_UNDER_REVIEW => 'تحت المراجعة (إيصال الدفع)',
            Order::STATUS_CONFIRMED => 'تم التأكيد',
            Order::STATUS_PROCESSING => 'جاري التجهيز',
            Order::STATUS_READY_FOR_PICKUP => 'جاهز للاستلام من الفرع',
            Order::STATUS_ON_THE_WAY => 'في الطريق إليك مع المندوب',
            Order::STATUS_SHIPPED => 'تم الشحن',
            Order::STATUS_DELIVERED => 'تم التوصيل بنجاح',
            Order::STATUS_CANCELLED => 'تم الإلغاء',
        ];

        $statusText = $statusLabels[$this->order->status] ?? $this->order->status;

        return new Envelope(
            subject: "تحديث حالة طلبك رقم #{$this->order->public_order_id} - {$statusText}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.status_changed',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
