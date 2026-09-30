{{-- resources/views/storefront/checkout.blade.php --}}
@extends('layouts.storefront')

@section('title', 'إتمام الطلب والدفع | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-6 sm:py-10 bg-canvas-base min-h-screen"
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
    
    <div class="container mx-auto px-3 sm:px-4 md:px-6 max-w-7xl space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-bold text-charcoal-muted flex-wrap">
            <a href="{{ route('site.home') }}" class="hover:text-red-600 transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('cart') }}" class="hover:text-red-600 transition-colors">سلة المشتريات</a>
            <span>/</span>
            <span class="text-charcoal-heading font-black">إتمام الطلب والدفع</span>
        </nav>

        <!-- Page Header -->
        <div class="pb-3 border-b border-cream flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-charcoal-heading">إتمام الطلب والدفع</h1>
                <p class="text-xs sm:text-sm text-charcoal-muted mt-1">توصيل سريع لجميع مناطق وقرى العامرية والإسكندرية</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-xl self-start sm:self-auto">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>فروعنا مفتوحة للتوصيل والاستلام حتى 2:00 صباحاً</span>
            </div>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border-2 border-rose-200 text-rose-800 text-xs space-y-1 shadow-xs">
            <div class="font-black text-sm flex items-center gap-1.5">
                <span>⚠️</span>
                <span>يرجى مراجعة وتصحيح البيانات التالية:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pr-2 font-medium">
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
              class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            @csrf

            <!-- Form Left / Main Column (7 cols) -->
            <div class="lg:col-span-7 space-y-5">
                
                <!-- 1. Customer Information Card -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-cream shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-cream pb-3">
                        <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 text-xs font-black flex items-center justify-center border border-red-200 shrink-0">1</span>
                        <div>
                            <h2 class="font-black text-sm sm:text-base text-charcoal-heading">بيانات العميل والتواصل</h2>
                            <p class="text-[11px] text-charcoal-muted">بيانات استلام الأوردر والتواصل عبر واتساب</p>
                        </div>
                    </div>

                    <div class="space-y-3.5 text-xs">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="font-bold text-charcoal-heading block mb-1">
                                الاسم بالكامل <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user?->name) }}" 
                                   placeholder="أحمد محمود علي"
                                   required 
                                   class="w-full bg-canvas-base border border-cream focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-charcoal outline-none transition-all">
                        </div>

                        <!-- Phone & Email Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label for="phone" class="font-bold text-charcoal-heading block mb-1">
                                    رقم الهاتف (واتساب) <span class="text-red-600">*</span>
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user?->phone) }}" 
                                       placeholder="01012345678"
                                       dir="ltr"
                                       required 
                                       class="w-full bg-canvas-base border border-cream focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-charcoal text-right outline-none transition-all">
                                <span class="text-[10px] text-charcoal-muted mt-1 block">رقم للتواصل ومتابعة حالة الأوردر</span>
                            </div>

                            <div>
                                <label for="email" class="font-bold text-charcoal-heading block mb-1">
                                    البريد الإلكتروني <span class="text-charcoal-muted font-normal">(اختياري)</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user?->email) }}" 
                                       placeholder="example@mail.com"
                                       dir="ltr"
                                       class="w-full bg-canvas-base border border-cream focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-charcoal text-right outline-none transition-all">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Branch Selection Card (Dedicated & Always Visible) -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-cream shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-cream pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 text-xs font-black flex items-center justify-center border border-red-200 shrink-0">2</span>
                            <div>
                                <h2 class="font-black text-sm sm:text-base text-charcoal-heading">اختيار الفرع بالعامرية</h2>
                                <p class="text-[11px] text-charcoal-muted">حدد الفرع الأقرب إليك للتجهيز أو التوصيل منه</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-lg bg-orange-100 text-orange-900 border border-orange-200">
                            4 فروع بالعامرية
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach($branches as $branch)
                        <label class="relative p-3.5 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between select-none group"
                               :class="selectedBranch == {{ $branch->id }} 
                                    ? 'border-red-600 bg-red-50/70 shadow-sm ring-2 ring-red-500/20' 
                                    : 'border-cream hover:border-orange-300 bg-white hover:bg-orange-50/30'">
                            
                            <div class="flex items-start gap-2.5 mb-2">
                                <input type="radio" 
                                       name="branch_id" 
                                       value="{{ $branch->id }}" 
                                       x-model="selectedBranch"
                                       class="text-red-600 focus:ring-red-500 mt-0.5 shrink-0">
                                <div class="min-w-0">
                                    <span class="font-black text-xs sm:text-sm text-charcoal-heading block group-hover:text-red-700 transition-colors">
                                        {{ $branch->name }}
                                    </span>
                                    <span class="text-[10px] text-charcoal-muted block leading-relaxed mt-0.5 line-clamp-2">
                                        📍 {{ $branch->address }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-cream/70 flex items-center justify-between text-[10px]">
                                <span class="text-charcoal font-bold" dir="ltr">📞 {{ $branch->phone ?: '01234567890' }}</span>
                                <span class="font-black" :class="selectedBranch == {{ $branch->id }} ? 'text-red-700' : 'text-emerald-700'">
                                    <span x-show="selectedBranch == {{ $branch->id }}">✓ المختار</span>
                                    <span x-show="selectedBranch != {{ $branch->id }}">متاح</span>
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Delivery Method & Address Card -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-cream shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-cream pb-3">
                        <span class="w-7 h-7 rounded-xl bg-red-50 text-red-600 text-xs font-black flex items-center justify-center border border-red-200 shrink-0">3</span>
                        <div>
                            <h2 class="font-black text-sm sm:text-base text-charcoal-heading">طريقة الاستلام وعنوان التوصيل</h2>
                            <p class="text-[11px] text-charcoal-muted">اختر توصيل لباب البيت أو الاستلام بنفسك من الفرع</p>
                        </div>
                    </div>

                    <!-- Delivery Type Radios -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="deliveryType === 'delivery' 
                                    ? 'border-red-600 bg-red-50/70 shadow-sm ring-2 ring-red-500/20' 
                                    : 'border-cream hover:border-orange-300 bg-white'">
                            <input type="radio" 
                                   name="delivery_type" 
                                   value="delivery" 
                                   x-model="deliveryType" 
                                   class="text-red-600 focus:ring-red-500">
                            <div class="flex-1">
                                <span class="font-black text-xs sm:text-sm text-charcoal-heading block">🛵 توصيل سريع للمنزل</span>
                                <span class="text-[10px] text-charcoal-muted block mt-0.5">توصيل فورى لباب البيت لجميع مناطق العامرية</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="deliveryType === 'pickup' 
                                    ? 'border-red-600 bg-red-50/70 shadow-sm ring-2 ring-red-500/20' 
                                    : 'border-cream hover:border-orange-300 bg-white'">
                            <input type="radio" 
                                   name="delivery_type" 
                                   value="pickup" 
                                   x-model="deliveryType" 
                                   class="text-red-600 focus:ring-red-500">
                            <div class="flex-1">
                                <span class="font-black text-xs sm:text-sm text-charcoal-heading block">🏪 استلام من الفرع المختار</span>
                                <span class="text-[10px] text-emerald-700 font-bold block mt-0.5">تجهيز فوري ومجاناً (0 ج.م رسوم)</span>
                            </div>
                        </label>
                    </div>

                    <!-- Home Delivery Address Field (Visible when deliveryType === 'delivery') -->
                    <div x-show="deliveryType === 'delivery'" x-transition class="space-y-2 text-xs pt-1">
                        <label for="address" class="font-bold text-charcoal-heading block">
                            عنوان التوصيل بالتفصيل <span class="text-red-600">*</span>
                        </label>

                        @if($user && !empty($user->addresses) && count($user->addresses) > 0)
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                            <span class="text-[10px] text-charcoal-muted shrink-0 font-bold">عناوينك المسجلة:</span>
                            @foreach($user->addresses as $savedAddr)
                            <button type="button" 
                                    onclick="document.getElementById('address').value = {{ json_encode($savedAddr['address'] ?? '') }};"
                                    class="px-2.5 py-1 rounded-lg bg-canvas-base border border-cream hover:border-red-500 hover:text-red-700 text-[11px] font-bold text-charcoal shrink-0 transition-colors cursor-pointer">
                                {{ $savedAddr['title'] ?? 'عنوان' }}
                            </button>
                            @endforeach
                        </div>
                        @endif

                        <textarea id="address" 
                                  name="address" 
                                  rows="3" 
                                  placeholder="اسم المنطقة، الشارع، رقم العمارة، رقم الشقة، علامة مميزة (مثال: بجوار مسجد...)"
                                  class="w-full bg-canvas-base border border-cream focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl p-3 text-xs sm:text-sm text-charcoal outline-none transition-all leading-relaxed">{{ old('address', $user?->address) }}</textarea>
                    </div>

                    <!-- Pickup Notice (Visible when deliveryType === 'pickup') -->
                    <div x-show="deliveryType === 'pickup'" x-cloak x-transition class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-xl text-xs space-y-1">
                        <div class="font-black text-emerald-800 flex items-center gap-1.5">
                            <span>✓</span>
                            <span>سيتم تجهيز طلبك في <strong x-text="branchesData[selectedBranch]?.name || 'الفرع المختار'"></strong> للاستلام فور وصولك.</span>
                        </div>
                        <p class="text-[11px] text-emerald-700" x-text="'عنوان الفرع: ' + (branchesData[selectedBranch]?.address || '')"></p>
                    </div>
                </div>

                <!-- 4. Payment Method Card -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-cream shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-cream pb-3">
                        <span class="w-7 h-7 rounded-full bg-red-50 text-red-600 text-xs font-black flex items-center justify-center border border-red-200 shrink-0">4</span>
                        <div>
                            <h2 class="font-black text-sm sm:text-base text-charcoal-heading">طريقة الدفع</h2>
                            <p class="text-[11px] text-charcoal-muted">اختر الدفع نقداً كاش أو بالتحويل اللحظي إنستاباي</p>
                        </div>
                    </div>

                    <!-- Payment Radios -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="paymentMethod === 'cash' 
                                    ? 'border-red-600 bg-red-50/70 shadow-sm ring-2 ring-red-500/20' 
                                    : 'border-cream hover:border-orange-300 bg-white'">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="cash" 
                                   x-model="paymentMethod" 
                                   class="text-red-600 focus:ring-red-500">
                            <div class="flex-1">
                                <span class="font-black text-xs sm:text-sm text-charcoal-heading block">💵 الدفع عند الاستلام (كاش)</span>
                                <span class="text-[10px] text-charcoal-muted block mt-0.5" 
                                      x-text="deliveryType === 'pickup' ? 'تدفع في الفرع عند استلام الأوردر' : 'تدفع للمندوب عند باب البيت'"></span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition-all select-none"
                               :class="paymentMethod === 'instapay' 
                                    ? 'border-red-600 bg-red-50/70 shadow-sm ring-2 ring-red-500/20' 
                                    : 'border-cream hover:border-orange-300 bg-white'">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="instapay" 
                                   x-model="paymentMethod" 
                                   class="text-red-600 focus:ring-red-500">
                            <div class="flex-1">
                                <span class="font-black text-xs sm:text-sm text-charcoal-heading block">📱 الدفع عبر InstaPay / المحفظة</span>
                                <span class="text-[10px] text-charcoal-muted block mt-0.5">تحويل فوري برقم الفرع المختار</span>
                            </div>
                        </label>
                    </div>

                    <!-- InstaPay Details Panel (Dynamically Tailored by Selected Branch) -->
                    <div x-show="paymentMethod === 'instapay'" x-cloak x-transition class="space-y-4 pt-3 border-t border-cream text-xs">
                        
                        <!-- Account Details Box -->
                        <div class="p-4 rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-orange-200 space-y-2.5">
                            <div class="font-black text-amber-950 flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>بيانات التحويل المباشر للفرع المختار:</span>
                                </div>
                                <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-orange-200 text-orange-900 font-bold" 
                                      x-text="branchesData[selectedBranch]?.name || 'الفرع المختار'"></span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
                                <div class="bg-white p-3 rounded-xl border border-orange-200 shadow-2xs">
                                    <span class="text-[10px] text-gray-500 block">عنوان الدفع اللحظي (IPA):</span>
                                    <span class="font-mono font-black text-red-600 text-xs sm:text-sm select-all block mt-0.5">
                                        {{ settings('instapay_username', 'altawheed@instapay') }}
                                    </span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-orange-200 shadow-2xs">
                                    <span class="text-[10px] text-gray-500 block">رقم تحويل ومحفظة الفرع:</span>
                                    <span class="font-mono font-black text-emerald-700 text-xs sm:text-sm select-all block mt-0.5" 
                                          dir="ltr"
                                          x-text="branchesData[selectedBranch]?.phone || '{{ settings('instapay_number', '01234567890') }}'">
                                    </span>
                                </div>
                            </div>
                            <div class="text-[10px] text-amber-900 font-medium bg-white/80 p-2.5 rounded-xl border border-amber-200 leading-relaxed">
                                بعد إتمام التحويل عبر تطبيق InstaPay أو محفظتك الإلكترونية، يرجى رفع صورة إيصال التحويل بالأسفل لتأكيد وتنفيذ الأوردر في الحال.
                            </div>
                        </div>

                        <!-- Upload Receipt -->
                        <div>
                            <label for="payment_proof_image" class="font-bold text-charcoal-heading block mb-1">
                                صورة إيصال التحويل <span class="text-red-600">*</span>
                            </label>
                            <input type="file" 
                                   id="payment_proof_image" 
                                   name="payment_proof_image" 
                                   accept="image/*"
                                   class="w-full bg-canvas-base border border-cream rounded-xl p-2.5 text-xs text-charcoal file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-700 cursor-pointer">
                            <span class="text-[10px] text-charcoal-muted mt-1 block">ارفع لقطة شاشة لعملية التحويل الناجحة</span>
                        </div>

                        <!-- Reference ID -->
                        <div>
                            <label for="instapay_reference" class="font-bold text-charcoal-heading block mb-1">
                                رقم المرجع أو كود المعاملة <span class="text-charcoal-muted font-normal">(اختياري)</span>
                            </label>
                            <input type="text" 
                                   id="instapay_reference" 
                                   name="instapay_reference" 
                                   value="{{ old('instapay_reference') }}" 
                                   placeholder="مثال: Ref #12345678"
                                   class="w-full bg-canvas-base border border-cream focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-charcoal outline-none transition-all">
                        </div>

                    </div>
                </div>

                <!-- 5. Notes Card -->
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-cream shadow-xs space-y-2">
                    <label for="notes" class="font-bold text-xs sm:text-sm text-charcoal-heading block">
                        ملاحظات إضافية لتجهيز وتوصيل الطلب <span class="text-charcoal-muted font-normal">(اختياري)</span>
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="2" 
                              placeholder="أي تعليمات خاصة للمندوب، طريقة التقطيع للأجبان، أو مواعيد التوصيل المفضلة..." 
                              class="w-full bg-canvas-base border border-cream focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl p-3 text-xs sm:text-sm text-charcoal outline-none transition-all leading-relaxed">{{ old('notes') }}</textarea>
                </div>

            </div>

            <!-- Summary Right / Sidebar Column (5 cols) -->
            <div class="lg:col-span-5 space-y-5">
                
                <div class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-cream shadow-xs space-y-4 lg:sticky lg:top-24">
                    <div class="flex items-center justify-between border-b border-cream pb-3">
                        <h3 class="font-black text-sm sm:text-base text-charcoal-heading">
                            ملخص الطلب
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-red-50 text-red-600 border border-red-200">
                            {{ count($items) }} أصناف
                        </span>
                    </div>

                    <!-- Compact Items List -->
                    <div class="max-h-64 overflow-y-auto divide-y divide-cream/60 pl-1 admin-scrollbar">
                        @foreach($items as $item)
                        <div class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-canvas-base border border-cream p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="text-lg">🛍️</span>
                                    @endif
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <div class="font-black text-charcoal-heading truncate">{{ $item['name'] }}</div>
                                    @if(!empty($item['portion_label']))
                                        <div class="text-[10px] text-orange-800 font-bold">{{ $item['portion_label'] }}</div>
                                    @endif
                                    <div class="text-[10px] text-charcoal-muted">الكمية: <strong class="text-charcoal">{{ $item['quantity'] }}</strong></div>
                                </div>
                            </div>
                            <div class="font-black text-red-600 text-xs sm:text-sm tabular-nums shrink-0">
                                {{ number_format($item['price_usd'] * $item['quantity'], 2) }} ج.م
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Financial Breakdown -->
                    <div class="space-y-2.5 text-xs pt-3 border-t border-cream">
                        <div class="flex items-center justify-between text-charcoal-muted">
                            <span class="font-bold">المجموع الفرعي</span>
                            <span class="font-bold text-charcoal tabular-nums">{{ $totals['formatted_subtotal'] }}</span>
                        </div>

                        @if(!empty($totals['coupon_code']) && ($totals['discount_amount'] ?? 0) > 0)
                        <div class="flex items-center justify-between text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-xl border border-emerald-200 text-xs font-bold">
                            <span>خصم الكوبون ({{ $totals['coupon_code'] }})</span>
                            <span class="tabular-nums">- {{ $totals['formatted_discount'] }}</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-charcoal-muted">
                            <span class="font-bold">رسوم التوصيل</span>
                            <span class="font-black tabular-nums"
                                  :class="deliveryType === 'pickup' ? 'text-emerald-600' : 'text-charcoal'"
                                  x-text="deliveryType === 'pickup' ? 'مجاناً (استلام من الفرع)' : '{{ $totals['formatted_delivery_fee'] }}'">
                            </span>
                        </div>

                        <div class="border-t border-cream pt-3 flex items-center justify-between text-sm sm:text-base font-black text-charcoal-heading">
                            <span>الإجمالي النهائي:</span>
                            <span class="text-lg sm:text-xl text-red-600 tabular-nums"
                                  x-text="grandTotal.toFixed(2) + ' ج.م'">
                            </span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                :disabled="submitting"
                                class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-sm text-center shadow-lg shadow-red-600/25 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60">
                            <svg x-show="submitting" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span x-text="submitting ? 'جاري تسجيل طلبك...' : 'تأكيد الطلب الآن 🛍️'">تأكيد الطلب الآن 🛍️</span>
                        </button>
                    </div>

                    <div class="text-center text-[10px] text-charcoal-muted pt-1 leading-relaxed">
                        بالضغط على "تأكيد الطلب"، يتم إرسال طلبك مباشرة لقسم التجهيز في الفرع ومتابعته معك عبر واتساب.
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
