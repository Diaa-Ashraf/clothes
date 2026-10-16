{{-- resources/views/storefront/cart.blade.php --}}
@extends('layouts.storefront')

@section('title', 'سلة المشتريات | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-10 bg-white min-h-screen" 
     x-data="{
         items: {{ json_encode($items) }},
         totals: {{ json_encode($totals) }},
         deliveryType: '{{ $deliveryType }}',
         updating: false,
         freeDeliveryThreshold: {{ (float) settings('free_delivery_threshold', 500) }},

         get progressPercent() {
             const sub = parseFloat(this.totals.subtotal) || 0;
             return Math.min(100, Math.round((sub / this.freeDeliveryThreshold) * 100));
         },

         get remainingForFreeDelivery() {
             const sub = parseFloat(this.totals.subtotal) || 0;
             return Math.max(0, this.freeDeliveryThreshold - sub);
         },

         orderCartViaWhatsApp() {
             const phone = '{{ preg_replace("/[^0-9]/", "", settings("store_whatsapp", "01000000000")) }}';
             const itemsList = this.items.map((item, idx) => {
                 const vText = item.portion_label ? ' (' + item.portion_label + ')' : '';
                 return (idx + 1) + '. ' + item.name + vText + ' × ' + item.quantity + ' بسعر: ' + item.total_usd + ' ج.م';
             }).join('\n');

             const msg = 'مرحباً {{ addslashes(settings('site_name', 'أزياء لافيستا')) }} 🌿\n' +
                         'أود إتمام طلبيتي من حقيبة التسوق كالتالي:\n\n' +
                         itemsList + '\n\n' +
                         '• المجموع الفرعي: ' + this.totals.formatted_subtotal + '\n' +
                         '• رسوم التوصيل: ' + this.totals.formatted_delivery_fee + '\n' +
                         '• الإجمالي النهائي: ' + this.totals.formatted_total + '\n\n' +
                         'بيانات التوصيل:\n' +
                         '• الاسم:\n' +
                         '• الهاتف:\n' +
                         '• المحافظة والعنوان:';

             window.open('https://wa.me/' + phone + '?text=' + encodeURIComponent(msg), '_blank');
         },
         
         updateQty(key, newQty) {
             if (newQty < 1) return;
             this.updating = true;
             fetch('{{ route('update.cart.quantity') }}', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({ key: key, quantity: newQty, delivery_type: this.deliveryType })
             })
             .then(res => res.json())
             .then(data => {
                 this.updating = false;
                 if (data.success) {
                     const idx = this.items.findIndex(i => i.key === key);
                     if (idx !== -1) {
                         this.items[idx].quantity = newQty;
                         this.items[idx].total_usd = this.items[idx].price_usd * newQty;
                     }
                     this.fetchTotals();
                     window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                     toastr.success(data.message || 'تم تحديث الكمية بنجاح');
                 } else {
                     toastr.error(data.message || 'تعذر تحديث الكمية');
                 }
             })
             .catch(() => {
                 this.updating = false;
                 toastr.error('حدث خطأ في الاتصال');
             });
         },

         removeItem(key) {
             window.tawheedConfirm('إزالة القطعة', 'هل تريد بالتأكيد إزالة هذه القطعة من حقيبة التسوق؟', () => {
                 this.updating = true;
                 fetch('{{ route('removefromcart') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({ key: key, delivery_type: this.deliveryType })
                 })
                 .then(res => res.json())
                 .then(data => {
                     this.updating = false;
                     this.items = this.items.filter(i => i.key !== key);
                     this.totals = data.totals;
                     window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                     toastr.success('تمت إزالة القطعة من الحقيبة');
                 })
                 .catch(() => {
                     this.updating = false;
                     toastr.error('حدث خطأ أثناء الحذف');
                 });
             }, null, { confirmText: 'نعم، إزالة', cancelText: 'تراجع', icon: '' });
         },

         clearAll() {
             window.tawheedConfirm('تفريغ الحقيبة', 'هل أنت متأكد من تفريغ حقيبة التسوق بالكامل؟', () => {
                 this.updating = true;
                 fetch('{{ route('clearcart') }}', {
                     method: 'POST',
                     headers: {
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json'
                     }
                 })
                 .then(res => res.json())
                 .then(data => {
                     this.updating = false;
                     this.items = [];
                     this.totals = data.totals;
                     window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: 0 } }));
                     toastr.info('تم تفريغ حقيبة التسوق');
                 })
                 .catch(() => {
                     this.updating = false;
                     toastr.error('حدث خطأ أثناء تفريغ السلة');
                 });
             }, null, { confirmText: 'تفريغ الحقيبة', cancelText: 'إلغاء', icon: '' });
         },

         fetchTotals() {
             fetch('{{ route('cart.payload') }}?delivery_type=' + this.deliveryType)
                 .then(res => res.json())
                 .then(data => {
                     this.totals = data.totals;
                 });
         },

         changeDeliveryType(type) {
             this.deliveryType = type;
             this.fetchTotals();
         },

         couponCodeInput: '',
         couponApplying: false,

         applyCoupon() {
             if (!this.couponCodeInput.trim()) {
                 toastr.warning('يرجى إدخال رمز الكوبون');
                 return;
             }
             this.couponApplying = true;
             fetch('{{ route('cart.apply-coupon') }}', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({ coupon_code: this.couponCodeInput.trim() })
             })
             .then(res => res.json())
             .then(data => {
                 this.couponApplying = false;
                 if (data.success) {
                     this.totals = data.totals;
                     this.couponCodeInput = '';
                     toastr.success(data.message || 'تم تطبيق كود الخصم بنجاح');
                 } else {
                     toastr.error(data.message || 'كود الخصم غير صالح');
                 }
             })
             .catch(() => {
                 this.couponApplying = false;
                 toastr.error('حدث خطأ أثناء تطبيق الكوبون');
             });
         },

         removeCoupon() {
             this.couponApplying = true;
             fetch('{{ route('cart.remove-coupon') }}', {
                 method: 'POST',
                 headers: {
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                     'Accept': 'application/json'
                 }
             })
             .then(res => res.json())
             .then(data => {
                 this.couponApplying = false;
                 if (data.success) {
                     this.totals = data.totals;
                     toastr.info(data.message || 'تم حذف الكوبون');
                 }
             })
             .catch(() => {
                 this.couponApplying = false;
                 toastr.error('حدث خطأ أثناء إزالة الكوبون');
             });
         }
     }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" dir="rtl">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">حقيبة التسوق</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-[#EADFC8]">
            <div>
                <h1 class="text-2xl sm:text-3xl font-serif font-black text-[#2B2118] flex items-center gap-2">
                    <span>حقيبة التسوق</span>
                    <span class="text-base font-normal text-[#8F7B63]" x-show="items.length > 0">
                        (<span x-text="items.length"></span> قطع)
                    </span>
                </h1>
                <p class="text-xs text-[#8F7B63] mt-1">قطع أزياء راقية ومختارة من {{ settings('site_name', 'أزياء لافيستا') }}</p>
            </div>

            <template x-if="items.length > 0">
                <button type="button" 
                        @click="clearAll()"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-[#A9552F] hover:text-[#382416] hover:underline cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>تفريغ الحقيبة</span>
                </button>
            </template>
        </div>

        <!-- Cart Content -->
        <template x-if="items.length > 0">
            <div class="space-y-6">
                <!-- 🚚 Free Delivery Dynamic Progress Banner -->
                <div class="p-4 sm:p-5 bg-[#FDFBF7] rounded-3xl border border-[#EADFC8] shadow-xs space-y-2.5">
                    <div class="flex items-center justify-between text-xs sm:text-sm font-medium flex-wrap gap-2">
                        <div class="flex items-center gap-2" x-show="remainingForFreeDelivery > 0">
                            <div class="w-8 h-8 rounded-xl bg-[#AC9653]/15 text-[#AC9653] flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                            <span class="text-[#2B2118]">
                                أضف بقيمة <strong class="text-[#A9552F] font-bold tabular-nums" x-text="remainingForFreeDelivery.toFixed(1) + ' ج.م'"></strong> إلى حقيبتك لتحصل على <strong class="text-[#AC9653]">شحن مجاني</strong> لكافة محافظات مصر
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-emerald-800 font-bold" x-show="remainingForFreeDelivery <= 0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span>تهانينا! طلبيتك مؤهلة للشحن المجاني السريع لكافة المحافظات</span>
                        </div>

                        <div class="flex items-center gap-1.5 mr-auto sm:mr-0">
                            <span class="text-xs font-bold text-[#8F7B63] tabular-nums" x-text="progressPercent + '%'"></span>
                        </div>
                    </div>

                    <!-- Progress Bar Track -->
                    <div class="w-full h-2.5 rounded-full bg-[#EADFC8] overflow-hidden p-0.5">
                        <div class="h-full rounded-full transition-all duration-500"
                             :class="progressPercent >= 100 ? 'bg-emerald-600' : 'bg-gradient-to-l from-[#AC9653] to-[#CBA367]'"
                             :style="'width: ' + progressPercent + '%'"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Items Table / List -->
                <div class="lg:col-span-8 bg-[#FDFBF7] rounded-3xl p-5 sm:p-7 border border-[#EADFC8] shadow-xs space-y-4">
                    <div class="divide-y divide-[#EADFC8]">
                        <template x-for="item in items" :key="item.key">
                            <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-4">
                                
                                <!-- Item Info -->
                                <div class="flex items-center gap-3.5 flex-1 min-w-0 w-full sm:w-auto">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[#F3E9D2]/40 border border-[#EADFC8] p-1.5 flex items-center justify-center shrink-0 overflow-hidden">
                                        <template x-if="item.image">
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover rounded-xl">
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="w-8 h-8 rounded-xl bg-[#F3E9D2] text-[#6B5642] flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex-1 min-w-0 text-right">
                                        <template x-if="item.type === 'bundle'">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] mb-1">
                                                طقم أزياء مجمع
                                            </span>
                                        </template>

                                        <h3 class="font-serif font-bold text-sm sm:text-base text-[#2B2118] truncate" x-text="item.name"></h3>

                                        <!-- Variant / Portion Badge -->
                                        <template x-if="item.portion_label">
                                            <div class="mt-1 flex items-center gap-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-[#F3E9D2] text-[#382416] border border-[#EADFC8]" x-text="item.portion_label"></span>
                                            </div>
                                        </template>

                                        <!-- Customer Notes -->
                                        <template x-if="item.notes">
                                            <div class="mt-1 text-[11px] text-[#6B5642] bg-[#F3E9D2]/30 px-2.5 py-1 rounded-lg border border-[#EADFC8] inline-block">
                                                <span class="font-bold text-[#2B2118]">ملاحظات:</span> <span x-text="item.notes"></span>
                                            </div>
                                        </template>
                                        
                                        <div class="text-xs text-[#8F7B63] mt-1">
                                            سعر القطعة: 
                                            <span class="font-bold text-[#382416] tabular-nums" x-text="item.formatted_price_usd"></span>
                                        </div>

                                        <!-- Limited Stock Alert -->
                                        <template x-if="item.is_limited_stock">
                                            <span class="inline-flex items-center gap-1 text-[11px] text-[#A9552F] bg-[#A9552F]/10 px-2 py-0.5 rounded-lg border border-[#A9552F]/20 font-semibold mt-1">
                                                كمية محدودة بالمخزون
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Quantity Stepper & Line Total -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-[#EADFC8]">
                                    
                                    <!-- Stepper -->
                                    <div class="flex items-center border border-[#EADFC8] rounded-xl bg-[#F3E9D2]/30 p-1">
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity - 1)"
                                                :disabled="updating || item.quantity <= 1"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white hover:bg-[#F3E9D2] text-[#2B2118] font-bold disabled:opacity-40 transition-colors shadow-2xs cursor-pointer border border-[#EADFC8]">
                                            -
                                        </button>
                                        
                                        <span class="w-9 text-center font-bold text-xs text-[#2B2118] tabular-nums" x-text="item.quantity"></span>
                                        
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity + 1)"
                                                :disabled="updating || (item.available_stock && item.quantity >= item.available_stock)"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white hover:bg-[#F3E9D2] text-[#2B2118] font-bold disabled:opacity-40 transition-colors shadow-2xs cursor-pointer border border-[#EADFC8]">
                                            +
                                        </button>
                                    </div>

                                    <!-- Line Total -->
                                    <div class="text-left min-w-[90px]">
                                        <span class="text-sm sm:text-base font-black text-[#382416] tabular-nums" 
                                              x-text="(item.price_usd * item.quantity).toFixed(2) + ' ج.م'"></span>
                                    </div>

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            @click="removeItem(item.key)"
                                            class="p-2 text-[#8F7B63] hover:text-[#A9552F] rounded-lg hover:bg-[#F3E9D2] transition-colors cursor-pointer"
                                            title="حذف القطعة">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>

                    <!-- Return to shopping button -->
                    <div class="pt-4 border-t border-[#EADFC8] flex items-center justify-between">
                        <a href="{{ route('site.products') }}" class="text-xs font-bold text-[#382416] hover:text-[#A9552F] inline-flex items-center gap-1">
                            <span>← متابعة التسوق وتصفح تشكيلات أخرى</span>
                        </a>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-4 space-y-5">
                    
                    <!-- Delivery Option Choice -->
                    <div class="bg-[#FDFBF7] rounded-3xl p-5 border border-[#EADFC8] shadow-xs space-y-3">
                        <h3 class="font-serif font-bold text-xs text-[#2B2118]">طريقة الاستلام المفضلة</h3>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                            <button type="button" 
                                    @click="changeDeliveryType('delivery')"
                                    :class="deliveryType === 'delivery' ? 'bg-[#382416] text-[#FBF6EA] shadow-xs font-bold' : 'bg-[#F3E9D2]/40 text-[#6B5642] hover:bg-[#F3E9D2] border border-[#EADFC8]'"
                                    class="py-2.5 px-3 rounded-2xl flex flex-col items-center gap-1 transition-all cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>شحن وتوصيل</span>
                            </button>

                            <button type="button" 
                                    @click="changeDeliveryType('pickup')"
                                    :class="deliveryType === 'pickup' ? 'bg-[#382416] text-[#FBF6EA] shadow-xs font-bold' : 'bg-[#F3E9D2]/40 text-[#6B5642] hover:bg-[#F3E9D2] border border-[#EADFC8]'"
                                    class="py-2.5 px-3 rounded-2xl flex flex-col items-center gap-1 transition-all cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>استلام من البوتيك</span>
                            </button>
                        </div>

                        <!-- Pickup Notice -->
                        <div x-show="deliveryType === 'pickup'" class="text-[11px] text-[#382416] bg-[#F3E9D2] p-2.5 rounded-xl border border-[#CDB78F] font-semibold">
                            الاستلام من أي من فروعنا مجاني تماماً مع إمكانية تجربة القياس في البوتيك.
                        </div>
                    </div>

                    <!-- Coupon Card -->
                    <div class="bg-[#FDFBF7] rounded-3xl p-5 border border-[#EADFC8] shadow-xs space-y-3">
                        <h3 class="font-serif font-bold text-xs text-[#2B2118] flex items-center justify-between">
                            <span>كوبون الخصم</span>
                            <span class="text-[10px] text-[#A9552F] bg-[#A9552F]/10 px-2 py-0.5 rounded-full font-bold">تخفيض فوري</span>
                        </h3>

                        <!-- Coupon Form when NOT applied -->
                        <template x-if="!totals.has_coupon">
                            <div class="flex items-center gap-2">
                                <input type="text" 
                                       x-model="couponCodeInput" 
                                       placeholder="أدخل كود الكوبون..." 
                                       class="flex-1 uppercase font-bold text-xs bg-[#F3E9D2]/30 border border-[#EADFC8] rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#382416] transition-colors"
                                       @keydown.enter.prevent="applyCoupon()">
                                <button type="button" 
                                        @click="applyCoupon()"
                                        :disabled="couponApplying"
                                        class="px-4 py-2.5 bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold rounded-xl transition-all disabled:opacity-50 cursor-pointer shrink-0">
                                    <span x-show="!couponApplying">تطبيق</span>
                                    <span x-show="couponApplying">...</span>
                                </button>
                            </div>
                        </template>

                        <!-- Applied Coupon State -->
                        <template x-if="totals.has_coupon">
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-[#F3E9D2] border border-[#CDB78F]">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-[#382416] text-[#FBF6EA] flex items-center justify-center text-xs font-bold">✓</span>
                                    <div>
                                        <div class="text-xs font-bold text-[#2B2118] flex items-center gap-1.5">
                                            <span>كود:</span>
                                            <span class="tracking-wider uppercase" x-text="totals.coupon_code"></span>
                                        </div>
                                        <div class="text-[10px] text-[#A9552F] font-bold">
                                            خصم: <span x-text="totals.formatted_discount"></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" 
                                        @click="removeCoupon()" 
                                        class="text-xs text-[#A9552F] hover:text-[#382416] font-bold p-1 cursor-pointer"
                                        title="حذف الكوبون">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Financial Summary Box -->
                    <div class="bg-[#FDFBF7] rounded-3xl p-6 border border-[#EADFC8] shadow-xs space-y-4">
                        <h3 class="font-serif font-bold text-sm text-[#2B2118] border-b border-[#EADFC8] pb-3">ملخص الحساب</h3>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between text-[#6B5642]">
                                <span>المجموع الفرعي</span>
                                <span class="font-bold text-[#2B2118] tabular-nums" x-text="totals.formatted_subtotal"></span>
                            </div>

                            <!-- Discount Row -->
                            <template x-if="totals.has_coupon && totals.discount_amount > 0">
                                <div class="flex items-center justify-between text-[#A9552F] font-bold bg-[#A9552F]/10 p-2 rounded-xl border border-[#A9552F]/20">
                                    <span>خصم الكوبون (<span x-text="totals.coupon_code"></span>)</span>
                                    <span class="tabular-nums">- <span x-text="totals.formatted_discount"></span></span>
                                </div>
                            </template>

                            <div class="flex items-center justify-between text-[#6B5642]">
                                <span>رسوم التوصيل</span>
                                <span class="font-bold tabular-nums" 
                                      :class="totals.delivery_fee == 0 ? 'text-[#382416]' : 'text-[#2B2118]'"
                                      x-text="totals.formatted_delivery_fee"></span>
                            </div>

                            <div class="border-t border-[#EADFC8] pt-3 flex items-center justify-between text-base font-bold text-[#2B2118]">
                                <span>الإجمالي النهائي</span>
                                <span class="text-xl font-black text-[#382416] tabular-nums" x-text="totals.formatted_total"></span>
                            </div>
                        </div>

                        <!-- Proceed to Checkout Button -->
                        <div class="pt-2 space-y-2">
                            <a :href="'{{ route('checkout.index') }}?delivery_type=' + deliveryType" 
                               class="w-full py-4 px-4 bg-[#CBA367] hover:bg-[#b88f55] text-white font-bold text-sm text-center shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer rounded-2xl">
                                <span>المتابعة لإتمام الطلب الفاخر</span>
                                <span>←</span>
                            </a>

                            <!-- 1-Click WhatsApp Order Cart -->
                            <button type="button" 
                                    @click="orderCartViaWhatsApp()"
                                    class="w-full py-3.5 px-4 bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#075E54] border border-[#25D366]/40 text-xs font-bold rounded-2xl transition-all flex items-center justify-center gap-2 cursor-pointer shadow-2xs">
                                <svg class="w-4 h-4 text-[#25D366] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                                </svg>
                                <span>طلب محتويات الحقيبة مباشرة عبر واتساب</span>
                            </button>
                        </div>

                        <div class="pt-2 flex items-center justify-center gap-2 text-[11px] text-[#8F7B63] text-center">
                            <span>معاينة وقياس عند الاستلام • دفع كاش أو إلكتروني</span>
                        </div>
                    </div>

                </div>

            </div>
            </div>
        </template>

        <!-- Empty Cart State -->
        <template x-if="items.length === 0">
            <div class="bg-[#FDFBF7] rounded-3xl p-12 sm:p-16 border border-[#EADFC8] text-center max-w-lg mx-auto shadow-xs space-y-5 my-8">
                <div class="w-20 h-20 rounded-3xl bg-[#F3E9D2] text-[#382416] flex items-center justify-center mx-auto border border-[#EADFC8]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <h2 class="text-xl sm:text-2xl font-serif font-black text-[#2B2118]">حقيبة التسوق فارغة</h2>
                    <p class="text-xs sm:text-sm text-[#8F7B63] leading-relaxed">
                        لم تقم بإضافة أي قطع أزياء إلى حقيبتك حتى الآن. استكشف أحدث صيحات الموضة والتشكيلات الراقية الآن!
                    </p>
                </div>

                <div class="pt-3">
                    <a href="{{ route('site.products') }}" 
                       class="inline-flex items-center gap-2 py-3 px-7 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs sm:text-sm transition-all shadow-md">
                        <span>تصفح الكتالوج</span>
                        <span class="rotate-180">←</span>
                    </a>
                </div>
            </div>
        </template>

    </div>
</div>
@endsection
