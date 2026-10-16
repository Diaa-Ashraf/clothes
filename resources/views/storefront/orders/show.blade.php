{{-- resources/views/storefront/orders/show.blade.php --}}
@extends('storefront.account.layout')

@section('title', 'تفاصيل الطلب: ' . $order->public_order_id . ' | ' . settings('site_name', 'أزياء لافيستا'))
@section('page_title', 'تفاصيل الطلب')

@php
    $statuses = [
        'pending' => ['label' => 'استلام الطلب', 'step' => 1],
        'under_review' => ['label' => 'مراجعة التحويل', 'step' => 2],
        'confirmed' => ['label' => 'تم التأكيد', 'step' => 2],
        'processing' => ['label' => 'جاري التجهيز', 'step' => 3],
        'on_the_way' => ['label' => 'في طريق الشحن', 'step' => 4],
        'ready_for_pickup' => ['label' => 'جاهز للاستلام بالبوتيك', 'step' => 4],
        'delivered' => ['label' => 'تم التوصيل بنجاح', 'step' => 5],
    ];

    $isCancelled = ($order->status === 'cancelled');
    $currentStep = match($order->status) {
        'pending' => 1,
        'under_review', 'confirmed' => 2,
        'processing' => 3,
        'on_the_way', 'ready_for_pickup' => 4,
        'delivered' => 5,
        default => 1,
    };
@endphp

