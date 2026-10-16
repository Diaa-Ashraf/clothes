{{-- resources/views/storefront/checkout/confirmation.blade.php --}}
@extends('layouts.storefront')

@section('title', 'تم تأكيد طلبك بنجاح | ' . $order->public_order_id)

@section('content')
<div class="py-12 bg-[#FBF6EA] min-h-screen">
    <div class="container mx-auto px-4 max-w-3xl space-y-8">
        
        <!-- Success Celebration Card -->
        <div class="bg-white rounded-3xl p-8 sm:p-12 border border-[#EADFC8] shadow-sm text-center space-y-5">
            <div class="w-20 h-20 rounded-full bg-[#F3E9D2] text-[#A9552F] border border-[#CDB78F] text-3xl flex items-center justify-center mx-auto shadow-inner">
                ✓
            </div>

            <div class="space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#6B5642] border border-[#EADFC8]">
                    تم تسجيل الطلب في البوتيك بنجاح
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-[#2B2118]">
                    شكراً لك، تم استلام طلبك!
                </h1>
                <p class="text-xs sm:text-sm text-[#8F7B63] max-w-md mx-auto leading-relaxed">
                    فريق {{ settings('site_name', 'أزياء لافيستا') }} يقوم بتجهيز وتغليف قطع ملابسك بعناية فائقة لضمان أعلى مستويات الأناقة وتوافق المقاسات.
                </p>
            </div>

            <!-- Public Order ID Card with Copy Button -->
            <div class="bg-[#FBF6EA] p-4 rounded-2xl border border-[#EADFC8] inline-flex flex-col sm:flex-row items-center gap-3 sm:gap-6 mx-auto"
                 x-data="{ copied: false }">
                <div class="text-right">
                    <span class="text-[10px] text-[#8F7B63] block">رقم الطلب المرجعي:</span>
                    <span class="text-lg font-mono font-black text-[#2B2118] tracking-wider">
                        {{ $order->public_order_id }}
                    </span>
                </div>

                <button type="button" 
                        @click="
                            navigator.clipboard.writeText('{{ $order->public_order_id }}');
                            copied = true;
                            setTimeout(() => copied = false, 2500);
                            toastr.success('تم نسخ رقم الطلب');
                        "
                        class="px-3.5 py-1.5 rounded-xl bg-white border border-[#EADFC8] hover:bg-[#F3E9D2] text-xs font-bold text-[#2B2118] transition-colors shadow-xs flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4 text-[#8F7B63]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span x-text="copied ? 'تم النسخ!' : 'نسخ الرقم'">نسخ الرقم</span>
                </button>
            </div>

            <!-- InstaPay Review Alert (If InstaPay selected) -->
            @if($order->payment_method === 'instapay')
            <div class="p-4 rounded-2xl bg-[#F3E9D2] border border-[#CDB78F] text-[#2B2118] text-xs text-right space-y-1 max-w-lg mx-auto">
                <div class="font-bold flex items-center gap-1.5 text-[#A9552F]">
                    <span>ℹ</span>
                    <span>طلبك قيد المراجعة والتحقق من تحويل إنستاباي</span>
                </div>
                <p class="text-[11px] leading-relaxed text-[#6B5642]">
                    سيقوم فريق الحسابات بمطابقة إيصال التحويل المرفق وتأكيد خروج الشحنة في أقرب وقت.
                </p>
            </div>
            @endif
        </div>

        <!-- Order Information & Summary Breakdown -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#EADFC8] shadow-sm space-y-6">
            <h2 class="font-black text-base text-[#2B2118] border-b border-[#EADFC8] pb-3">
                تفاصيل الطلب والاستلام
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3.5 rounded-2xl bg-[#FBF6EA] border border-[#EADFC8] space-y-1">
                    <span class="text-[10px] text-[#8F7B63] block">اسم العميل ورقم الهاتف:</span>
                    <span class="font-bold text-[#2B2118] block">{{ $order->customer_name }}</span>
                    <span class="text-[#6B5642] font-mono" dir="ltr">{{ $order->phone }}</span>
                </div>

                <div class="p-3.5 rounded-2xl bg-[#FBF6EA] border border-[#EADFC8] space-y-1">
                    <span class="text-[10px] text-[#8F7B63] block">طريقة الاستلام:</span>
                    @if($order->delivery_type === 'pickup')
                        <span class="font-bold text-[#A9552F] block">🏬 استلام وتجربة في البوتيك</span>
                        <span class="text-[#6B5642]">{{ $order->branch?->name ?: 'الفرع المحدد' }} ({{ $order->branch?->address }})</span>
                    @else
                        <span class="font-bold text-[#2B2118] block">🚚 شحن وتوصيل للمنزل</span>
                        <span class="text-[#6B5642]">{{ $order->address }}</span>
                    @endif
                </div>

                <div class="p-3.5 rounded-2xl bg-[#FBF6EA] border border-[#EADFC8] space-y-1">
                    <span class="text-[10px] text-[#8F7B63] block">طريقة الدفع:</span>
                    <span class="font-bold text-[#2B2118] block">
                        {{ $order->payment_method === 'instapay' ? '💳 تحويل فوري عبر إنستاباي' : '💵 الدفع كاش عند الاستلام والمعاينة' }}
                    </span>
                    @if($order->instapay_reference)
                        <span class="text-[10px] text-[#8F7B63]">المرجع: {{ $order->instapay_reference }}</span>
                    @endif
                </div>

                <div class="p-3.5 rounded-2xl bg-[#FBF6EA] border border-[#EADFC8] space-y-1">
                    <span class="text-[10px] text-[#8F7B63] block">حالة الطلب:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#F3E9D2] text-[#6B5642]">
                        {{ $order->status_label ?? 'قيد التجهيز والتنسيق' }}
                    </span>
                </div>
            </div>

            <!-- Ordered Items List -->
            <div class="pt-4 border-t border-[#EADFC8] space-y-3">
                <h3 class="font-bold text-xs text-[#2B2118]">القطع المطلوبة:</h3>
                <div class="divide-y divide-[#EADFC8]/60">
                    @foreach($order->orderItems as $item)
                    <div class="py-2.5 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-[#FBF6EA] text-[11px] font-bold flex items-center justify-center border border-[#EADFC8] text-[#2B2118]">
                                {{ $item->quantity }}x
                            </span>
                            <div>
                                <span class="font-bold text-[#2B2118] block">{{ $item->product?->name ?: 'قطعة أزياء' }}</span>
                                @if($item->portion_name)
                                    <span class="text-[11px] text-[#8F7B63]">{{ $item->portion_name }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="font-black text-[#A9552F] tabular-nums">
                            {{ number_format($item->total_price, 2) }} ج.م
                        </span>
                    </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-[#EADFC8] flex items-center justify-between font-black text-sm text-[#2B2118]">
                    <span>الإجمالي الكلي:</span>
                    <span class="text-lg text-[#A9552F] tabular-nums">
                        {{ number_format($order->total_price, 2) }} ج.م
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-6 border-t border-[#EADFC8] flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('site.home') }}" 
                   class="w-full sm:w-auto py-3 px-6 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs text-center transition-all shadow-md">
                    العودة للبوتيك ومتابعة التسوق
                </a>

                @php
                    $whatsappMsg = urlencode("مرحباً " . settings('site_name', 'أزياء لافيستا') . "، أود متابعة طلبي رقم: {$order->public_order_id}");
                    $storeWhatsapp = preg_replace('/[^0-9]/', '', settings('store_whatsapp', '201000000000'));
                @endphp
                <a href="https://wa.me/{{ $storeWhatsapp }}?text={{ $whatsappMsg }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-full sm:w-auto py-3 px-6 rounded-2xl bg-[#25D366] hover:bg-[#20ba59] text-white font-bold text-xs text-center transition-all flex items-center justify-center gap-2 shadow-md">
                    <span>متابعة الطلب عبر واتساب</span>
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
