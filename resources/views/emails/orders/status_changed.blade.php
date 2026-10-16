<x-mail::message>
# مرحباً {{ $order->customer_name }} 👋

تم تحديث حالة طلبك رقم **#{{ $order->public_order_id }}** في متجر **{{ settings('site_name', 'أزياء لافيستا') }}**.

<x-mail::panel>
### الحالة الحالية للطلب: **{{ $order->status_label ?? $order->status }}**
@if($order->status === 'on_the_way')
🚚 المندوب في طريقه إليك لتسليم الطلب!
@elseif($order->status === 'ready_for_pickup')
🏬 طلبك جاهز للاستلام الآن من فرعنا.
@elseif($order->status === 'delivered')
🎉 تم تسليم الطلب بنجاح. شكراً لثقتك بمتجر {{ settings('site_name', 'أزياء لافيستا') }}!
@elseif($order->status === 'cancelled')
⚠️ تم إلغاء الطلب. @if($order->cancel_reason) سبب الإلغاء: {{ $order->cancel_reason }} @endif
@endif
</x-mail::panel>

### تفاصيل الطلب:
- **إجمالي الطلب:** {{ number_format($order->total_price, 2) }} ج.م
- **طريقة الاستلام:** {{ $order->delivery_type === 'pickup' ? 'استلام من الفرع' : 'توصيل للمنزل' }}
@if($order->address && $order->delivery_type !== 'pickup')
- **العنوان:** {{ $order->address }}
@endif

<x-mail::button :url="route('orders.track', $order->public_order_id)">
تتبع تفاصيل الطلب مباشرة
</x-mail::button>

إذا كان لديك أي استفسار، لا تتردد في التواصل معنا عبر واتساب أو الاتصال بخدمة العملاء.

مع خالص التحيات،<br>
فريق عمل **{{ settings('site_name', config('app.name', 'أزياء لافيستا')) }}**
</x-mail::message>
