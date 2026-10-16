{{-- resources/views/storefront/checkout.blade.php --}}
@extends('layouts.storefront')

@section('title', 'إتمام الطلب والدفع الآمن | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-8 sm:py-12 bg-white min-h-screen"
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
        },

        submitCheckout(e) {
            if (this.submitting) return;
            this.submitting = true;

            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (res.ok && data.success) {
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } }));
                    toastr.success(data.message || 'تم تسجيل طلبك بنجاح!');
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 400);
                } else {
                    this.submitting = false;
                    if (data.errors) {
                        const allErrors = Object.values(data.errors).flat();
                        toastr.error(allErrors[0] || 'يرجى مراجعة وتصحيح البيانات');
                    } else {
                        toastr.error(data.message || 'حدث خطأ أثناء تسجيل الطلب، يرجى المحاولة مجدداً');
                    }
                }
            })
            .catch(() => {
                this.submitting = false;
                toastr.error('حدث خطأ في الاتصال، يرجى المحاولة مرة أخرى.');
            });
        }
     }">
    
    <div class="container mx-auto px-3 sm:px-4 md:px-6 max-w-7xl space-y-6" dir="rtl">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#8F7B63] flex-wrap">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('cart') }}" class="hover:text-[#382416] transition-colors">حقيبة التسوق</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">إتمام الطلب والدفع</span>
        </nav>

        <!-- Page Header -->
        <div class="pb-3 border-b border-[#EADFC8] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-serif font-black text-[#2B2118]">إتمام الطلب والشحن</h1>
                <p class="text-xs sm:text-sm text-[#8F7B63] mt-1">{{ settings('site_name', 'أزياء لافيستا') }} • تجربة تسوق راقية وشحن سريع لباب البيت</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-[#382416] bg-[#F3E9D2] border border-[#CDB78F] px-3.5 py-1.5 rounded-xl self-start sm:self-auto shadow-2xs">
                <span class="w-2 h-2 rounded-full bg-[#A9552F]"></span>
                <span>معاينة وقياس القطع متاح عند الاستلام</span>
            </div>
        </div>

        <form action="{{ route('checkout.place') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              @submit.prevent="submitCheckout($event)" 
              class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            @csrf

            <!-- Form Left / Main Column (7 cols) -->
            <div class="lg:col-span-7 space-y-5">
                
                <!-- 1. Customer Information Card -->
                <div class="bg-[#FDFBF7] rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-[#EADFC8] pb-3">
                        <span class="w-7 h-7 rounded-xl bg-[#382416] text-[#FBF6EA] text-xs font-bold flex items-center justify-center shrink-0">1</span>
                        <div>
                            <h2 class="font-serif font-bold text-sm sm:text-base text-[#2B2118]">بيانات العميل والتواصل</h2>
                            <p class="text-[11px] text-[#8F7B63]">الاسم ورقم الهاتف لتأكيد المقاس وتوصيل طلبك</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 text-xs">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="font-bold text-[#2B2118] block mb-1">
                                الاسم بالكامل <span class="text-[#A9552F]">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user?->name) }}" 
                                   placeholder="أحمد محمد"
                                   required 
                                   class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] focus:border-[#382416] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#2B2118] outline-none transition-all">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="font-bold text-[#2B2118] block mb-1">
                                رقم الهاتف (واتساب) <span class="text-[#A9552F]">*</span>
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $user?->phone) }}" 
                                   placeholder="01012345678"
                                   dir="ltr"
                                   required 
                                   class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] focus:border-[#382416] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#2B2118] text-right outline-none transition-all">
                            <span class="text-[10px] text-[#8F7B63] mt-1 block">رقم للتواصل وتأكيد موعد التسليم عبر واتساب</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Boutique / Branch Selection Card -->
                <div class="bg-[#FDFBF7] rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs space-y-4">
                    <div class="flex items-center justify-between border-b border-[#EADFC8] pb-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-xl bg-[#382416] text-[#FBF6EA] text-xs font-bold flex items-center justify-center shrink-0">2</span>
                            <div>
                                <h2 class="font-serif font-bold text-sm sm:text-base text-[#2B2118]">اختيار البوتيك / الفرع</h2>
                                <p class="text-[11px] text-[#8F7B63]">حدد الفرع الأقرب إليك للتجهيز أو الاستلام المباشر</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-[#F3E9D2] text-[#382416] border border-[#CDB78F]">
                            فروع {{ settings('site_name', 'أزياء لافيستا') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        @foreach($branches as $branch)
                        <label class="relative p-3.5 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between select-none group"
                               :class="selectedBranch == {{ $branch->id }} 
                                    ? 'border-[#382416] bg-[#F3E9D2] shadow-xs' 
                                    : 'border-[#EADFC8] hover:border-[#CDB78F] bg-white'">
                            
                            <div class="flex items-start gap-2.5 mb-2">
                                <input type="radio" 
                                       name="branch_id" 
                                       value="{{ $branch->id }}" 
                                       x-model="selectedBranch"
                                       class="text-[#382416] focus:ring-[#382416] mt-0.5 shrink-0">
                                <div class="min-w-0">
                                    <span class="font-bold text-xs sm:text-sm text-[#2B2118] block group-hover:text-[#A9552F] transition-colors">
                                        {{ $branch->name }}
                                    </span>
                                    <span class="text-[10px] text-[#8F7B63] block leading-relaxed mt-0.5 line-clamp-2">
                                        📍 {{ $branch->address }}
                                    </span>
                                </div>
                            </div>

                            <div class="pt-2 border-t border-[#EADFC8] flex items-center justify-between text-[10px]">
                                <span class="text-[#6B5642] font-semibold" dir="ltr">📞 {{ $branch->phone ?: '01000000000' }}</span>
                                <span class="font-bold" :class="selectedBranch == {{ $branch->id }} ? 'text-[#382416]' : 'text-[#8F7B63]'">
                                    <span x-show="selectedBranch == {{ $branch->id }}">✓ المختار</span>
                                    <span x-show="selectedBranch != {{ $branch->id }}">متاح</span>
                                </span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Delivery Method & Address Card -->
                <div class="bg-[#FDFBF7] rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-[#EADFC8] pb-3">
                        <span class="w-7 h-7 rounded-xl bg-[#382416] text-[#FBF6EA] text-xs font-bold flex items-center justify-center shrink-0">3</span>
                        <div>
                            <h2 class="font-serif font-bold text-sm sm:text-base text-[#2B2118]">طريقة الاستلام وعنوان التوصيل</h2>
                            <p class="text-[11px] text-[#8F7B63]">اختر الشحن لباب البيت أو الاستلام بنفسك من البوتيك</p>
                        </div>
                    </div>

                    <!-- Delivery Type Radios -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all select-none"
                               :class="deliveryType === 'delivery' 
                                    ? 'border-[#382416] bg-[#F3E9D2] shadow-xs' 
                                    : 'border-[#EADFC8] hover:border-[#CDB78F] bg-white'">
                            <input type="radio" 
                                   name="delivery_type" 
                                   value="delivery" 
                                   x-model="deliveryType" 
                                   class="text-[#382416] focus:ring-[#382416]">
                            <div class="flex-1">
                                <span class="font-bold text-xs sm:text-sm text-[#2B2118] block">🛵 شحن وتوصيل للمنزل</span>
                                <span class="text-[10px] text-[#8F7B63] block mt-0.5">توصيل سريع لباب البيت مع حق المعاينة</span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all select-none"
                               :class="deliveryType === 'pickup' 
                                    ? 'border-[#382416] bg-[#F3E9D2] shadow-xs' 
                                    : 'border-[#EADFC8] hover:border-[#CDB78F] bg-white'">
                            <input type="radio" 
                                   name="delivery_type" 
                                   value="pickup" 
                                   x-model="deliveryType" 
                                   class="text-[#382416] focus:ring-[#382416]">
                            <div class="flex-1">
                                <span class="font-bold text-xs sm:text-sm text-[#2B2118] block">🏪 استلام من البوتيك المختار</span>
                                <span class="text-[10px] text-[#382416] font-bold block mt-0.5">تجهيز فوري ومجاني (0 ج.م رسوم)</span>
                            </div>
                        </label>
                    </div>

                    <!-- Home Delivery Address Field -->
                    <div x-show="deliveryType === 'delivery'" x-transition class="space-y-2 text-xs pt-1">
                        <label for="address" class="font-bold text-[#2B2118] block">
                            عنوان التوصيل بالتفصيل <span class="text-[#A9552F]">*</span>
                        </label>

                        @if($user && !empty($user->addresses) && count($user->addresses) > 0)
                        <div class="flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
                            <span class="text-[10px] text-[#8F7B63] shrink-0 font-bold">عناوينك المسجلة:</span>
                            @foreach($user->addresses as $savedAddr)
                            <button type="button" 
                                    onclick="document.getElementById('address').value = {{ json_encode($savedAddr['address'] ?? '') }};"
                                    class="px-2.5 py-1 rounded-lg bg-[#F3E9D2]/40 border border-[#EADFC8] hover:border-[#382416] hover:text-[#382416] text-[11px] font-bold text-[#6B5642] shrink-0 transition-colors cursor-pointer">
                                {{ $savedAddr['title'] ?? 'عنوان' }}
                            </button>
                            @endforeach
                        </div>
                        @endif

                        <textarea id="address" 
                                  name="address" 
                                  rows="3" 
                                  placeholder="اسم المدينة، الحي، الشارع، رقم العمارة، رقم الشقة، علامة مميزة..."
                                  class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] focus:border-[#382416] focus:bg-white rounded-xl p-3 text-xs sm:text-sm text-[#2B2118] outline-none transition-all leading-relaxed">{{ old('address', $user?->address) }}</textarea>
                    </div>

                    <!-- Pickup Notice -->
                    <div x-show="deliveryType === 'pickup'" x-cloak x-transition class="p-3 bg-[#F3E9D2] border border-[#CDB78F] rounded-xl text-xs space-y-1">
                        <div class="font-bold text-[#382416] flex items-center gap-1.5">
                            <span>✓</span>
                            <span>سيتم تجهيز طلبك في <strong x-text="branchesData[selectedBranch]?.name || 'البوتيك المختار'"></strong> وتجربة القياس متاحة.</span>
                        </div>
                        <p class="text-[11px] text-[#6B5642]" x-text="'عنوان الفرع: ' + (branchesData[selectedBranch]?.address || '')"></p>
                    </div>
                </div>

                <!-- 4. Payment Method Card -->
                <div class="bg-[#FDFBF7] rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs space-y-4">
                    <div class="flex items-center gap-2.5 border-b border-[#EADFC8] pb-3">
                        <span class="w-7 h-7 rounded-full bg-[#382416] text-[#FBF6EA] text-xs font-bold flex items-center justify-center shrink-0">4</span>
                        <div>
                            <h2 class="font-serif font-bold text-sm sm:text-base text-[#2B2118]">طريقة الدفع</h2>
                            <p class="text-[11px] text-[#8F7B63]">اختر الدفع نقداً عند الاستلام أو بالتحويل اللحظي إنستاباي</p>
                        </div>
                    </div>

                    <!-- Payment Radios -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all select-none"
                               :class="paymentMethod === 'cash' 
                                    ? 'border-[#382416] bg-[#F3E9D2] shadow-xs' 
                                    : 'border-[#EADFC8] hover:border-[#CDB78F] bg-white'">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="cash" 
                                   x-model="paymentMethod" 
                                   class="text-[#382416] focus:ring-[#382416]">
                            <div class="flex-1">
                                <span class="font-bold text-xs sm:text-sm text-[#2B2118] block">💵 الدفع عند الاستلام (كاش)</span>
                                <span class="text-[10px] text-[#8F7B63] block mt-0.5" 
                                      x-text="deliveryType === 'pickup' ? 'تدفع في البوتيك عند الاستلام' : 'تدفع لمندوب الشحن بعد المعاينة'"></span>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-3 p-3.5 rounded-2xl border cursor-pointer transition-all select-none"
                               :class="paymentMethod === 'instapay' 
                                    ? 'border-[#382416] bg-[#F3E9D2] shadow-xs' 
                                    : 'border-[#EADFC8] hover:border-[#CDB78F] bg-white'">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="instapay" 
                                   x-model="paymentMethod" 
                                   class="text-[#382416] focus:ring-[#382416]">
                            <div class="flex-1">
                                <span class="font-bold text-xs sm:text-sm text-[#2B2118] block">📱 التحويل عبر InstaPay / المحفظة</span>
                                <span class="text-[10px] text-[#8F7B63] block mt-0.5">تحويل لحظي مباشر للفرع المختار</span>
                            </div>
                        </label>
                    </div>

                    <!-- InstaPay Details Panel -->
                    <div x-show="paymentMethod === 'instapay'" x-cloak x-transition class="space-y-4 pt-3 border-t border-[#EADFC8] text-xs">
                        
                        <!-- Account Details Box -->
                        <div class="p-4 rounded-2xl bg-[#F3E9D2]/70 border border-[#CDB78F] space-y-2.5">
                            <div class="font-bold text-[#2B2118] flex items-center justify-between flex-wrap gap-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#A9552F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span>بيانات التحويل المباشر:</span>
                                </div>
                                <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-[#382416] text-[#FBF6EA] font-bold" 
                                      x-text="branchesData[selectedBranch]?.name || 'البوتيك المختار'"></span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs pt-1">
                                <div class="bg-white p-3 rounded-xl border border-[#EADFC8] shadow-2xs">
                                    <span class="text-[10px] text-[#8F7B63] block">عنوان الدفع اللحظي (IPA):</span>
                                    <span class="font-mono font-bold text-[#382416] text-xs sm:text-sm select-all block mt-0.5">
                                        {{ settings('instapay_username', 'altawheed@instapay') }}
                                    </span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-[#EADFC8] shadow-2xs">
                                    <span class="text-[10px] text-[#8F7B63] block">رقم تحويل المحفظة / الفرع:</span>
                                    <span class="font-mono font-bold text-[#382416] text-xs sm:text-sm select-all block mt-0.5" 
                                          dir="ltr"
                                          x-text="branchesData[selectedBranch]?.phone || '{{ settings('instapay_number', '01000000000') }}'">
                                    </span>
                                </div>
                            </div>
                            <div class="text-[10px] text-[#6B5642] font-medium bg-white/70 p-2.5 rounded-xl border border-[#EADFC8] leading-relaxed">
                                بعد إتمام التحويل عبر تطبيق InstaPay أو محفظتك، يرجى رفع صورة إيصال التحويل بالأسفل لتأكيد وتجهيز طلبك على الفور.
                            </div>
                        </div>

                        <!-- Upload Receipt -->
                        <div>
                            <label for="payment_proof_image" class="font-bold text-[#2B2118] block mb-1">
                                صورة إيصال التحويل <span class="text-[#A9552F]">*</span>
                            </label>
                            <input type="file" 
                                   id="payment_proof_image" 
                                   name="payment_proof_image" 
                                   accept="image/*"
                                   class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] rounded-xl p-2.5 text-xs text-[#2B2118] file:ml-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#382416] file:text-[#FBF6EA] hover:file:bg-[#2B2118] cursor-pointer">
                            <span class="text-[10px] text-[#8F7B63] mt-1 block">ارفع لقطة شاشة لعملية التحويل الناجحة</span>
                        </div>

                        <!-- Reference ID -->
                        <div>
                            <label for="instapay_reference" class="font-bold text-[#2B2118] block mb-1">
                                رقم المرجع أو كود المعاملة <span class="text-[#8F7B63] font-normal">(اختياري)</span>
                            </label>
                            <input type="text" 
                                   id="instapay_reference" 
                                   name="instapay_reference" 
                                   value="{{ old('instapay_reference') }}" 
                                   placeholder="مثال: Ref #12345678"
                                   class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] focus:border-[#382416] focus:bg-white rounded-xl px-3.5 py-2.5 text-xs sm:text-sm text-[#2B2118] outline-none transition-all">
                        </div>

                    </div>
                </div>

                <!-- 5. Notes Card -->
                <div class="bg-[#FDFBF7] rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs space-y-2">
                    <label for="notes" class="font-bold text-xs sm:text-sm text-[#2B2118] block">
                        ملاحظات إضافية لتجهيز وتوصيل الطلب <span class="text-[#8F7B63] font-normal">(اختياري)</span>
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="2" 
                              placeholder="أي تعليمات خاصة للمندوب، مواعيد التسليم المفضلة، أو تفاصيل خاصة بالمقاسات..." 
                              class="w-full bg-[#F3E9D2]/30 border border-[#EADFC8] focus:border-[#382416] focus:bg-white rounded-xl p-3 text-xs sm:text-sm text-[#2B2118] outline-none transition-all leading-relaxed">{{ old('notes') }}</textarea>
                </div>

            </div>

            <!-- Summary Right / Sidebar Column (5 cols) -->
            <div class="lg:col-span-5 space-y-5">
                
                <div class="bg-[#FDFBF7] rounded-2xl sm:rounded-3xl p-4 sm:p-6 border border-[#EADFC8] shadow-xs space-y-4 lg:sticky lg:top-24">
                    <div class="flex items-center justify-between border-b border-[#EADFC8] pb-3">
                        <h3 class="font-serif font-bold text-sm sm:text-base text-[#2B2118]">
                            ملخص حقيبة التسوق
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F]">
                            {{ count($items) }} قطع
                        </span>
                    </div>

                    <!-- Compact Items List -->
                    <div class="max-h-64 overflow-y-auto divide-y divide-[#EADFC8] pl-1 no-scrollbar">
                        @foreach($items as $item)
                        <div class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-[#F3E9D2]/30 border border-[#EADFC8] p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover rounded-lg">
                                    @else
                                        <span class="text-lg">🛍️</span>
                                    @endif
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <div class="font-bold text-[#2B2118] truncate">{{ $item['name'] }}</div>
                                    @if(!empty($item['portion_label']))
                                        <div class="text-[10px] text-[#A9552F] font-bold">{{ $item['portion_label'] }}</div>
                                    @endif
                                    <div class="text-[10px] text-[#8F7B63]">الكمية: <strong class="text-[#2B2118]">{{ $item['quantity'] }}</strong></div>
                                </div>
                            </div>
                            <div class="font-bold text-[#382416] text-xs sm:text-sm tabular-nums shrink-0">
                                {{ number_format($item['price_usd'] * $item['quantity'], 2) }} ج.م
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Financial Breakdown -->
                    <div class="space-y-2.5 text-xs pt-3 border-t border-[#EADFC8]">
                        <div class="flex items-center justify-between text-[#8F7B63]">
                            <span>المجموع الفرعي</span>
                            <span class="font-bold text-[#2B2118] tabular-nums">{{ $totals['formatted_subtotal'] }}</span>
                        </div>

                        @if(!empty($totals['coupon_code']) && ($totals['discount_amount'] ?? 0) > 0)
                        <div class="flex items-center justify-between text-[#A9552F] bg-[#A9552F]/10 px-2.5 py-1.5 rounded-xl border border-[#A9552F]/20 text-xs font-bold">
                            <span>خصم الكوبون ({{ $totals['coupon_code'] }})</span>
                            <span class="tabular-nums">- {{ $totals['formatted_discount'] }}</span>
                        </div>
                        @endif

                        <div class="flex items-center justify-between text-[#8F7B63]">
                            <span>رسوم التوصيل</span>
                            <span class="font-bold tabular-nums"
                                  :class="deliveryType === 'pickup' ? 'text-[#382416]' : 'text-[#2B2118]'"
                                  x-text="deliveryType === 'pickup' ? 'مجاناً (استلام من البوتيك)' : '{{ $totals['formatted_delivery_fee'] }}'">
                            </span>
                        </div>

                        <div class="border-t border-[#EADFC8] pt-3 flex items-center justify-between text-sm sm:text-base font-bold text-[#2B2118]">
                            <span>الإجمالي النهائي:</span>
                            <span class="text-lg sm:text-xl font-black text-[#AC9653] tabular-nums"
                                  x-text="grandTotal.toFixed(2) + ' ر.س'">
                            </span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                :disabled="submitting"
                                class="w-full py-4 px-6 bg-[#CBA367] hover:bg-[#b88f55] text-white font-bold text-sm text-center shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                            <svg x-show="submitting" x-cloak class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span x-text="submitting ? 'جاري تسجيل وتأكيد طلبك الفاخر...' : 'تأكيد الطلب الفاخر الآن'">تأكيد الطلب الفاخر الآن</span>
                        </button>
                    </div>

                    <div class="text-center text-[10px] text-[#8F7B63] pt-1 leading-relaxed">
                        بالضغط على "تأكيد الطلب"، يتم إرسال طلبك مباشرة للمتابعة معك والتجهيز.
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
