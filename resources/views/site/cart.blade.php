@extends('layouts.site')

@section('content')
@php
 $activeCurrency = get_active_currency();
@endphp

<div class="bg-[#F8F9FA] min-h-[85vh] py-8 sm:py-12 text-[#18181B]" dir="rtl"
 x-data="{
 loading: false,
 cart: {
 count: {{ $cartCount ?? count($items) }},
 items: [],
 formatted_subtotal: '{{ $totals['formatted_subtotal'] }}',
 formatted_tax: '{{ $totals['formatted_tax'] }}',
 formatted_total: '{{ $totals['formatted_total'] }}',
 tax_rate: {{ $totals['tax_rate'] ?? 0 }}
 },
 init() {
 this.fetchCartData();
 window.addEventListener('cart-updated', (e) => {
 if (e.detail) {
 this.updateFromPayload(e.detail);
 }
 });
 },
 fetchCartData() {
 fetch('{{ route('cart.payload') }}')
 .then(r => r.json())
 .then(data => {
 this.updateFromPayload(data);
 })
 .catch(() => {});
 },
 updateFromPayload(data) {
 this.cart = data;
 },
 changeQuantity(key, newQty) {
 if (this.loading) return;
 if (newQty < 1) {
 this.removeItem(key);
 return;
 }

 // Check if item has limited stock (5 pieces or less)
 const item = this.cart.items ? this.cart.items.find(i => i.key === key) : null;
 if (item && item.type === 'product' && (item.is_limited_stock || (item.available_stock !== undefined && item.available_stock <= 5)) && newQty > 1) {
 toastr.warning('{{ __("عذراً، هذا المنتج ذو كمية محدودة (5 قطع أو أقل بالمخزون)، الحد الأقصى المسموح به للطلب هو قطعة واحدة فقط.") }}');
 return;
 }

 this.loading = true;
 $.post('{{ route('update.cart.quantity') }}', {
 item_key: key,
 quantity: newQty
 }).done((res) => {
 if (res.success) {
 toastr.success(res.message);
 if (res.cart_payload) {
 this.updateFromPayload(res.cart_payload);
 window.dispatchEvent(new CustomEvent('cart-updated', { detail: res.cart_payload }));
 } else {
 this.fetchCartData();
 }
 } else {
 toastr.error(res.message);
 }
 }).fail((xhr) => {
 const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __('حدث خطأ أثناء تحديث الكمية.') }}';
 toastr.error(msg);
 }).always(() => {
 this.loading = false;
 });
 },
 removeItem(key) {
 if (this.loading) return;
 window.showLuxuryConfirm({
 title: '{{ __("حذف عنصر من السلة") }}',
 message: '{{ __("هل تود حذف هذا العنصر من السلة؟") }}',
 confirmText: '{{ __("نعم، احذف") }}',
 cancelText: '{{ __("تراجع") }}',
 icon: '',
 iconBg: 'bg-rose-50 text-rose-600 border-rose-200',
 confirmBtnClass: 'bg-rose-600 hover:bg-rose-700 text-white',
 onConfirm: () => {
 this.loading = true;
 $.post('{{ route('removefromcart') }}', {
 item_key: key
 }).done((res) => {
 if (res.success) {
 toastr.success(res.message);
 if (res.cart_payload) {
 this.updateFromPayload(res.cart_payload);
 window.dispatchEvent(new CustomEvent('cart-updated', { detail: res.cart_payload }));
 } else {
 this.fetchCartData();
 }
 } else {
 toastr.error(res.message);
 }
 }).fail(() => {
 toastr.error('{{ __("تعذر حذف العنصر.") }}');
 }).always(() => {
 this.loading = false;
 });
 }
 });
 },
 clearCart() {
 if (this.loading) return;
 window.showLuxuryConfirm({
 title: '{{ __("تفريغ سلة المشتريات") }}',
 message: '{{ __("هل أنت متأكد من رغبتك في تفريغ محتويات السلة بالكامل؟") }}',
 confirmText: '{{ __("نعم، تفريغ السلة") }}',
 cancelText: '{{ __("إبقاء المنتجات") }}',
 icon: '',
 iconBg: 'bg-amber-50 text-amber-600 border-amber-200',
 confirmBtnClass: 'bg-[#18181B] hover:bg-rose-600 text-white',
 onConfirm: () => {
 this.loading = true;
 $.post('{{ route('clearcart') }}').done((res) => {
 toastr.success(res.message);
 if (res.cart_payload) {
 this.updateFromPayload(res.cart_payload);
 window.dispatchEvent(new CustomEvent('cart-updated', { detail: res.cart_payload }));
 } else {
 this.fetchCartData();
 }
 }).fail(() => {
 toastr.error('{{ __("تعذر تفريغ السلة.") }}');
 }).always(() => {
 this.loading = false;
 });
 }
 });
 }
 }">
 <div class="w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
 
 {{-- Header Breadcrumb & Title --}}
 <div class="bg-white border border-[#EADBCC] rounded-3xl p-6 sm:p-8 mb-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 <div>
 <nav class="text-xs text-[#71717A] flex items-center gap-2 mb-2 font-medium">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors">{{ __('الرئيسية') }}</a>
 <span>/</span>
 <span class="text-[#18181B] font-semibold">{{ __('سلة الشراء') }}</span>
 </nav>
 <h1 class="text-2xl sm:text-3xl font-bold text-[#18181B] flex items-center gap-3">
 <span></span>
 <span>{{ __('حقيبة المقتنيات والتسوق') }}</span>
 <span x-show="cart.count > 0" class="text-xs px-2.5 py-0.5 rounded-full bg-[#C5A059] text-white font-bold tabular-nums" x-text="cart.count + ' مقتنيات'"></span>
 </h1>
 </div>

 <template x-if="cart.items && cart.items.length > 0">
 <button type="button" 
 @click="clearCart()"
 :disabled="loading"
 class="self-start sm:self-auto px-5 py-2.5 rounded-full border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold transition-all shadow-2xs active:scale-95 flex items-center gap-2 cursor-pointer disabled:opacity-50">
 <span></span>
 <span>{{ __('تفريغ السلة بالكامل') }}</span>
 </button>
 </template>
 </div>

 {{-- Content Area: Dynamically bound via Alpine.js --}}
 <template x-if="cart.items && cart.items.length > 0">
 <div class="flex flex-col lg:flex-row gap-8 items-start" :class="loading ? 'opacity-70 pointer-events-none' : ''">
 
 {{-- Right Side (in RTL): Cart Items List --}}
 <div class="w-full lg:w-2/3 space-y-4">
 <div class="bg-white border border-[#EADBCC] rounded-3xl overflow-hidden shadow-xs">
 
 {{-- Items Table Header --}}
 <div class="hidden sm:flex items-center justify-between px-6 py-4 bg-[#FAF8F5] border-b border-[#EADBCC]/60 text-xs font-bold text-[#71717A]">
 <span class="w-1/2">{{ __('المنتج / العرض') }}</span>
 <span class="w-1/6 text-center">{{ __('السعر') }}</span>
 <span class="w-1/6 text-center">{{ __('الكمية') }}</span>
 <span class="w-1/6 text-end">{{ __('الإجمالي') }}</span>
 </div>

 {{-- Items Rows (Rendered Instantly via Alpine x-for) --}}
 <div class="divide-y divide-gray-100">
 <template x-for="item in cart.items" :key="item.key">
 <div class="p-4 sm:p-6 transition-colors hover:bg-[#FAF8F5]/50 cart-item-row">
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 
 {{-- Product Info & Thumbnail --}}
 <div class="w-full sm:w-1/2 flex items-center gap-3.5">
 <div class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#F8F9FA] border border-gray-200/80 p-1 shrink-0 overflow-hidden flex items-center justify-center">
 <img :src="item.image" 
 :alt="item.name" 
 class="w-full h-full object-cover rounded-xl">
 <template x-if="item.type === 'bundle'">
 <span class="absolute top-1 start-1 bg-rose-600 text-white text-[8px] font-black px-1.5 py-0.5 rounded-full shadow-xs">
 {{ __('بكج') }}
 </span>
 </template>
 </div>

 <div class="min-w-0 flex-1 text-start">
 <template x-if="item.type === 'bundle'">
 <span class="inline-block bg-[#C5A059]/15 text-[#8C6D23] text-[10px] font-extrabold px-2 py-0.5 rounded-full mb-1">
 {{ __('عرض مجمع فاخر') }}
 </span>
 </template>
 <h3 class="font-bold text-sm text-[#18181B] truncate" :title="item.name" x-text="item.name"></h3>
 <template x-if="item.is_limited_stock">
 <div class="mt-1 flex items-center gap-1.5 text-[11px] font-semibold text-amber-800 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-full w-fit">
 <span></span>
 <span>{{ __('كمية محدودة (الحد الأقصى قطعة واحدة)') }}</span>
 </div>
 </template>
 <div class="sm:hidden text-xs text-[#71717A] mt-1 flex items-center gap-2">
 <span class="font-bold text-[#18181B]" x-text="item.formatted_price_usd"></span>
 <span>×</span>
 <span x-text="item.quantity"></span>
 </div>
 </div>
 </div>

 {{-- Unit Price (Desktop) --}}
 <div class="hidden sm:block w-1/6 text-center">
 <span class="font-bold text-sm text-[#18181B] tabular-nums" x-text="item.formatted_price_usd"></span>
 </div>

 {{-- Quantity Stepper --}}
 <div class="w-full sm:w-1/6 flex items-center justify-between sm:justify-center gap-3">
 <span class="sm:hidden text-xs font-semibold text-[#71717A]">{{ __('الكمية:') }}</span>
 <template x-if="item.type !== 'bundle'">
 <div class="flex items-center border border-gray-200 rounded-full bg-white p-0.5 shadow-2xs">
 <button type="button" 
 @click="changeQuantity(item.key, item.quantity - 1)"
 class="w-7 h-7 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 hover:text-[#18181B] text-xs font-bold transition-all active:scale-90 cursor-pointer">
 -
 </button>
 <span class="w-8 text-center text-xs font-bold text-[#18181B] tabular-nums" x-text="item.quantity"></span>
 <button type="button" 
 @click="changeQuantity(item.key, item.quantity + 1)"
 :class="item.is_limited_stock ? 'text-amber-600 hover:bg-amber-50' : 'text-gray-500 hover:bg-gray-100 hover:text-[#18181B]'"
 class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition-all active:scale-90 cursor-pointer"
 :title="item.is_limited_stock ? '{{ __('الكمية محدودة: قطعة واحدة كحد أقصى') }}' : '{{ __('زيادة الكمية') }}'">
 +
 </button>
 </div>
 </template>
 <template x-if="item.type === 'bundle'">
 <span class="px-3 py-1 bg-gray-100 text-[#18181B] rounded-full text-xs font-bold" x-text="item.quantity"></span>
 </template>
 </div>

 {{-- Subtotal & Remove Button --}}
 <div class="w-full sm:w-1/6 flex items-center justify-between sm:justify-end gap-3">
 <span class="sm:hidden text-xs font-semibold text-[#71717A]">{{ __('الإجمالي:') }}</span>
 <div class="text-end">
 <span class="font-black text-sm text-[#C5A059] tabular-nums block" x-text="item.formatted_total_usd"></span>
 </div>
 <button type="button" 
 @click="removeItem(item.key)"
 class="w-8 h-8 rounded-full bg-gray-50 hover:bg-red-50 text-gray-400 hover:text-red-500 border border-gray-100 flex items-center justify-center text-xs transition-all hover:scale-110 active:scale-95 cursor-pointer"
 title="{{ __('حذف من السلة') }}">
 
 </button>
 </div>

 </div>
 </div>
 </template>
 </div>

 {{-- Footer Toolbar --}}
 <div class="p-4 sm:p-6 bg-[#FAF8F5]/80 border-t border-[#EADBCC]/60 flex flex-col sm:flex-row items-center justify-between gap-3">
 <a href="{{ route('product.shop') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-full border border-gray-200 hover:border-[#C5A059] text-[#18181B] text-xs font-bold transition-all text-center hover:bg-white shadow-2xs">
 &rarr; {{ __('متابعة التسوق وإضافة المزيد') }}
 </a>
 <span class="text-xs text-[#71717A]">
 {{ __('الأسعار شاملة ومحدثة بأسعار الصرف اليومية') }}
 </span>
 </div>

 </div>
 </div>

 {{-- Left Side (in RTL): Order Summary & Checkout Card --}}
 <div class="w-full lg:w-1/3 space-y-4">
 <div class="bg-white border border-[#EADBCC] rounded-3xl p-6 sm:p-7 shadow-sm sticky top-24 space-y-5">
 
 <div class="border-b border-gray-100 pb-4">
 <h2 class="text-base font-bold text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('ملخص الحساب والطلب') }}</span>
 </h2>
 </div>

 {{-- Totals Breakdown --}}
 <div class="space-y-3 text-xs text-[#71717A]">
 <div class="flex items-center justify-between">
 <span>{{ __('المجموع الفرعي') }}</span>
 <span class="font-bold text-[#18181B] text-sm tabular-nums" x-text="cart.formatted_subtotal"></span>
 </div>

 <template x-if="cart.tax_rate > 0">
 <div class="flex items-center justify-between">
 <span>{{ __('ضريبة القيمة المضافة') }} (<span x-text="cart.tax_rate"></span>%)</span>
 <span class="font-semibold text-[#18181B] tabular-nums" x-text="cart.formatted_tax"></span>
 </div>
 </template>

 <div class="flex items-center justify-between">
 <span>{{ __('رسوم الشحن والتوصيل') }}</span>
 <span class="text-[#059669] font-bold bg-[#ECFDF5] px-2.5 py-1 rounded-full text-[11px]">
 {{ __('تحدد وتؤكد عبر واتساب') }}
 </span>
 </div>
 </div>

 <div class="border-t border-dashed border-gray-200 pt-4 space-y-1">
 <div class="flex items-baseline justify-between">
 <span class="text-sm font-bold text-[#18181B]">{{ __('المجموع النهائي:') }}</span>
 <div class="text-end">
 <span class="text-2xl font-black text-[#C5A059] tabular-nums block leading-none" x-text="cart.formatted_total"></span>
 </div>
 </div>
 </div>

 {{-- CTAs --}}
 <div class="space-y-3 pt-2">
 <a href="{{ route('checkout.index') }}" 
 class="w-full py-3.5 rounded-full bg-[#C5A059] hover:bg-[#18181B] text-white text-xs font-bold transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-2 active:scale-98">
 <span></span>
 <span>{{ __('متابعة إتمام الطلب وتأكيد الواتساب') }} &larr;</span>
 </a>

 <div class="bg-[#FAF8F5] border border-[#EADBCC]/60 rounded-2xl p-3.5 text-[11px] text-[#71717A] space-y-1.5">
 <div class="flex items-center gap-2 text-[#18181B] font-bold">
 <span></span>
 <span>{{ __('تسوق آمن ودفع عند الاستلام') }}</span>
 </div>
 <p class="text-[10px] leading-relaxed text-gray-500">
 {{ __('يتم مراجعة طلبك والتواصل معك فوراً لتأكيد العنوان وموعد الشحن.') }}
 </p>
 </div>
 </div>

 </div>
 </div>

 </div>
 </template>

 {{-- Empty State (Triggered reactively when cart is empty without page reload) --}}
 <template x-if="!cart.items || cart.items.length === 0">
 <div class="bg-white border border-[#EADBCC] rounded-3xl p-12 sm:p-16 text-center space-y-4 shadow-xs max-w-xl mx-auto">
 <div class="w-20 h-20 mx-auto rounded-full bg-[#FAF8F5] border border-[#EADBCC] flex items-center justify-center text-4xl shadow-inner">
 
 </div>
 <h3 class="text-lg font-bold text-[#18181B]">{{ __('سلة التسوق فارغة حالياً') }}</h3>
 <p class="text-xs text-[#71717A] max-w-sm mx-auto leading-relaxed">
 {{ __('لم تقم بإضافة أي منتجات أو عروض إلى حقيبة التسوق بعد. استكشف تشكيلتنا الفاخرة واختر ما يناسب ذوقك.') }}
 </p>
 <div class="pt-2">
 <a href="{{ route('product.shop') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white text-xs font-bold transition-all shadow-xs hover:shadow-md">
 <span>{{ __('استكشف تشكيلة المتجر الآن') }}</span>
 <span>&larr;</span>
 </a>
 </div>
 </div>
 </template>

 </div>
</div>
@endsection