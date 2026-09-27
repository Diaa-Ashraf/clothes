<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\AdminNotification;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        if (AdminNotification::count() === 0) {
            $orders = Order::latest()->take(3)->get();
            foreach ($orders as $order) {
                AdminNotification::createForOrder($order);
            }

            AdminNotification::create([
                'type' => 'message',
                'title' => '💬 رسالة تواصل واستفسار',
                'message' => 'استفسار من عميل بخصوص مواعيد التوصيل لكينج مريوط وشارع الإسعاف',
                'link' => route('admin.contact-messages.index'),
                'icon' => 'mail',
                'is_read' => false,
            ]);

            AdminNotification::create([
                'type' => 'job',
                'title' => '💼 طلب توظيف كاشير',
                'message' => 'تم استلام طلب تقديم جديد لوظيفة كاشير فرع العامرية الرئيسي',
                'link' => route('admin.job-applications.index'),
                'icon' => 'briefcase',
                'is_read' => false,
            ]);
        }
    }
}
