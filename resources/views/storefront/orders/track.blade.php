{{-- resources/views/storefront/orders/track.blade.php --}}
@extends('layouts.storefront')

@section('title', 'تتبع حالة الطلب | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-10 bg-[#FBF6EA] min-h-[85vh]" dir="rtl">
    <div class="container mx-auto px-4 max-w-2xl space-y-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs text-[#8F7B63]">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">تتبع الطلب</span>
        </nav>

        <!-- Search Order Card -->
        <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-10 border border-[#EADFC8] shadow-xs text-center space-y-5">
            <div class="w-16 h-16 rounded-full bg-[#F3E9D2] text-[#382416] text-2xl flex items-center justify-center mx-auto border border-[#CDB78F] shadow-inner">
                🔍
            </div>

            <div class="space-y-1.5">
                <h1 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">تتبع حالة طلبك في أي وقت</h1>
                <p class="text-xs text-[#8F7B63] max-w-md mx-auto">
                    أدخل رقم الطلب المرجعي لمتابعة مرحلة التجهيز والشحن خطوة بخطوة بدون الحاجة لتسجيل الدخول
                </p>
            </div>

            <form action="{{ route('orders.track') }}" method="GET" class="relative max-w-lg mx-auto">
                <input type="text" 
                       name="q" 
                       value="{{ $searchTerm }}" 
                       placeholder="مثال: TWH-20261014-XXXX" 
                       required
                       dir="ltr"
                       class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] focus:border-[#382416] focus:bg-white rounded-full py-3.5 pr-4 pl-32 text-xs sm:text-sm font-mono font-bold text-[#2B2118] outline-none transition-all">
                
                <button type="submit" 
                        class="absolute left-1.5 top-1/2 -translate-y-1/2 py-2.5 px-6 rounded-full bg-[#382416] text-[#FBF6EA] font-bold text-xs shadow-sm hover:bg-[#2B2118] active:scale-95 transition-all cursor-pointer">
                    تتبع الآن
                </button>
            </form>
        </div>

        <!-- Tracking Result Area -->
        @if($searched)
            @if($order)
                @php
                    $currentStep = match($order->status) {
                        'pending' => 1,
                        'under_review', 'confirmed' => 2,
                        'processing' => 3,
                        'on_the_way', 'ready_for_pickup' => 4,
                        'delivered' => 5,
                        default => 1,
                    };
                    $isCancelled = ($order->status === 'cancelled');
                @endphp

                <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-8 border border-[#EADFC8] shadow-xs space-y-6">
                    
                    <!-- Header with Public ID & Status -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EADFC8] pb-4">
                        <div class="space-y-0.5">
                            <span class="text-[10px] text-[#8F7B63] block">رقم الطلب المرجعي:</span>
                            <span class="font-mono font-bold text-lg text-[#382416]">{{ $order->public_order_id }}</span>
                        </div>

                        <div>
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Stepper Timeline -->
                    @if($isCancelled)
                        <div class="p-4 rounded-2xl bg-[#A9552F]/10 border border-[#A9552F]/30 text-[#A9552F] text-xs text-center space-y-1">
                            <span class="font-bold text-sm block">تم إلغاء هذا الطلب</span>
                            @if($order->cancel_reason)
                                <p class="text-[11px]">سبب الإلغاء: {{ $order->cancel_reason }}</p>
                            @endif
                        </div>
                    @else
                        <div class="py-4">
                            <div class="grid grid-cols-5 gap-1.5 text-center">
                                
                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 1 ? 'bg-[#382416] text-[#FBF6EA]' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                                        1
                                    </div>
                                    <span class="text-[10px] font-bold {{ $currentStep >= 1 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                                        استلام الطلب
                                    </span>
                                </div>

                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 2 ? 'bg-[#382416] text-[#FBF6EA]' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                                        2
                                    </div>
                                    <span class="text-[10px] font-bold {{ $currentStep >= 2 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                                        تم التأكيد
                                    </span>
                                </div>

                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 3 ? 'bg-[#382416] text-[#FBF6EA]' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                                        3
                                    </div>
                                    <span class="text-[10px] font-bold {{ $currentStep >= 3 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                                        جاري التجهيز
                                    </span>
                                </div>

                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 4 ? 'bg-[#382416] text-[#FBF6EA]' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                                        4
                                    </div>
                                    <span class="text-[10px] font-bold {{ $currentStep >= 4 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                                        {{ $order->delivery_type === 'pickup' ? 'جاهز بالبوتيك' : 'في طريق الشحن' }}
                                    </span>
                                </div>

                                <div class="flex flex-col items-center gap-1.5">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 5 ? 'bg-[#382416] text-[#FBF6EA]' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                                        5
                                    </div>
                                    <span class="text-[10px] font-bold {{ $currentStep >= 5 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                                        تم التسليم
                                    </span>
                                </div>

                            </div>
                        </div>
                    @endif

                    <!-- Order Details Summary -->
                    <div class="space-y-3 pt-3 border-t border-[#EADFC8] text-xs">
                        <div class="flex items-center justify-between text-[#6B5642]">
                            <span>تاريخ التسجيل:</span>
                            <span class="font-bold text-[#2B2118]">{{ $order->created_at->translatedFormat('d F Y - h:i A') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[#6B5642]">
                            <span>نوع الاستلام:</span>
                            <span class="font-bold text-[#2B2118]">{{ $order->delivery_type_label }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[#6B5642]">
                            <span>طريقة الدفع:</span>
                            <span class="font-bold text-[#2B2118]">{{ $order->payment_method_label }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[#6B5642] pt-2 border-t border-[#EADFC8]">
                            <span class="font-bold text-[#2B2118]">إجمالي الطلب:</span>
                            <span class="text-base font-black text-[#382416] tabular-nums">{{ format_currency($order->total_price) }}</span>
                        </div>
                    </div>

                    <!-- Direct Help Button -->
                    <div class="pt-2 text-center">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', settings('store_whatsapp', '201000000000')) }}?text={{ urlencode('مرحباً، أود الاستفسار عن حالة طلبي رقم: ' . $order->public_order_id) }}" 
                           target="_blank" 
                           class="inline-flex items-center gap-2 py-2.5 px-5 rounded-xl bg-[#F3E9D2] hover:bg-[#EADFC8] text-[#382416] font-bold text-xs transition-colors">
                            <span>استفسار فوري عبر واتساب</span>
                        </a>
                    </div>

                </div>
            @else
                <div class="bg-[#FDFBF7] rounded-3xl p-8 border border-[#EADFC8] text-center space-y-3 shadow-xs">
                    <span class="text-3xl block">⚠️</span>
                    <h3 class="text-sm font-bold text-[#2B2118]">لم نتمكن من العثور على هذا الطلب</h3>
                    <p class="text-xs text-[#8F7B63] max-w-sm mx-auto">
                        يرجى التأكد من كتابة كود الطلب بشكل صحيح ومحاولة البحث مجدداً.
                    </p>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
