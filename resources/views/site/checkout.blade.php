@extends('layouts.site')

@section('content')
@php
 $activeCurrency = get_active_currency();
@endphp

<div class="bg-[#F8F9FA] min-h-[85vh] py-8 sm:py-12 text-[#18181B]" dir="rtl">
 <div class="w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
 
 {{-- Header Breadcrumb & Title --}}
 <div class="bg-white border border-[#EADBCC] rounded-3xl p-6 sm:p-8 mb-8 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 <div>
 <nav class="text-xs text-[#71717A] flex items-center gap-2 mb-2 font-medium">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors">{{ __('الرئيسية') }}</a>
 <span>/</span>
 <a href="{{ route('cart') }}" class="hover:text-[#C5A059] transition-colors">{{ __('سلة الشراء') }}</a>
 <span>/</span>
 <span class="text-[#18181B] font-semibold">{{ __('إتمام الطلب السريع') }}</span>
 </nav>
 <h1 class="text-2xl sm:text-3xl font-bold text-[#18181B] flex items-center gap-3">
 <span></span>
 <span>{{ __('تأكيد الطلب عبر واتساب') }}</span>
 </h1>
 <p class="text-xs sm:text-sm text-[#71717A] mt-1">
 {{ __('أدخل بيانات التوصيل وسيتم تسجيل طلبك في النظام فوراً ونقلك إلى محادثة واتساب لتأكيد الشحن.') }}
 </p>
 </div>

 <div class="flex items-center gap-2 bg-[#ECFDF5] border border-emerald-200 text-emerald-800 px-4 py-2 rounded-2xl text-xs font-bold self-start sm:self-auto shadow-2xs">
 <span></span>
 <span>{{ __('الدفع عند الاستلام بعد الفحص والتأكد') }}</span>
 </div>
 </div>

 @if ($errors->any())
 <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-2xl mb-6 text-xs shadow-xs space-y-1">
 <span class="font-bold block text-sm"> {{ __('يرجى التحقق من صحة البيانات التالية:') }}</span>
 <ul class="list-disc list-inside space-y-0.5">
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <form id="checkoutForm" action="{{ route('checkout.place') }}" method="POST" @submit.prevent="submitOrder">
 @csrf
 <div class="flex flex-col lg:flex-row gap-8 items-start">
 
 {{-- Form Info (Right in RTL) --}}
 <div class="w-full lg:w-3/5 space-y-6">
 <div class="bg-white border border-[#EADBCC] rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
 
 <div class="border-b border-gray-100 pb-4 flex items-center justify-between">
 <h2 class="text-base font-bold text-[#18181B] flex items-center gap-2">
 <span class="w-7 h-7 rounded-full bg-[#18181B] text-white flex items-center justify-center text-xs font-bold">1</span>
 <span>{{ __('معلومات العميل وعنوان التوصيل') }}</span>
 </h2>
 <span class="text-xs text-[#71717A]">{{ __('الحقول بالرمز (*) إلزامية') }}</span>
 </div>

 {{-- Name Input --}}
 <div class="space-y-1.5">
 <label class="block text-xs font-bold text-[#18181B]">
 {{ __('الاسم الكامل') }} <span class="text-rose-600">*</span>
 </label>
 <input type="text" 
 name="name" 
 value="{{ old('name', auth()->user()?->name) }}" 
 placeholder="{{ __('اكتب اسمك الكريم...') }}" 
 required 
 class="w-full text-xs bg-[#F8F9FA] border border-[#E5E7EB] rounded-2xl px-4 py-3 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all shadow-2xs">
 </div>

 {{-- Phone Input --}}
 <div class="space-y-1.5">
 <label class="block text-xs font-bold text-[#18181B]">
 {{ __('رقم الهاتف أو الواتساب') }} <span class="text-rose-600">*</span>
 </label>
 <div class="relative">
 <input type="tel" 
 name="phone" 
 value="{{ old('phone', auth()->user()?->phone) }}" 
 placeholder="{{ __('مثال: 01202325201 أو 0999123456') }}" 
 required 
 dir="ltr"
 class="w-full text-xs text-end bg-[#F8F9FA] border border-[#E5E7EB] rounded-2xl px-4 py-3 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all shadow-2xs">
 </div>
 <span class="text-[11px] text-[#71717A] block">
 {{ __('سنقوم بالتواصل معك عبر هذا الرقم لتأكيد تفاصيل التوصيل وموعد الشحن.') }}
 </span>
 </div>

 {{-- Address Input --}}
 <div class="space-y-1.5">
 <label class="block text-xs font-bold text-[#18181B]">
 {{ __('عنوان التوصيل بالتفصيل') }} <span class="text-rose-600">*</span>
 </label>
 <textarea name="address" 
 rows="3" 
 placeholder="{{ __('المدينة، المنطقة، اسم الشارع، وأقرب نقطة دالة مميزة...') }}" 
 required 
 class="w-full text-xs bg-[#F8F9FA] border border-[#E5E7EB] rounded-2xl p-4 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all shadow-2xs leading-relaxed">{{ old('address', auth()->user()?->address) }}</textarea>
 </div>

 {{-- Additional Notes --}}
 <div class="space-y-1.5">
 <label class="block text-xs font-bold text-[#18181B]">
 {{ __('ملاحظات إضافية على الطلب') }} <span class="text-gray-400 font-normal">({{ __('اختياري') }})</span>
 </label>
 <textarea name="notes" 
 rows="2" 
 placeholder="{{ __('أي تفاصيل خاصة بتوقيت الاستلام، أو التغليف الفاخر كهدية...') }}" 
 class="w-full text-xs bg-[#F8F9FA] border border-[#E5E7EB] rounded-2xl p-4 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059] focus:bg-white transition-all shadow-2xs">{{ old('notes') }}</textarea>
 </div>

 </div>
 </div>

 {{-- Order Review & WhatsApp CTA (Left in RTL) --}}
 <div class="w-full lg:w-2/5 space-y-6">
 <div class="bg-white border border-[#EADBCC] rounded-3xl p-6 sm:p-7 shadow-sm sticky top-24 space-y-5">
 
 <div class="border-b border-gray-100 pb-4">
 <h2 class="text-base font-bold text-[#18181B] flex items-center gap-2">
 <span class="w-7 h-7 rounded-full bg-[#C5A059] text-white flex items-center justify-center text-xs font-bold">2</span>
 <span>{{ __('مراجعة المنتجات والملخص') }}</span>
 </h2>
 </div>

 {{-- Items Preview List --}}
 <div class="space-y-3 max-h-64 overflow-y-auto no-scrollbar pr-1 divide-y divide-gray-50">
 @foreach($items as $item)
 <div class="flex items-center justify-between gap-3 pt-2.5 first:pt-0">
 <div class="flex items-center gap-3 min-w-0">
 <div class="w-12 h-12 rounded-xl bg-[#F8F9FA] border border-gray-100 p-1 shrink-0 overflow-hidden flex items-center justify-center">
 <img src="{{ $item['image'] ?? asset('assets/site/img/product/product-1.jpg') }}" 
 alt="{{ $item['name'] }}" 
 class="w-full h-full object-cover rounded-lg">
 </div>
 <div class="min-w-0 text-start">
 <h4 class="text-xs font-bold text-[#18181B] truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</h4>
 <span class="text-[11px] text-[#71717A] block tabular-nums">
 {{ format_currency($item['price']) }} × {{ $item['quantity'] }}
 </span>
 </div>
 </div>
 <span class="font-extrabold text-xs text-[#18181B] shrink-0 tabular-nums">
 {{ format_currency($item['total']) }}
 </span>
 </div>
 @endforeach
 </div>

 {{-- Totals Breakdown --}}
 <div class="border-t border-gray-100 pt-4 space-y-2.5 text-xs text-[#71717A]">
 <div class="flex items-center justify-between">
 <span>{{ __('المجموع الفرعي') }}</span>
 <span class="font-bold text-[#18181B] text-sm tabular-nums">{{ $totals['formatted_subtotal'] }}</span>
 </div>

 @if($totals['tax_rate'] > 0)
 <div class="flex items-center justify-between">
 <span>{{ __('ضريبة القيمة المضافة') }} ({{ $totals['tax_rate'] }}%)</span>
 <span class="font-semibold text-[#18181B] tabular-nums">{{ $totals['formatted_tax'] }}</span>
 </div>
 @endif

 <div class="flex items-center justify-between">
 <span>{{ __('رسوم الشحن والتوصيل') }}</span>
 <span class="text-[#059669] font-bold bg-[#ECFDF5] px-2.5 py-0.5 rounded-full text-[11px]">
 {{ __('تحدد وتؤكد عبر واتساب') }}
 </span>
 </div>
 </div>

 {{-- Grand Total --}}
 <div class="border-t border-dashed border-gray-200 pt-4 space-y-1">
 <div class="flex items-baseline justify-between">
 <span class="text-sm font-bold text-[#18181B]">{{ __('الإجمالي النهائي:') }}</span>
 <div class="text-end">
 <span class="text-2xl font-black text-[#C5A059] tabular-nums block leading-none">
 {{ $totals['formatted_total'] }}
 </span>
 </div>
 </div>
 </div>

 {{-- Submit Button --}}
 <div class="space-y-3 pt-2">
 <button type="submit" 
 id="submitBtn"
 class="w-full py-4 rounded-full bg-[#25D366] hover:bg-[#1eb857] text-white text-sm font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 active:scale-98 cursor-pointer disabled:opacity-75 disabled:cursor-not-allowed">
 <span id="btnIcon"></span>
 <span id="btnText">{{ __('تأكيد الطلب وإرسال لواتساب') }} &larr;</span>
 </button>

 <div class="bg-[#FAF8F5] border border-[#EADBCC]/60 rounded-2xl p-3.5 text-[11px] text-[#71717A] space-y-1.5 text-start">
 <div class="flex items-center gap-2 text-[#18181B] font-bold">
 <span></span>
 <span>{{ __('تسجيل تلقائي وتأكيد فوري') }}</span>
 </div>
 <p class="text-[10px] leading-relaxed text-gray-500">
 {{ __('بمجرد الضغط، سيتم حفظ الطلب في لوحة التحكم وتوجيهك مباشرة إلى محادثة واتساب الرسمية مع كافة تفاصيل المنتجات وصورها.') }}
 </p>
 </div>
 </div>

 </div>
 </div>

 </div>
 </form>

 {{-- Thank You & WhatsApp Redirect Overlay Modal --}}
 <div id="orderSuccessModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-4" dir="rtl">
 <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 text-center space-y-5 shadow-2xl border border-[#EADBCC] transform transition-all animate-bounce-short">
 <div class="w-16 h-16 bg-[#ECFDF5] border-2 border-emerald-300 rounded-full flex items-center justify-center mx-auto text-3xl shadow-inner">
 
 </div>
 
 <div>
 <h3 class="text-xl font-bold text-[#18181B]">{{ __('تم تسجيل طلبك بنجاح!') }}</h3>
 <p class="text-xs text-[#71717A] mt-1" id="modalOrderNumber"></p>
 </div>

 <div class="bg-[#FAF8F5] border border-[#EADBCC]/70 rounded-2xl p-4 text-xs text-[#71717A] space-y-2 text-start">
 <div class="flex items-center gap-2 text-emerald-800 font-bold">
 <span></span>
 <span>{{ __('تم تفريغ السلة وتثبيت حجز المنتجات') }}</span>
 </div>
 <p class="text-[11px] leading-relaxed text-gray-600">
 {{ __('سيتم فتح محادثة واتساب الآن تلقائياً لتأكيد شحن طلبك. إذا لم تفتح تلقائياً اضغط على الزر الأخضر أدناه:') }}
 </p>
 </div>

 <div class="space-y-2">
 <a id="modalWhatsappBtn" href="#" target="_blank" class="w-full py-3.5 rounded-full bg-[#25D366] hover:bg-[#1eb857] text-white text-sm font-bold transition-all shadow-md flex items-center justify-center gap-2">
 <span></span>
 <span>{{ __('فتح محادثة واتساب الآن') }} &larr;</span>
 </a>

 <a href="{{ route('site.home') }}" class="w-full py-2.5 rounded-full bg-gray-100 hover:bg-gray-200 text-[#18181B] text-xs font-semibold block transition-colors">
 {{ __('العودة للصفحة الرئيسية') }}
 </a>
 </div>
 </div>
 </div>

 </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
 const form = document.getElementById('checkoutForm');
 const submitBtn = document.getElementById('submitBtn');
 const btnIcon = document.getElementById('btnIcon');
 const btnText = document.getElementById('btnText');
 const modal = document.getElementById('orderSuccessModal');
 const modalWhatsappBtn = document.getElementById('modalWhatsappBtn');
 const modalOrderNumber = document.getElementById('modalOrderNumber');

 if (!form) return;

 form.addEventListener('submit', function(e) {
 e.preventDefault();

 // UI Loading state
 submitBtn.disabled = true;
 btnIcon.textContent = '⏳';
 btnText.textContent = '{{ __("جاري تسجيل الطلب وتجهيز المحادثة...") }}';

 const formData = new FormData(form);

 fetch(form.action, {
 method: 'POST',
 body: formData,
 headers: {
 'X-Requested-With': 'XMLHttpRequest',
 'Accept': 'application/json'
 }
 })
 .then(async (response) => {
 const data = await response.json();
 if (response.ok && data.success) {
 // 1. Instantly reset client-side cart & header badge to 0
 window.dispatchEvent(new CustomEvent('cart-updated', {
 detail: {
 count: 0,
 items: [],
 subtotal: 0,
 tax_rate: 0,
 tax_amount: 0,
 grand_total: 0,
 subtotal_usd: 0,
 formatted_subtotal_usd: '0.00 ج.م',
 formatted_subtotal: '0.00 ج.م',
 formatted_tax: '0.00 ج.م',
 formatted_total: '0.00 ج.م'
 }
 }));

 // 2. Setup modal WhatsApp URL
 const whatsappUrl = data.whatsapp_url;
 modalWhatsappBtn.href = whatsappUrl;
 if (data.order_number) {
 modalOrderNumber.textContent = '{{ __("رقم الطلب:") }} ' + data.order_number;
 }

 // 3. Show success confirmation modal
 modal.classList.remove('hidden');

 // 4. Try opening WhatsApp directly in new tab/window
 const newTab = window.open(whatsappUrl, '_blank');
 if (!newTab || newTab.closed || typeof newTab.closed === 'undefined') {
 // Pop-up blocked, redirect directly after a brief pause
 setTimeout(() => {
 window.location.href = whatsappUrl;
 }, 1200);
 }
 } else {
 submitBtn.disabled = false;
 btnIcon.textContent = '';
 btnText.textContent = '{{ __("تأكيد الطلب وإرسال لواتساب") }} ←';

 const errorMsg = data.message || '{{ __("حدث خطأ أثناء حفظ الطلب، يرجى مراجعة البيانات والمحاولة مجدداً.") }}';
 if (typeof toastr !== 'undefined') {
 toastr.error(errorMsg);
 } else {
 alert(errorMsg);
 }
 }
 })
 .catch(err => {
 submitBtn.disabled = false;
 btnIcon.textContent = '';
 btnText.textContent = '{{ __("تأكيد الطلب وإرسال لواتساب") }} ←';
 if (typeof toastr !== 'undefined') {
 toastr.error('{{ __("حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى.") }}');
 } else {
 alert('حدث خطأ في الاتصال');
 }
 });
 });
});
</script>
@endsection
