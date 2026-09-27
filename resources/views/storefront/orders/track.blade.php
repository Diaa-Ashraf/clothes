{{-- resources/views/storefront/orders/track.blade.php --}}
@extends('layouts.storefront')

@section('title', 'تتبع حالة الطلب | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-10 bg-canvas-base min-h-[85vh]">
 <div class="container mx-auto px-4 max-w-2xl space-y-8">
 
 <!-- Breadcrumbs -->
 <nav class="flex items-center gap-2 text-xs text-charcoal-muted">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">تتبع الطلب</span>
 </nav>

 <!-- Search Order Card -->
 <div class="bg-white rounded-3xl p-6 sm:p-10 border border-cream shadow-sm text-center space-y-5">
 <div class="w-16 h-16 rounded-full bg-primary-50 text-primary-800 text-3xl flex items-center justify-center mx-auto border border-primary-200 shadow-inner">
 
 </div>

 <div class="space-y-1.5">
 <h1 class="text-xl sm:text-2xl font-black text-charcoal-heading">تتبع حالة طلبك في أي وقت</h1>
 <p class="text-xs text-charcoal-muted max-w-md mx-auto">
 أدخل رقم الطلب المرجعي لمتابعة مرحلة التجهيز والتوصيل خطوة بخطوة بدون الحاجة لتسجيل الدخول
 </p>
 </div>

 <form action="{{ route('orders.track') }}" method="GET" class="relative max-w-lg mx-auto">
 <input type="text" 
 name="q" 
 value="{{ $searchTerm }}" 
 placeholder="مثال: TWH-20260927-6HLD" 
 required
 dir="ltr"
 class="w-full bg-canvas-base border border-cream focus:border-primary-600 focus:ring-2 focus:ring-primary-100 rounded-full py-3.5 pr-4 pl-32 text-xs sm:text-sm font-mono font-bold text-charcoal outline-none transition-all">
 
 <button type="submit" 
 class="absolute left-1.5 top-1/2 -translate-y-1/2 py-2.5 px-6 rounded-full bg-gradient-maroon text-white font-bold text-xs shadow-md shadow-primary-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer">
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

 <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm space-y-6">
 
 <!-- Header with Public ID & Status -->
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-cream pb-4">
 <div class="space-y-0.5">
 <span class="text-[10px] text-charcoal-muted block">رقم الطلب:</span>
 <span class="font-mono font-black text-lg text-primary-800">{{ $order->public_order_id }}</span>
 </div>

 <div>
 <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $order->status_badge_class }}">
 {{ $order->status_label }}
 </span>
 </div>
 </div>

 <!-- Stepper Timeline -->
 @if($isCancelled)
 <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs text-center space-y-1">
 <span class="text-2xl block"></span>
 <span class="font-bold text-sm block">تم إلغاء هذا الطلب</span>
 @if($order->cancel_reason)
 <p class="text-[11px] text-rose-700">سبب الإلغاء: {{ $order->cancel_reason }}</p>
 @endif
 </div>
 @else
 <div class="py-4">
 <div class="grid grid-cols-5 gap-1.5 text-center">
 
 <div class="flex flex-col items-center gap-1.5">
 <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 1 ? 'bg-primary-800 text-white' : 'bg-gray-100 text-gray-400' }}">
 
 </div>
 <span class="text-[10px] font-bold {{ $currentStep >= 1 ? 'text-primary-800' : 'text-charcoal-muted' }}">
 تم الاستلام
 </span>
 </div>

 <div class="flex flex-col items-center gap-1.5">
 <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 2 ? 'bg-primary-800 text-white' : 'bg-gray-100 text-gray-400' }}">
 {{ $order->payment_method === 'instapay' ? '' : '' }}
 </div>
 <span class="text-[10px] font-bold {{ $currentStep >= 2 ? 'text-primary-800' : 'text-charcoal-muted' }}">
 {{ $order->payment_method === 'instapay' ? 'مراجعة الدفع' : 'تم التأكيد' }}
 </span>
 </div>

 <div class="flex flex-col items-center gap-1.5">
 <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 3 ? 'bg-primary-800 text-white' : 'bg-gray-100 text-gray-400' }}">
 
 </div>
 <span class="text-[10px] font-bold {{ $currentStep >= 3 ? 'text-primary-800' : 'text-charcoal-muted' }}">
 جاري التجهيز
 </span>
 </div>

 <div class="flex flex-col items-center gap-1.5">
 <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 4 ? 'bg-primary-800 text-white' : 'bg-gray-100 text-gray-400' }}">
 {{ $order->delivery_type === 'pickup' ? '' : '' }}
 </div>
 <span class="text-[10px] font-bold {{ $currentStep >= 4 ? 'text-primary-800' : 'text-charcoal-muted' }}">
 {{ $order->delivery_type === 'pickup' ? 'جاهز للاستلام' : 'في الطريق' }}
 </span>
 </div>

 <div class="flex flex-col items-center gap-1.5">
 <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $currentStep >= 5 ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-400' }}">
 
 </div>
 <span class="text-[10px] font-bold {{ $currentStep >= 5 ? 'text-emerald-700' : 'text-charcoal-muted' }}">
 تم التوصيل
 </span>
 </div>

 </div>
 </div>
 @endif

 <!-- Summary Info Box -->
 <div class="bg-canvas-base rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
 <div>
 <span class="text-[10px] text-charcoal-muted block">تاريخ الطلب:</span>
 <span class="font-bold text-charcoal">{{ $order->created_at->translatedFormat('d M Y - h:i A') }}</span>
 </div>
 <div>
 <span class="text-[10px] text-charcoal-muted block">طريقة الاستلام:</span>
 <span class="font-bold text-charcoal">{{ $order->delivery_type === 'pickup' ? 'استلام من الفرع' : 'توصيل للمنزل' }}</span>
 </div>
 <div>
 <span class="text-[10px] text-charcoal-muted block">إجمالي الطلب:</span>
 <span class="font-black text-primary-800 tabular-nums">{{ number_format($order->total_price, 2) }} ج.م</span>
 </div>
 </div>

 <!-- Direct Contact -->
 @php
 $msg = urlencode("مرحباً ألبان التوحيد، أستفسر عن طلبي رقم: {$order->public_order_id}");
 $storePhone = preg_replace('/[^0-9]/', '', settings('store_whatsapp', '201000000000'));
 @endphp
 <div class="pt-2 text-center">
 <a href="https://wa.me/{{ $storePhone }}?text={{ $msg }}" 
 target="_blank" 
 class="inline-flex items-center gap-2 py-3 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-md shadow-emerald-700/20">
 <span>متابعة الطلب مع خدمة العملاء عبر واتساب</span>
 <span></span>
 </a>
 </div>

 </div>
 @else
 <!-- Not Found State -->
 <div class="bg-white rounded-3xl p-10 border border-cream text-center space-y-3 shadow-sm">
 <span class="text-4xl block"></span>
 <h3 class="text-base font-bold text-charcoal-heading">لم يتم العثور على هذا الطلب</h3>
 <p class="text-xs text-charcoal-muted max-w-md mx-auto leading-relaxed">
 تأكد من كتابة رقم الطلب بشكل دقيق كما وصلك في رسالة التأكيد (مثال: TWH-20260927-XXXX) ثم أعد المحاولة.
 </p>
 </div>
 @endif
 @endif

 </div>
</div>
@endsection
