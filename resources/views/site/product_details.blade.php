@extends('layouts.site')

@section('content')
@php
 $displayPrice = format_currency($product->final_price, 'USD');
 $isWishlisted = auth()->check() && auth()->user()->wishlistItems()->where('product_id', $product->id)->exists();
@endphp

<div class="bg-[#F8F9FA] text-[#18181B] min-h-[85vh] py-4 sm:py-8 lg:py-10" 
 x-data="{
 quantity: 1,
 maxStock: {{ (int)($product->available_stock ?? 1) }},
 isLimited: {{ $product->available_stock <= 5 ? 'true' : 'false' }},
 isAdding: false,
 imgZoom: false,
 increment() {
 if (this.isLimited) {
 toastr.warning('{{ __("عذراً، هذا المنتج ذو كمية محدودة (5 قطع أو أقل بالمخزون)، الحد الأقصى المسموح به هو قطعة واحدة فقط.") }}');
 return;
 }
 if (this.quantity < this.maxStock) {
 this.quantity++;
 }
 },
 decrement() {
 if (this.quantity > 1) {
 this.quantity--;
 }
 },
 addToCart() {
 if (this.isAdding || this.maxStock <= 0) return;
 this.isAdding = true;
 
 $.post('{{ route('AddToCart', $product->id) }}', {
 quantity: this.quantity
 }).done((res) => {
 if (res.success) {
 toastr.success(res.message);
 fetch('{{ route('cart.payload') }}')
 .then(r => r.json())
 .then(payload => {
 window.dispatchEvent(new CustomEvent('cart-updated', { detail: payload }));
 window.dispatchEvent(new CustomEvent('open-mini-cart'));
 })
 .catch(() => {});
 } else {
 toastr.error(res.message);
 }
 }).fail((xhr) => {
 const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __('حدث خطأ أثناء إضافة المنتج.') }}';
 toastr.error(msg);
 }).always(() => {
 this.isAdding = false;
 });
 },
 buyNow() {
 if (this.isAdding || this.maxStock <= 0) return;
 this.isAdding = true;
 
 $.post('{{ route('AddToCart', $product->id) }}', {
 quantity: this.quantity
 }).done((res) => {
 if (res.success) {
 window.location.href = '{{ route('checkout.index') }}';
 } else {
 toastr.error(res.message);
 this.isAdding = false;
 }
 }).fail((xhr) => {
 const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __('حدث خطأ أثناء الطلب.') }}';
 toastr.error(msg);
 this.isAdding = false;
 });
 }
 }">
 
 <div class="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
 
 {{-- Breadcrumbs navigation bar --}}
 <nav class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-[#71717A] mb-4 sm:mb-6 lg:mb-8 font-sans overflow-x-auto whitespace-nowrap pb-1 no-scrollbar">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors shrink-0">{{ __('الرئيسية') }}</a>
 <span class="text-gray-300 shrink-0">/</span>
 <a href="{{ route('product.shop') }}" class="hover:text-[#C5A059] transition-colors shrink-0">{{ __('المتجر') }}</a>
 @if($product->category)
 <span class="text-gray-300 shrink-0">/</span>
 <a href="{{ route('category.product', $product->category->id) }}" class="hover:text-[#C5A059] transition-colors shrink-0">{{ $product->category->name }}</a>
 @endif
 <span class="text-gray-300 shrink-0">/</span>
 <span class="text-[#18181B] font-semibold truncate max-w-[150px] xs:max-w-[200px] sm:max-w-xs md:max-w-md shrink-0">{{ $product->name }}</span>
 </nav>

 {{-- Main Product Card Showcase --}}
 <div class="bg-white rounded-2xl sm:rounded-3xl border border-[#E5E7EB] shadow-xs overflow-hidden mb-8 sm:mb-12">
 <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 lg:gap-12 p-3.5 sm:p-6 md:p-8 lg:p-12 items-start">
 
 {{-- Column 1: Image Showcase --}}
 <div class="lg:col-span-6 flex flex-col items-center">
 <div class="relative w-full aspect-square max-w-[540px] bg-[#F8F9FA] rounded-xl sm:rounded-2xl lg:rounded-3xl border border-[#E5E7EB] p-3 sm:p-6 lg:p-8 flex items-center justify-center overflow-hidden group shadow-inner">
 
 {{-- Badges --}}
 <div class="absolute top-2.5 start-2.5 sm:top-4 sm:start-4 z-10 flex flex-col gap-1 sm:gap-2 pointer-events-none">
 @if($product->has_discount)
 <span class="bg-rose-600 text-white font-bold text-[10px] sm:text-xs px-2 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-md animate-pulse">
 -{{ $product->discount_percentage }}% {{ __('خصم') }}
 </span>
 @endif
 @if($product->featured)
 <span class="bg-[#C5A059] text-white font-extrabold text-[10px] sm:text-xs px-2 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-md flex items-center gap-1">
 {{ __('إصدار مميز') }}
 </span>
 @endif
 </div>

 {{-- Wishlist Floating Button --}}
 @if(settings('wishlist_enabled', true))
 <button type="button" 
 onclick="window.toggleWishlist(event, {{ $product->id }}, this);"
 class="btn-wishlist-toggle absolute top-2.5 end-2.5 sm:top-4 sm:end-4 z-10 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white/95 border border-[#E5E7EB] hover:border-[#C5A059] {{ $isWishlisted ? 'text-rose-600' : 'text-gray-400' }} hover:text-rose-600 flex items-center justify-center transition-all duration-300 shadow-md backdrop-blur-sm hover:scale-110 active:scale-95 cursor-pointer" 
 data-id="{{ $product->id }}" 
 title="{{ $isWishlisted ? __('إزالة من المفضلة') : __('إضافة للمفضلة') }}">
 <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current" viewBox="0 0 24 24">
 <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
 </svg>
 </button>
 @endif

 {{-- Product Main Image (Click to Zoom) --}}
 <div class="w-full h-full flex items-center justify-center cursor-zoom-in"
 @click="imgZoom = true"
 title="{{ __('انقر لتكبير الصورة') }}">
 <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
 alt="{{ $product->name }}" 
 class="w-full h-full object-contain rounded-xl sm:rounded-2xl transition-transform duration-500 ease-out group-hover:scale-105 select-none">
 </div>

 {{-- Zoom hint badge --}}
 <button type="button"
 @click="imgZoom = true"
 class="absolute bottom-2.5 end-2.5 sm:bottom-4 sm:end-4 z-10 px-2 py-1 rounded-lg bg-white/90 hover:bg-white border border-gray-200 text-[#71717A] hover:text-[#18181B] text-[10px] sm:text-xs font-semibold flex items-center gap-1 shadow-sm backdrop-blur-xs transition-colors">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" />
 </svg>
 <span class="hidden xs:inline">{{ __('تكبير') }}</span>
 </button>
 </div>

 {{-- Fullscreen Image Lightbox Modal --}}
 <div x-show="imgZoom" 
 x-cloak
 x-transition:enter="transition ease-out duration-300"
 x-transition:enter-start="opacity-0"
 x-transition:enter-end="opacity-100"
 x-transition:leave="transition ease-in duration-200"
 x-transition:leave-start="opacity-100"
 x-transition:leave-end="opacity-0"
 @keydown.escape.window="imgZoom = false"
 class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-6"
 style="display: none;">
 <div class="relative max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center" @click.away="imgZoom = false">
 <button type="button" 
 @click="imgZoom = false" 
 class="absolute -top-10 end-0 sm:-top-12 sm:end-0 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center transition-all cursor-pointer">
 
 </button>
 <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
 alt="{{ $product->name }}" 
 class="max-h-[82vh] w-auto max-w-full object-contain rounded-2xl shadow-2xl">
 <p class="text-white/80 text-xs sm:text-sm font-semibold mt-3 text-center px-4">
 {{ $product->name }}
 </p>
 </div>
 </div>

 {{-- Quick Guarantee Badges under image --}}
 <div class="grid grid-cols-3 gap-1.5 sm:gap-3 w-full max-w-[540px] mt-3.5 sm:mt-6">
 <div class="bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl sm:rounded-2xl p-2 sm:p-3 text-center flex flex-col items-center justify-center gap-0.5 sm:gap-1">
 <span class="text-base sm:text-lg"></span>
 <span class="text-[10px] sm:text-[11px] font-bold text-[#18181B] leading-snug">{{ __('ضمان أصالة 100%') }}</span>
 <span class="text-[8px] sm:text-[9px] text-[#71717A] leading-tight">{{ __('جودة ملكية مضمونة') }}</span>
 </div>
 <div class="bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl sm:rounded-2xl p-2 sm:p-3 text-center flex flex-col items-center justify-center gap-0.5 sm:gap-1">
 <span class="text-base sm:text-lg"></span>
 <span class="text-[10px] sm:text-[11px] font-bold text-[#18181B] leading-snug">{{ __('شحن سريع ومؤمّن') }}</span>
 <span class="text-[8px] sm:text-[9px] text-[#71717A] leading-tight">{{ __('لكافة المحافظات') }}</span>
 </div>
 <div class="bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl sm:rounded-2xl p-2 sm:p-3 text-center flex flex-col items-center justify-center gap-0.5 sm:gap-1">
 <span class="text-base sm:text-lg"></span>
 <span class="text-[10px] sm:text-[11px] font-bold text-[#18181B] leading-snug">{{ __('الدفع عند الاستلام') }}</span>
 <span class="text-[8px] sm:text-[9px] text-[#71717A] leading-tight">{{ __('معاينة قبل الدفع') }}</span>
 </div>
 </div>
 </div>

 {{-- Column 2: Product Information & CTAs --}}
 <div class="lg:col-span-6 flex flex-col justify-between h-full space-y-4 sm:space-y-6">
 
 <div>
 {{-- Category Tag & Stock Status --}}
 <div class="flex flex-wrap items-center gap-2 mb-2.5 sm:mb-3">
 <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-[11px] sm:text-xs font-bold bg-[#C5A059]/10 text-[#C5A059] border border-[#C5A059]/20">
 {{ $product->category?->name ?? __('مقتنيات فاخرة') }}
 </span>
 @if($product->available_stock > 0)
 <span class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
 <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
 {{ __('متوفر في المخزون الملكي') }} ({{ $product->available_stock }} {{ __('قطعة') }})
 </span>
 @else
 <span class="inline-flex items-center text-[11px] sm:text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-0.5 rounded-full">
 {{ __('نفدت الكمية مؤقتاً') }}
 </span>
 @endif
 </div>

 {{-- Product Title --}}
 <h1 class="text-xl sm:text-2xl lg:text-3xl xl:text-4xl font-extrabold text-[#18181B] leading-snug sm:leading-tight mb-3 sm:mb-4 tracking-tight break-words">
 {{ $product->name }}
 </h1>

 {{-- Price Section --}}
 <div class="bg-[#F8F9FA] border border-[#E5E7EB] rounded-2xl p-3.5 sm:p-5 mb-4 sm:mb-6 flex items-center justify-between flex-wrap gap-2.5 sm:gap-4">
 <div>
 <span class="text-[11px] sm:text-xs text-[#71717A] block mb-0.5">{{ __('السعر الحالي') }}</span>
 <div class="flex items-baseline gap-2.5 sm:gap-3 flex-wrap">
 <span class="text-2xl sm:text-3xl font-extrabold text-[#18181B] tabular-nums font-sans">
 {{ $displayPrice }}
 </span>
 @if($product->has_discount)
 <span class="text-sm sm:text-base text-gray-400 line-through tabular-nums font-sans">
 {{ format_currency($product->price, 'USD') }}
 </span>
 @if($product->price > $product->final_price)
 <span class="text-[11px] sm:text-xs font-bold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
 {{ __('وفر') }} {{ format_currency($product->price - $product->final_price, 'USD') }}
 </span>
 @endif
 @endif
 </div>
 </div>
 </div>

 {{-- Description Snippet --}}
 <div class="text-xs sm:text-sm md:text-base text-[#52525B] leading-relaxed mb-5 sm:mb-6 break-words">
 {{ $product->description ?: __('مقتنى يد ملكي فاخر مصنوع من أرقى المواد المقاومة للصدأ، يجمع بين الكلاسيكية الفاتنة والتقنيات العصرية الدقيقة. يأتي في علبة فاخرة ومناسب كهدية راقية تليق بالمناسبات الخاصة.') }}
 </div>

 {{-- Key Luxury Specifications Grid --}}
 <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 gap-2.5 sm:gap-3 mb-5 sm:mb-6">
 <div class="bg-white border border-[#E5E7EB] rounded-xl p-2.5 sm:p-3 flex items-center gap-2.5 sm:gap-3">
 <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-[#C5A059]/10 text-[#C5A059] flex items-center justify-center font-bold text-sm shrink-0">
 
 </div>
 <div class="min-w-0 flex-1">
 <span class="text-[10px] text-[#71717A] block">{{ __('نوع الصياغة والتنفيذ') }}</span>
 <span class="text-xs font-bold text-[#18181B] leading-snug block">{{ __('صياغة متقنة بحرفية ملكية') }}</span>
 </div>
 </div>
 <div class="bg-white border border-[#E5E7EB] rounded-xl p-2.5 sm:p-3 flex items-center gap-2.5 sm:gap-3">
 <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-[#C5A059]/10 text-[#C5A059] flex items-center justify-center font-bold text-sm shrink-0">
 
 </div>
 <div class="min-w-0 flex-1">
 <span class="text-[10px] text-[#71717A] block">{{ __('المواد والطلاء') }}</span>
 <span class="text-xs font-bold text-[#18181B] leading-snug block">{{ __('مقاوم للصدأ وتغير اللون') }}</span>
 </div>
 </div>
 <div class="bg-white border border-[#E5E7EB] rounded-xl p-2.5 sm:p-3 flex items-center gap-2.5 sm:gap-3">
 <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-[#C5A059]/10 text-[#C5A059] flex items-center justify-center font-bold text-sm shrink-0">
 
 </div>
 <div class="min-w-0 flex-1">
 <span class="text-[10px] text-[#71717A] block">{{ __('الاستخدام اليومي') }}</span>
 <span class="text-xs font-bold text-[#18181B] leading-snug block">{{ __('مقاوم للماء والخدوش السطحية') }}</span>
 </div>
 </div>
 <div class="bg-white border border-[#E5E7EB] rounded-xl p-2.5 sm:p-3 flex items-center gap-2.5 sm:gap-3">
 <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-[#C5A059]/10 text-[#C5A059] flex items-center justify-center font-bold text-sm shrink-0">
 
 </div>
 <div class="min-w-0 flex-1">
 <span class="text-[10px] text-[#71717A] block">{{ __('التغليف والإضافات') }}</span>
 <span class="text-xs font-bold text-[#18181B] leading-snug block">{{ __('صندوق ملكي فاخر مع كرت ضمان') }}</span>
 </div>
 </div>
 </div>
 </div>

 {{-- Actions Section: Quantity & Buttons --}}
 <div class="space-y-3.5 sm:space-y-4 pt-4 border-t border-[#E5E7EB]">
 @if($product->available_stock > 0)
 <div class="flex flex-wrap items-center justify-between sm:justify-start gap-3 sm:gap-4">
 <div class="flex items-center gap-2.5 sm:gap-3">
 <span class="text-xs font-bold text-[#18181B] shrink-0">{{ __('الكمية:') }}</span>
 
 {{-- Quantity Selector --}}
 <div class="flex items-center border border-[#E5E7EB] rounded-xl sm:rounded-2xl bg-[#F8F9FA] p-1">
 <button type="button" 
 @click="decrement()"
 :disabled="quantity <= 1"
 class="w-8 h-8 rounded-lg sm:rounded-xl bg-white border border-gray-200 text-gray-700 hover:border-[#C5A059] hover:text-[#C5A059] flex items-center justify-center font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer active:scale-95">
 -
 </button>
 <span class="w-10 sm:w-12 text-center font-bold text-sm text-[#18181B] tabular-nums select-none" x-text="quantity"></span>
 <button type="button" 
 @click="increment()"
 :disabled="quantity >= maxStock || isLimited"
 class="w-8 h-8 rounded-lg sm:rounded-xl bg-white border border-gray-200 text-gray-700 hover:border-[#C5A059] hover:text-[#C5A059] flex items-center justify-center font-bold transition-all disabled:opacity-40 disabled:cursor-not-allowed cursor-pointer active:scale-95"
 :title="isLimited ? '{{ __('الكمية محدودة: قطعة واحدة كحد أقصى') }}' : '{{ __('زيادة الكمية') }}'">
 +
 </button>
 </div>
 </div>

 <template x-if="isLimited">
 <span class="text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200/80 px-2.5 py-1 rounded-full">
 {{ __('كمية محدودة بالمخزون (الحد الأقصى قطعة واحدة فقط)') }}
 </span>
 </template>
 <template x-if="!isLimited">
 <span class="text-xs text-[#71717A]">
 {{ __('الحد الأقصى للطلب:') }} <span class="font-bold text-[#18181B]" x-text="maxStock"></span>
 </span>
 </template>
 </div>

 {{-- Action Buttons: Buy Now, Add to Cart & Direct WhatsApp --}}
 <div class="space-y-2.5 pt-1">
 {{-- Primary: Express Direct Order (Buy Now) --}}
 <button type="button" 
 @click="buyNow()"
 :disabled="isAdding"
 class="w-full py-4 px-6 rounded-xl sm:rounded-2xl bg-[#18181B] hover:bg-black text-white text-xs sm:text-sm font-bold transition-all duration-300 shadow-lg hover:shadow-xl active:scale-98 flex items-center justify-center gap-2.5 cursor-pointer">
 <span></span>
 <span x-text="isAdding ? '{{ __('جاري تحويلك لإتمام الطلب...') }}' : '{{ __('طلب مباشر فوري (شراء الآن)') }}'"></span>
 <span>&larr;</span>
 </button>

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
 {{-- Add To Cart Button --}}
 <button type="button" 
 @click="addToCart()"
 :disabled="isAdding"
 class="w-full py-3 px-4 rounded-xl sm:rounded-2xl bg-[#C5A059] hover:bg-[#b08e4d] text-white text-xs sm:text-sm font-bold transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center gap-2 cursor-pointer group">
 <svg class="w-4 h-4 text-white group-hover:scale-110 transition-transform shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
 </svg>
 <span x-text="isAdding ? '{{ __('جاري الإضافة...') }}' : '{{ __('إضافة إلى السلة') }}'"></span>
 </button>

 {{-- Direct WhatsApp Inquiry Button --}}
 <a href="{{ $whatsappInquiryUrl }}" 
 target="_blank" 
 class="w-full py-3 px-4 rounded-xl sm:rounded-2xl bg-[#25D366] hover:bg-[#1EBE5D] text-white text-xs sm:text-sm font-bold transition-all duration-300 shadow-md hover:shadow-lg active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
 <svg class="w-4 h-4 fill-current shrink-0" viewBox="0 0 24 24">
 <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.044c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
 </svg>
 <span>{{ __('استفسار واتساب') }}</span>
 </a>
 </div>
 </div>
 @else
 {{-- Out of Stock & Notify via WhatsApp --}}
 <div class="p-3.5 sm:p-4 bg-rose-50 border border-rose-200 rounded-xl sm:rounded-2xl text-center space-y-2.5 sm:space-y-3">
 <p class="text-rose-700 text-xs sm:text-sm font-bold">
 {{ __('هذا المنتج غير متوفر حالياً في المخزون.') }}
 </p>
 <a href="{{ $whatsappInquiryUrl }}" 
 target="_blank" 
 class="inline-flex items-center justify-center gap-2 py-2.5 sm:py-3 px-5 sm:px-6 rounded-xl sm:rounded-2xl bg-[#25D366] text-white text-xs font-bold hover:bg-[#1EBE5D] transition-all shadow-sm">
 <span>{{ __('طلب توفير خاص عبر واتساب') }}</span>
 </a>
 </div>
 @endif
 </div>
 </div>
 </div>
 </div>

 {{-- Related Products Section --}}
 @if($relatedProducts->count() > 0)
 <div class="mt-8 sm:mt-12 lg:mt-16">
 <div class="flex items-center justify-between mb-4 sm:mb-6 lg:mb-8 flex-wrap gap-2">
 <div>
 <span class="text-[11px] sm:text-xs font-bold text-[#C5A059] uppercase tracking-wider block mb-0.5 sm:mb-1">
 {{ __('مقتنيات مقترحة') }}
 </span>
 <h2 class="text-base sm:text-xl lg:text-2xl font-extrabold text-[#18181B]">
 {{ __('مقتنيات فاخرة قد تنال إعجابك') }} 
 </h2>
 </div>
 <a href="{{ route('product.shop') }}" class="text-xs sm:text-sm font-bold text-[#18181B] hover:text-[#C5A059] transition-colors flex items-center gap-1 shrink-0">
 <span>{{ __('عرض كافة المقتنيات') }}</span>
 <span>←</span>
 </a>
 </div>

 <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 sm:gap-4 md:gap-6">
 @foreach($relatedProducts as $relProduct)
 <x-product-card :product="$relProduct" />
 @endforeach
 </div>
 </div>
 @endif

 </div>
</div>
@endsection