@section('account_content')
<div class="space-y-6" dir="rtl">
    
    <!-- Top Details Banner -->
    <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-8 border border-[#EADFC8] shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <h1 class="text-lg sm:text-xl font-serif font-black text-[#2B2118]">طلب رقم:</h1>
                <span class="font-mono font-bold text-lg text-[#382416]">{{ $order->public_order_id }}</span>
            </div>
            <p class="text-xs text-[#8F7B63]">
                تم التسجيل في: {{ $order->created_at->translatedFormat('l، d F Y - h:i A') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- 1-Click Re-order Button -->
            <form action="{{ route('account.orders.reorder', $order->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="py-2 px-3.5 rounded-xl bg-[#F3E9D2] hover:bg-[#EADFC8] text-[#382416] border border-[#CDB78F] font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4 text-[#382416]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>إعادة طلب القطع</span>
                </button>
            </form>

            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <!-- Visual Lifecycle Timeline -->
    <div class="bg-[#FDFBF7] rounded-3xl p-6 sm:p-8 border border-[#EADFC8] shadow-xs space-y-6">
        <h2 class="font-serif font-bold text-sm text-[#2B2118] border-b border-[#EADFC8] pb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#382416]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>مراحل تجهيز وشحن طلبك</span>
        </h2>

        @if($isCancelled)
            <div class="p-4 rounded-2xl bg-[#A9552F]/10 border border-[#A9552F]/30 text-[#A9552F] text-xs text-center space-y-1">
                <span class="font-bold text-sm block">تم إلغاء هذا الطلب</span>
                @if($order->cancel_reason)
                    <p class="text-[11px]">سبب الإلغاء: {{ $order->cancel_reason }}</p>
                @endif
            </div>
        @else
            <!-- Timeline Stepper -->
            <div class="relative pt-2 pb-4">
                <div class="grid grid-cols-5 gap-2 text-center relative z-10">
                    
                    <!-- Step 1: Received -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 1 ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                            1
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 1 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                            استلام الطلب
                        </span>
                    </div>

                    <!-- Step 2: Confirmed -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 2 ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                            2
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 2 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                            تم التأكيد
                        </span>
                    </div>

                    <!-- Step 3: Processing -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 3 ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                            3
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 3 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                            جاري التجهيز
                        </span>
                    </div>

                    <!-- Step 4: Out for Delivery or Ready for Pickup -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 4 ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                            4
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 4 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                            {{ $order->delivery_type === 'pickup' ? 'جاهز بالبوتيك' : 'في طريق الشحن' }}
                        </span>
                    </div>

                    <!-- Step 5: Delivered -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 5 ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'bg-[#EADFC8] text-[#8F7B63]' }}">
                            5
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 5 ? 'text-[#382416]' : 'text-[#8F7B63]' }}">
                            تم التسليم
                        </span>
                    </div>

                </div>
            </div>
        @endif
    </div>

    <!-- Order Items & Billing Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Items Table -->
        <div class="lg:col-span-8 bg-[#FDFBF7] rounded-3xl p-6 border border-[#EADFC8] shadow-xs space-y-4">
            <h2 class="font-serif font-bold text-sm text-[#2B2118] border-b border-[#EADFC8] pb-3">
                القطع المتضمنة في الطلب
            </h2>

            <div class="divide-y divide-[#EADFC8]">
                @foreach($order->items as $item)
                <div class="py-3.5 first:pt-0 last:pb-0 flex items-center justify-between gap-4 text-xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8] p-1 flex items-center justify-center overflow-hidden shrink-0">
                            @if($item->product?->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <span class="text-base">🛍️</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="font-bold text-[#2B2118] block truncate">{{ $item->product_name }}</span>
                            @if($item->variant_label)
                                <span class="text-[10px] text-[#A9552F] block font-semibold">{{ $item->variant_label }}</span>
                            @endif
                            <span class="text-[10px] text-[#8F7B63] block">الكمية: {{ $item->quantity }}</span>
                        </div>
                    </div>

                    <div class="text-left font-black text-[#382416] tabular-nums shrink-0">
                        {{ format_currency($item->total_price) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Billing & Delivery Info Sidebar -->
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-[#FDFBF7] rounded-3xl p-6 border border-[#EADFC8] shadow-xs space-y-4 text-xs">
                <h3 class="font-serif font-bold text-sm text-[#2B2118] border-b border-[#EADFC8] pb-3">تفاصيل الحساب</h3>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-[#8F7B63]">
                        <span>المجموع الفرعي:</span>
                        <span class="font-bold text-[#2B2118] tabular-nums">{{ format_currency($order->subtotal ?? $order->total_price) }}</span>
                    </div>

                    @if($order->discount_amount > 0)
                    <div class="flex items-center justify-between text-[#A9552F] bg-[#A9552F]/10 p-2 rounded-xl border border-[#A9552F]/20">
                        <span>خصم:</span>
                        <span class="font-bold tabular-nums">- {{ format_currency($order->discount_amount) }}</span>
                    </div>
                    @endif

                    <div class="flex items-center justify-between text-[#8F7B63]">
                        <span>رسوم التوصيل:</span>
                        <span class="font-bold text-[#2B2118] tabular-nums">{{ format_currency($order->shipping_cost ?? 0) }}</span>
                    </div>

                    <div class="pt-3 border-t border-[#EADFC8] flex items-center justify-between text-sm font-bold text-[#2B2118]">
                        <span>الإجمالي الكلي:</span>
                        <span class="text-base font-black text-[#382416] tabular-nums">{{ format_currency($order->total_price) }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-[#EADFC8] space-y-2 text-[11px] text-[#6B5642]">
                    <div>
                        <span class="text-[#8F7B63] block">طريقة الاستلام:</span>
                        <span class="font-bold text-[#2B2118]">{{ $order->delivery_type_label }}</span>
                    </div>
                    @if($order->branch)
                    <div>
                        <span class="text-[#8F7B63] block">الفرع المختار:</span>
                        <span class="font-bold text-[#2B2118]">{{ $order->branch->name }}</span>
                    </div>
                    @endif
                    @if($order->address)
                    <div>
                        <span class="text-[#8F7B63] block">عنوان التوصيل:</span>
                        <span class="font-bold text-[#2B2118]">{{ $order->address }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="text-[#8F7B63] block">طريقة الدفع:</span>
                        <span class="font-bold text-[#2B2118]">{{ $order->payment_method_label }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
