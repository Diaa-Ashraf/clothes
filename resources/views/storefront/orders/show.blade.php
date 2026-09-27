{{-- resources/views/storefront/orders/show.blade.php --}}
@extends('storefront.account.layout')

@section('title', 'تفاصيل الطلب: ' . $order->public_order_id . ' | ألبان وسوبرماركت التوحيد')
@section('page_title', 'تفاصيل الطلب')

@php
    $statuses = [
        'pending' => ['label' => 'استلام الطلب', 'step' => 1],
        'under_review' => ['label' => 'مراجعة التحويل', 'step' => 2],
        'confirmed' => ['label' => 'تم التأكيد', 'step' => 2],
        'processing' => ['label' => 'جاري التجهيز', 'step' => 3],
        'on_the_way' => ['label' => 'في الطريق للمنزل', 'step' => 4],
        'ready_for_pickup' => ['label' => 'جاهز للاستلام بالفرع', 'step' => 4],
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
<div class="space-y-6">
    
    <!-- Top Details Banner -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <h1 class="text-lg sm:text-xl font-black text-charcoal-heading">طلب رقم:</h1>
                <span class="font-mono font-bold text-lg text-primary-800">{{ $order->public_order_id }}</span>
            </div>
            <p class="text-xs text-charcoal-muted">
                تم التسجيل في: {{ $order->created_at->translatedFormat('l، d F Y - h:i A') }}
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- 1-Click Re-order Button -->
            <form action="{{ route('account.orders.reorder', $order->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="py-2 px-3.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-800 border border-orange-200 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>إعادة طلب الأصناف</span>
                </button>
            </form>

            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    <!-- Visual Lifecycle Timeline -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm space-y-6">
        <h2 class="font-black text-sm text-charcoal-heading border-b border-cream pb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>مراحل تجهيز وتوصيل طلبك</span>
        </h2>

        @if($isCancelled)
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs text-center space-y-1">
                <svg class="w-8 h-8 text-rose-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="font-bold text-sm block">تم إلغاء هذا الطلب</span>
                @if($order->cancel_reason)
                    <p class="text-[11px] text-rose-700">سبب الإلغاء: {{ $order->cancel_reason }}</p>
                @endif
            </div>
        @else
            <!-- Timeline Stepper -->
            <div class="relative pt-2 pb-4">
                <div class="grid grid-cols-5 gap-2 text-center relative z-10">
                    
                    <!-- Step 1: Received -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 1 ? 'bg-primary-800 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            1
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 1 ? 'text-primary-800' : 'text-charcoal-muted' }}">
                            تم الاستلام
                        </span>
                    </div>

                    <!-- Step 2: Confirmed / Reviewed -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 2 ? 'bg-primary-800 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            2
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 2 ? 'text-primary-800' : 'text-charcoal-muted' }}">
                            {{ $order->payment_method === 'instapay' ? 'مراجعة الدفع' : 'تم التأكيد' }}
                        </span>
                    </div>

                    <!-- Step 3: Processing -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 3 ? 'bg-primary-800 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            3
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 3 ? 'text-primary-800' : 'text-charcoal-muted' }}">
                            جاري التجهيز
                        </span>
                    </div>

                    <!-- Step 4: On the way or Ready for pickup -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 4 ? 'bg-primary-800 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            4
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 4 ? 'text-primary-800' : 'text-charcoal-muted' }}">
                            {{ $order->delivery_type === 'pickup' ? 'جاهز للاستلام' : 'في الطريق' }}
                        </span>
                    </div>

                    <!-- Step 5: Delivered -->
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold {{ $currentStep >= 5 ? 'bg-emerald-600 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold {{ $currentStep >= 5 ? 'text-emerald-700' : 'text-charcoal-muted' }}">
                            تم التوصيل
                        </span>
                    </div>

                </div>
            </div>
        @endif
    </div>

    <!-- Order Items & Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        
        <!-- Items Table -->
        <div class="md:col-span-8 bg-white rounded-3xl p-6 sm:p-7 border border-cream shadow-sm space-y-4">
            <h3 class="font-bold text-xs text-charcoal-heading border-b border-cream pb-3">المنتجات المطلوبة</h3>

            <div class="divide-y divide-cream/70 text-xs">
                @foreach($order->orderItems as $item)
                    <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-canvas-base border border-cream p-1 shrink-0 flex items-center justify-center">
                                @if($item->product?->primary_image_url)
                                    <img src="{{ $item->product->primary_image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-contain">
                                @else
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-charcoal flex items-center gap-2">
                                    <span>{{ $item->product?->name ?: 'منتج بالطلب' }}</span>
                                    @if($item->portion_label)
                                        <span class="px-1.5 py-0.5 rounded bg-primary-100 text-primary-800 text-[10px] font-bold">
                                            {{ $item->portion_label }}
                                        </span>
                                    @endif
                                </h4>
                                @if($item->notes)
                                    <p class="text-[10px] text-amber-800 bg-amber-50 px-2 py-0.5 rounded mt-0.5 inline-block">
                                        ملاحظة التقطيع/الطلب: {{ $item->notes }}
                                    </p>
                                @endif
                                <span class="text-[11px] text-charcoal-muted block mt-0.5">{{ number_format($item->price, 2) }} ج.م × {{ $item->quantity }}</span>
                            </div>
                        </div>

                        <div class="text-left font-black text-primary-800 tabular-nums">
                            {{ number_format($item->total_price ?? ($item->price * $item->quantity), 2) }} ج.م
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Financials -->
            <div class="pt-4 border-t border-cream space-y-2 text-xs">
                <div class="flex items-center justify-between text-charcoal-muted">
                    <span>طريقة الاستلام</span>
                    <span class="font-bold text-charcoal">{{ $order->delivery_type === 'pickup' ? 'استلام من الفرع (مجاني)' : 'توصيل للمنزل' }}</span>
                </div>

                <div class="flex items-center justify-between text-charcoal-muted">
                    <span>طريقة الدفع</span>
                    <span class="font-bold text-charcoal">{{ $order->payment_method === 'instapay' ? 'تحويل إنستاباي' : 'الدفع نقداً عند الاستلام' }}</span>
                </div>

                <div class="border-t border-cream pt-3 flex items-center justify-between text-sm font-black text-charcoal-heading">
                    <span>الإجمالي الكلي المطلوب</span>
                    <span class="text-lg text-primary-800 tabular-nums">
                        {{ number_format($order->total_price, 2) }} ج.م
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="md:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl p-6 border border-cream shadow-sm space-y-4 text-xs">
                <h3 class="font-bold text-charcoal-heading border-b border-cream pb-3">بيانات الاستلام والتوصيل</h3>

                <div class="space-y-1">
                    <span class="text-[10px] text-charcoal-muted block">الاسم:</span>
                    <span class="font-bold text-charcoal block">{{ $order->customer_name }}</span>
                </div>

                <div class="space-y-1">
                    <span class="text-[10px] text-charcoal-muted block">الهاتف:</span>
                    <span class="font-mono font-bold text-charcoal block" dir="ltr">{{ $order->phone }}</span>
                </div>

                @if($order->delivery_type === 'pickup')
                    <div class="space-y-1">
                        <span class="text-[10px] text-charcoal-muted block">فرع الاستلام بالعامرية:</span>
                        <span class="font-bold text-emerald-800 block">{{ $order->branch?->name ?: 'الفرع الرئيسي' }}</span>
                        <span class="text-charcoal-muted text-[11px] block">{{ $order->branch?->address }}</span>
                    </div>
                @else
                    <div class="space-y-1">
                        <span class="text-[10px] text-charcoal-muted block">عنوان التوصيل:</span>
                        <span class="font-bold text-charcoal leading-relaxed block">{{ $order->address }}</span>
                    </div>
                @endif

                @if($order->notes)
                    <div class="space-y-1 pt-2 border-t border-cream">
                        <span class="text-[10px] text-charcoal-muted block">ملاحظات الطلب:</span>
                        <span class="text-charcoal-muted block">{{ $order->notes }}</span>
                    </div>
                @endif
            </div>

            <!-- WhatsApp Direct Help -->
            @php
                $msg = urlencode("مرحباً ألبان التوحيد، أستفسر بخصوص طلبي رقم: {$order->public_order_id}");
                $storePhone = preg_replace('/[^0-9]/', '', settings('store_whatsapp', '201000000000'));
            @endphp
            <a href="https://wa.me/{{ $storePhone }}?text={{ $msg }}" 
               target="_blank" 
               class="w-full py-3 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-md shadow-emerald-700/20">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                </svg>
                <span>تواصل مع الفرع عبر واتساب</span>
            </a>
        </div>

    </div>

</div>
@endsection
