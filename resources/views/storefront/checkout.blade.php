{{-- resources/views/storefront/checkout.blade.php --}}
@extends('layouts.storefront')

@section('title', 'إتمام الطلب والدفع | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-8 bg-canvas-base min-h-screen"
 x-data="{
 deliveryType: '{{ old('delivery_type', $deliveryType) }}',
 paymentMethod: '{{ old('payment_method', 'cash') }}',
 selectedBranch: '{{ old('branch_id', $branches->first()?->id ?? 1) }}',
 branchesData: {{ json_encode($branches->keyBy('id')) }},
 submitting: false,
 deliveryFee: {{ (float)$totals['delivery_fee'] }},
 subtotal: {{ (float)$totals['subtotal'] }},
 discountAmount: {{ (float)($totals['discount_amount'] ?? 0) }},
 couponCode: '{{ $totals['coupon_code'] ?? '' }}',
 get discountedSubtotal() {
     return Math.max(0, this.subtotal - this.discountAmount);
 },
 get grandTotal() {
     let fee = this.deliveryType === 'pickup' ? 0 : this.deliveryFee;
     return this.discountedSubtotal + fee;
 }
 }">
 
 <div class="container mx-auto px-4 space-y-6">
 
 <!-- Breadcrumbs -->
 <nav class="flex items-center gap-2 text-xs text-charcoal-muted">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <a href="{{ route('cart') }}" class="hover:text-primary-800 transition-colors">سلة المشتريات</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">إتمام الطلب</span>
 </nav>

 <!-- Page Title -->
 <div class="pb-2 border-b border-cream">
 <h1 class="text-2xl sm:text-3xl font-black text-charcoal-heading">إتمام الطلب والدفع</h1>
 <p class="text-xs text-charcoal-muted mt-1">أدخل بياناتك لتأكيد طلبك وتوصيله إليك بأسرع وقت في العامرية</p>
 </div>

 @if($errors->any())
 <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
 <div class="font-bold flex items-center gap-1.5">
 <span></span>
 <span>يرجى مراجعة البيانات التالية:</span>
 </div>
 <ul class="list-disc list-inside space-y-0.5 pr-2">
 @foreach($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <form action="{{ route('checkout.place') }}" 
 method="POST" 
 enctype="multipart/form-data" 
 @submit="submitting = true" 
 class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
 @csrf

 <!-- Form Left / Main Column -->
 <div class="lg:col-span-7 space-y-6">
 
 <!-- 1. Customer Information Card -->
 <div class="bg-white rounded-3xl p-6 sm:p-7 border border-cream shadow-sm space-y-4">
 <div class="flex items-center gap-2 border-b border-cream pb-3">
 <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-800 text-xs font-black flex items-center justify-center border border-primary-200">1</span>
 <h2 class="font-black text-sm sm:text-base text-charcoal-heading">بيانات العميل</h2>
 </div>

 <div class="space-y-4 text-xs">
 <!-- Full Name -->
 <div>
 <label for="name" class="font-bold text-charcoal-heading block mb-1.5">
 الاسم بالكامل <span class="text-rose-600">*</span>
 </label>
 <input type="text" 
 id="name" 
 name="name" 
 value="{{ old('name', $user?->name) }}" 
 placeholder="أحمد محمود علي"
 required 
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <!-- Phone & Email Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <div>
 <label for="phone" class="font-bold text-charcoal-heading block mb-1.5">
 رقم الهاتف (واتساب) <span class="text-rose-600">*</span>
 </label>
 <input type="tel" 
 id="phone" 
 name="phone" 
 value="{{ old('phone', $user?->phone) }}" 
 placeholder="01012345678"
 dir="ltr"
 required 
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 <span class="text-[10px] text-charcoal-muted mt-1 block">رقم هاتف مصري متاح عليه واتساب لمتابعة طلبك</span>
 </div>

 <div>
 <label for="email" class="font-bold text-charcoal-heading block mb-1.5">
 البريد الإلكتروني <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <input type="email" 
 id="email" 
 name="email" 
 value="{{ old('email', $user?->email) }}" 
 placeholder="example@mail.com"
 dir="ltr"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>
 </div>
 </div>
 </div>

 <!-- 2. Delivery Method Card -->
 <div class="bg-white rounded-3xl p-6 sm:p-7 border border-cream shadow-sm space-y-4">
 <div class="flex items-center gap-2 border-b border-cream pb-3">
 <span class="w-7 h-7 rounded-full bg-primary-50 text-primary-800 text-xs font-black flex items-center justify-center border border-primary-200">2</span>
 <h2 class="font-black text-sm sm:text-base text-charcoal-heading">طريقة الاستلام</h2>
 </div>

 <!-- Delivery Type Radios -->
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
 <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
 :class="deliveryType === 'delivery' ? 'border-primary-700 bg-primary-50/50' : 'border-cream hover:bg-canvas-base'">
 <input type="radio" 
 name="delivery_type" 
 value="delivery" 
 x-model="deliveryType" 
 class="text-primary-800 focus:ring-primary-600">
 <div class="flex-1">
 <span class="font-bold text-charcoal block"> توصيل للمنزل</span>
 <span class="text-[10px] text-charcoal-muted block">خدمة التوصيل السريع لجميع مناطق العامرية</span>
 </div>
 </label>

 <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
 :class="deliveryType === 'pickup' ? 'border-primary-700 bg-primary-50/50' : 'border-cream hover:bg-canvas-base'">
 <input type="radio" 
 name="delivery_type" 
 value="pickup" 
 x-model="deliveryType" 
 class="text-primary-800 focus:ring-primary-600">
 <div class="flex-1">
 <span class="font-bold text-charcoal block"> استلام من الفرع</span>
 <span class="text-[10px] text-emerald-700 font-semibold block">مجاناً وبدون رسوم توصيل</span>
 </div>
 </label>
 </div>

 <!-- Home Delivery Address Field -->
 <div x-show="deliveryType === 'delivery'" x-transition class="space-y-1.5 text-xs pt-2">
 <label for="address" class="font-bold text-charcoal-heading block">
 عنوان التوصيل بالتفصيل <span class="text-rose-600">*</span>
 </label>

 @if($user && !empty($user->addresses) && count($user->addresses) > 0)
 <div class="flex items-center gap-2 mb-2 overflow-x-auto pb-1 scrollbar-none">
 <span class="text-[10px] text-charcoal-muted shrink-0 font-bold">عناوينك المحفوظة:</span>
 @foreach($user->addresses as $savedAddr)
 <button type="button" 
 onclick="document.getElementById('address').value = {{ json_encode($savedAddr['address'] ?? '') }};"
 class="px-2.5 py-1 rounded-lg bg-canvas-base border border-cream hover:border-primary-500 hover:text-primary-800 text-[11px] font-bold text-charcoal shrink-0 transition-colors cursor-pointer">
 {{ $savedAddr['title'] ?? 'عنوان' }}
 </button>
 @endforeach
 </div>
 @endif

 <textarea id="address" 
 name="address" 
 rows="3" 
 <!-- Branch Selection Field -->
 <div x-show="deliveryType === 'pickup'" x-cloak x-transition class="space-y-3 text-xs pt-2">
 <label for="branch_id" class="font-bold text-gray-900 block">
 اختر فرع الاستلام بالعامرية <span class="text-rose-600">*</span>
 </label>
 
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
 @foreach($branches as $branch)
 <label class="relative p-3.5 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between"
 :class="selectedBranch == {{ $branch->id }} ? 'border-red-600 bg-red-50/50 shadow-sm' : 'border-gray-200 hover:border-orange-300 bg-white'">
 <div class="flex items-start gap-2.5 mb-2">
 <input type="radio" 
 name="branch_id" 
 value="{{ $branch->id }}" 
 x-model="selectedBranch"
 class="text-red-600 focus:ring-red-500 mt-0.5">
 <div>
 <span class="font-black text-xs text-gray-900 block">{{ $branch->name }}</span>
 <span class="text-[10px] text-gray-500 block leading-tight mt-0.5">{{ $branch->address }}</span>
 </div>
 </div>
 <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-[10px] text-gray-500">
 <span>هاتف الفرع: <span dir="ltr" class="font-mono font-bold">{{ $branch->phone ?: '01234567890' }}</span></span>
 <span class="text-emerald-700 font-bold">متاح للاستلام</span>
 </div>
 </label>
 @endforeach
 </div>
 </div>

 </div>

 <!-- 3. Payment Method Card -->
 <div class="bg-white rounded-3xl p-6 sm:p-7 border border-cream shadow-sm space-y-4">
 <div class="flex items-center gap-2 border-b border-cream pb-3">
 <span class="w-7 h-7 rounded-full bg-red-50 text-red-600 text-xs font-black flex items-center justify-center border border-red-200">3</span>
 <h2 class="font-black text-sm sm:text-base text-gray-900">طريقة الدفع</h2>
 </div>

 <!-- Payment Radios -->
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
 <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
 :class="paymentMethod === 'cash' ? 'border-red-600 bg-red-50/50 shadow-sm' : 'border-gray-200 hover:bg-canvas-base'">
 <input type="radio" 
 name="payment_method" 
 value="cash" 
 x-model="paymentMethod" 
 class="text-red-600 focus:ring-red-500">
 <div class="flex-1">
 <span class="font-bold text-gray-900 block">الدفع عند الاستلام (كاش)</span>
 <span class="text-[10px] text-gray-500 block" x-text="deliveryType === 'pickup' ? 'تدفع بالفرع عند الاستلام' : 'تدفع للمندوب عند باب البيت'"></span>
 </div>
 </label>

 <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all"
 :class="paymentMethod === 'instapay' ? 'border-red-600 bg-red-50/50 shadow-sm' : 'border-gray-200 hover:bg-canvas-base'">
 <input type="radio" 
 name="payment_method" 
 value="instapay" 
 x-model="paymentMethod" 
 class="text-red-600 focus:ring-red-500">
 <div class="flex-1">
 <span class="font-bold text-gray-900 block">الدفع الفوري عبر InstaPay / كاش</span>
 <span class="text-[10px] text-gray-500 block">تحويل مباشر برقم الفرع وإرفاق الإيصال</span>
 </div>
 </label>
 </div>

 <!-- InstaPay Details Panel Dynamically Tailored by Selected Branch -->
 <div x-show="paymentMethod === 'instapay'" x-cloak x-transition class="space-y-4 pt-3 border-t border-cream text-xs">
 
 <!-- Account Details Box -->
 <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-orange-200 space-y-2.5">
 <div class="font-black text-amber-950 flex items-center justify-between">
 <div class="flex items-center gap-2">
 <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
 </svg>
 <span>بيانات التحويل الفوري حسب الفرع المختار:</span>
 </div>
 <span class="text-[10px] px-2 py-0.5 rounded-full bg-orange-200 text-orange-900 font-bold" 
 x-text="branchesData[selectedBranch]?.name || 'الفرع الرئيسي'"></span>
 </div>
 
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
 <div class="bg-white p-3 rounded-xl border border-orange-200 shadow-xs">
 <span class="text-[10px] text-gray-500 block">عنوان الدفع اللحظي (InstaPay IPA):</span>
 <span class="font-mono font-black text-red-600 text-xs select-all block mt-0.5">
 {{ settings('instapay_username', 'altawheed@instapay') }}
 </span>
 </div>
 <div class="bg-white p-3 rounded-xl border border-orange-200 shadow-xs">
 <span class="text-[10px] text-gray-500 block">رقم التحويل ومحفظة الفرع:</span>
 <span class="font-mono font-black text-emerald-700 text-sm select-all block mt-0.5" 
 dir="ltr"
 x-text="branchesData[selectedBranch]?.phone || '{{ settings('instapay_number', '01234567890') }}'">
 </span>
 </div>
 </div>
 <div class="text-[10px] text-amber-900 font-medium bg-white/70 p-2 rounded-lg border border-amber-200">
 بعد إتمام التحويل من تطبيق إنستاباي أو محفظتك، يرجى رفع صورة الإيصال بالأسفل لتأكيد الطلب فوراً.
 </div>
 </div>

 <!-- Upload Receipt -->
 <div>
 <label for="payment_proof_image" class="font-bold text-gray-900 block mb-1">
 صورة إيصال التحويل <span class="text-rose-600">*</span>
 </label>
 <input type="file" 
 id="payment_proof_image" 
 name="payment_proof_image" 
 accept="image/*"
 class="w-full bg-canvas-base border border-cream rounded-xl p-2.5 text-xs text-charcoal file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-800 file:text-white hover:file:bg-primary-900 cursor-pointer">
 <span class="text-[10px] text-charcoal-muted mt-1 block">ارفع لقطة شاشة لعملية التحويل الناجحة من تطبيق إنستاباي</span>
 </div>

 <!-- Reference ID -->
 <div>
 <label for="instapay_reference" class="font-bold text-gray-900 block mb-1">
 رقم المرجع أو كود المعاملة <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <input type="text" 
 id="instapay_reference" 
 name="instapay_reference" 
 value="{{ old('instapay_reference') }}" 
 placeholder="مثال: Ref #12345678"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600">
 </div>

 </div>
 </div>

 <!-- 4. Notes Card -->
 <div class="bg-white rounded-3xl p-6 border border-cream shadow-sm space-y-2">
 <label for="notes" class="font-bold text-xs text-charcoal-heading block">
 ملاحظات إضافية لتنفيذ الطلب <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <textarea id="notes" 
 name="notes" 
 rows="2" 
 placeholder="أي تعليمات خاصة للمندوب أو تجهيز الطلب..." 
 class="w-full bg-canvas-base border border-cream rounded-xl p-3 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">{{ old('notes') }}</textarea>
 </div>

 </div>

 <!-- Summary Right / Sidebar Column -->
 <div class="lg:col-span-5 space-y-5">
 
 <div class="bg-white rounded-3xl p-6 border border-cream shadow-sm space-y-4 sticky top-24">
 <h3 class="font-black text-sm text-charcoal-heading border-b border-cream pb-3">
 ملخص الطلب ({{ count($items) }} أصناف)
 </h3>

 <!-- Compact Items List -->
 <div class="max-h-60 overflow-y-auto divide-y divide-cream/60 pl-1">
 @foreach($items as $item)
 <div class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3 text-xs">
 <div class="flex items-center gap-2.5 min-w-0">
 <div class="w-10 h-10 rounded-xl bg-canvas-base border border-cream p-1 shrink-0 flex items-center justify-center">
 @if($item['image'])
 <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain">
 @else
 <span></span>
 @endif
 </div>
 <div class="min-w-0">
 <div class="font-bold text-charcoal truncate">{{ $item['name'] }}</div>
 <div class="text-[10px] text-charcoal-muted">الكمية: {{ $item['quantity'] }}</div>
 </div>
 </div>
 <div class="font-black text-primary-800 tabular-nums shrink-0">
 {{ number_format($item['price_usd'] * $item['quantity'], 2) }} ج.م
 </div>
 </div>
 @endforeach
 </div>

 <!-- Financial Breakdown -->
 <div class="space-y-2.5 text-xs pt-3 border-t border-cream">
 <div class="flex items-center justify-between text-charcoal-muted">
 <span>المجموع الفرعي</span>
 <span class="font-bold text-charcoal tabular-nums">{{ $totals['formatted_subtotal'] }}</span>
 </div>

 @if(!empty($totals['coupon_code']) && ($totals['discount_amount'] ?? 0) > 0)
 <div class="flex items-center justify-between text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200 text-xs font-bold">
 <span>خصم الكوبون ({{ $totals['coupon_code'] }})</span>
 <span class="tabular-nums">- {{ $totals['formatted_discount'] }}</span>
 </div>
 @endif

 <div class="flex items-center justify-between text-charcoal-muted">
 <span>رسوم التوصيل</span>
 <span class="font-bold tabular-nums"
 :class="deliveryType === 'pickup' ? 'text-emerald-600' : 'text-charcoal'"
 x-text="deliveryType === 'pickup' ? 'مجاناً (استلام)' : '{{ $totals['formatted_delivery_fee'] }}'">
 </span>
 </div>

 <div class="border-t border-cream pt-3 flex items-center justify-between text-base font-black text-charcoal-heading">
 <span>الإجمالي النهائي</span>
 <span class="text-xl text-primary-800 tabular-nums"
 x-text="grandTotal.toFixed(2) + ' ج.م'">
 </span>
 </div>
 </div>

 <!-- Place Order Button -->
 <div class="pt-3">
 <button type="submit" 
 :disabled="submitting"
 class="w-full py-3.5 px-4 rounded-2xl bg-gradient-maroon text-white font-black text-sm text-center shadow-lg shadow-primary-900/25 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
 <svg x-show="submitting" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
 </svg>
 <span x-text="submitting ? 'جاري تسجيل طلبك...' : 'تأكيد الطلب الآن'">تأكيد الطلب الآن</span>
 </button>
 </div>

 <div class="text-center text-[10px] text-charcoal-muted pt-1">
 بالضغط على "تأكيد الطلب"، فإنك توافق على شروط وسياسة الخدمة في سوبرماركت التوحيد.
 </div>
 </div>

 </div>
 </form>

 </div>
</div>
@endsection
