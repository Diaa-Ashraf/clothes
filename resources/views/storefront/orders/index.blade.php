{{-- resources/views/storefront/orders/index.blade.php --}}
@extends('storefront.account.layout')

@section('title', 'سجل طلباتي | ألبان وسوبرماركت التوحيد')
@section('page_title', 'سجل طلباتي')

@section('account_content')
<div class="space-y-6">
    
    <!-- Orders Header & Filters -->
    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-cream shadow-sm space-y-4">
        <div>
            <h1 class="text-base sm:text-lg font-black text-charcoal-heading">سجل طلباتي السابقة والحالية</h1>
            <p class="text-xs text-charcoal-muted">تتبع حالة طلباتك واستعراض تفاصيل المشتريات السابقة وإعادة الطلب بضغطة واحدة</p>
        </div>

        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-bold scrollbar-none">
            <a href="{{ route('account.orders') }}" 
               class="px-3.5 py-1.5 rounded-xl shrink-0 transition-colors {{ empty($status) || $status === 'all' ? 'bg-primary-800 text-white' : 'bg-canvas-base text-charcoal hover:bg-canvas-subtle' }}">
                الكل ({{ auth()->user()->orders()->count() }})
            </a>

            <a href="{{ route('account.orders', ['status' => 'under_review']) }}" 
               class="px-3.5 py-1.5 rounded-xl shrink-0 transition-colors {{ $status === 'under_review' ? 'bg-amber-600 text-white' : 'bg-canvas-base text-charcoal hover:bg-canvas-subtle' }}">
                قيد المراجعة
            </a>

            <a href="{{ route('account.orders', ['status' => 'processing']) }}" 
               class="px-3.5 py-1.5 rounded-xl shrink-0 transition-colors {{ $status === 'processing' ? 'bg-blue-600 text-white' : 'bg-canvas-base text-charcoal hover:bg-canvas-subtle' }}">
                جاري التجهيز
            </a>

            <a href="{{ route('account.orders', ['status' => 'on_the_way']) }}" 
               class="px-3.5 py-1.5 rounded-xl shrink-0 transition-colors {{ $status === 'on_the_way' ? 'bg-indigo-600 text-white' : 'bg-canvas-base text-charcoal hover:bg-canvas-subtle' }}">
                في الطريق
            </a>

            <a href="{{ route('account.orders', ['status' => 'delivered']) }}" 
               class="px-3.5 py-1.5 rounded-xl shrink-0 transition-colors {{ $status === 'delivered' ? 'bg-emerald-600 text-white' : 'bg-canvas-base text-charcoal hover:bg-canvas-subtle' }}">
                تم التوصيل
            </a>

            <a href="{{ route('account.orders', ['status' => 'cancelled']) }}" 
               class="px-3.5 py-1.5 rounded-xl shrink-0 transition-colors {{ $status === 'cancelled' ? 'bg-rose-600 text-white' : 'bg-canvas-base text-charcoal hover:bg-canvas-subtle' }}">
                ملغي
            </a>
        </div>
    </div>

    <!-- Orders List -->
    @if($orders->isNotEmpty())
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-3xl p-5 sm:p-6 border border-cream hover:border-primary-300 shadow-sm transition-all space-y-4">
                    
                    <!-- Order Top Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cream pb-3">
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-2">
                                <span class="font-black text-sm text-charcoal-heading">طلب رقم:</span>
                                <span class="font-mono font-bold text-primary-800 text-sm">{{ $order->public_order_id }}</span>
                            </div>
                            <span class="text-[11px] text-charcoal-muted">
                                {{ $order->created_at->translatedFormat('d M Y - h:i A') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Items Summary & Details Button -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                        <div class="space-y-1 text-charcoal-muted">
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-charcoal">طريقة الاستلام:</span>
                                <span>{{ $order->delivery_type === 'pickup' ? 'استلام من فرع ' . ($order->branch?->name ?: '') : 'توصيل للمنزل' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-charcoal">عدد الأصناف:</span>
                                <span>{{ $order->orderItems->sum('quantity') }} قطعة ({{ $order->orderItems->count() }} صنف)</span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between sm:justify-end gap-3 pt-2 sm:pt-0 border-t sm:border-t-0 border-cream/60">
                            <div class="text-right pl-2">
                                <span class="text-[10px] text-charcoal-muted block">الإجمالي الكلي:</span>
                                <span class="text-base font-black text-primary-800 tabular-nums">
                                    {{ number_format($order->total_price, 2) }} ج.م
                                </span>
                            </div>

                            <!-- 1-Click Re-order Form -->
                            <form action="{{ route('account.orders.reorder', $order->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="py-2 px-3.5 rounded-xl bg-orange-50 hover:bg-orange-100 text-orange-800 border border-orange-200 font-bold text-xs flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs">
                                    <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span>إعادة الطلب</span>
                                </button>
                            </form>

                            <a href="{{ route('account.orders.show', $order->id) }}" 
                               class="py-2 px-4 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs shadow-xs transition-colors">
                                تفاصيل الطلب والتتبع ←
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach

            @if($orders->hasPages())
                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-3xl p-12 border border-cream text-center space-y-3 shadow-sm">
            <svg class="w-12 h-12 mx-auto text-charcoal-muted/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <h3 class="text-base font-bold text-charcoal-heading">لا توجد طلبات مطابقة</h3>
            <p class="text-xs text-charcoal-muted max-w-sm mx-auto">
                لم نجد أي طلبات مسجلة بهذه الحالة. يمكنك تصفح المنتجات وبدء التسوق الآن!
            </p>
            <div class="pt-2">
                <a href="{{ route('site.products') }}" class="inline-block py-2.5 px-6 rounded-xl bg-primary-800 text-white font-bold text-xs">
                    تصفح منتجات التوحيد
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
