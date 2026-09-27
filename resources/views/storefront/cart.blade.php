{{-- resources/views/storefront/cart.blade.php --}}
@extends('layouts.storefront')

@section('title', 'سلة المشتريات | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-8 bg-gradient-to-b from-orange-50/30 via-canvas-base to-canvas-base min-h-screen" 
     x-data="{
         items: {{ json_encode($items) }},
         totals: {{ json_encode($totals) }},
         deliveryType: '{{ $deliveryType }}',
         updating: false,
         
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
             window.tawheedConfirm('حذف المنتج', 'هل تريد بالتأكيد إزالة هذا الصنف من سلة المشتريات؟', () => {
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
                     toastr.success('تم حذف الصنف من السلة');
                 })
                 .catch(() => {
                     this.updating = false;
                     toastr.error('حدث خطأ أثناء الحذف');
                 });
             }, null, { confirmText: 'نعم، احذف', cancelText: 'تراجع', icon: '' });
         },

         clearAll() {
             window.tawheedConfirm('تفريغ السلة', 'هل أنت متأكد من تفريغ سلة المشتريات بالكامل؟', () => {
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
                     toastr.info('تم تفريغ السلة بنجاح');
                 })
                 .catch(() => {
                     this.updating = false;
                     toastr.error('حدث خطأ أثناء تفريغ السلة');
                 });
             }, null, { confirmText: 'تفريغ السلة', cancelText: 'إلغاء', icon: '' });
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
         }
     }">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-bold text-gray-500" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-red-600 transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-gray-900 font-black">سلة المشتريات</span>
        </nav>

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-3 border-b border-orange-200">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-gray-900 flex items-center gap-2">
                    <span>سلة المشتريات</span>
                    <span class="text-base font-normal text-gray-500" x-show="items.length > 0">
                        (<span x-text="items.length"></span> أصناف)
                    </span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">منتجات ألبان وبقالة طازجة من فروع التوحيد بالعامرية</p>
            </div>

            <template x-if="items.length > 0">
                <button type="button" 
                        @click="clearAll()"
                        class="inline-flex items-center gap-1.5 text-xs font-black text-red-600 hover:text-red-700 hover:underline cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>تفريغ السلة</span>
                </button>
            </template>
        </div>

        <!-- Cart Content -->
        <template x-if="items.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Items Table / List -->
                <div class="lg:col-span-8 bg-white rounded-3xl p-5 sm:p-7 border-2 border-orange-100 shadow-sm space-y-4">
                    <div class="divide-y divide-gray-100">
                        <template x-for="item in items" :key="item.key">
                            <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-4">
                                
                                <!-- Item Info -->
                                <div class="flex items-center gap-3.5 flex-1 min-w-0 w-full sm:w-auto">
                                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-orange-50/50 border border-orange-100 p-1.5 flex items-center justify-center shrink-0 overflow-hidden">
                                        <template x-if="item.image">
                                            <img :src="item.image" :alt="item.name" class="w-full h-full object-contain">
                                        </template>
                                        <template x-if="!item.image">
                                            <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <template x-if="item.type === 'bundle'">
                                            <span class="inline-block px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-100 text-amber-900 border border-amber-200 mb-1">
                                                عرض مجمع توفيري 
                                            </span>
                                        </template>

                                        <h3 class="font-black text-sm sm:text-base text-gray-900 truncate" x-text="item.name"></h3>

                                        <!-- Weight / Portion Badge -->
                                        <template x-if="item.portion_label">
                                            <div class="mt-1 flex items-center gap-1.5">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black bg-orange-100 text-orange-900 border border-orange-200" x-text="item.portion_label"></span>
                                            </div>
                                        </template>

                                        <!-- Preparation / Slicing Notes -->
                                        <template x-if="item.notes">
                                            <div class="mt-1 text-[11px] text-gray-600 bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-200 inline-block">
                                                <span class="font-bold text-gray-800">ملاحظات:</span> <span x-text="item.notes"></span>
                                            </div>
                                        </template>
                                        
                                        <div class="text-xs text-gray-500 mt-1">
                                            سعر الطلب: 
                                            <span class="font-black text-red-600 tabular-nums" x-text="item.formatted_price_usd"></span>
                                        </div>

                                        <!-- Limited Stock Alert -->
                                        <template x-if="item.is_limited_stock">
                                            <span class="inline-flex items-center gap-1 text-[11px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-lg border border-amber-200 font-semibold mt-1">
                                                كمية محدودة (أقصى كمية مسموح بها: 1)
                                            </span>
                                        </template>
                                    </div>
                                </div>

                                <!-- Quantity Stepper & Line Total -->
                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
                                    
                                    <!-- Stepper -->
                                    <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50 p-1">
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity - 1)"
                                                :disabled="updating || item.quantity <= 1"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white hover:bg-orange-50 text-gray-900 font-black disabled:opacity-40 transition-colors shadow-xs cursor-pointer">
                                            -
                                        </button>
                                        
                                        <span class="w-9 text-center font-black text-xs text-gray-900 tabular-nums" x-text="item.quantity"></span>
                                        
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity + 1)"
                                                :disabled="updating || (item.is_limited_stock && item.quantity >= 1) || (item.quantity >= item.available_stock)"
                                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-white hover:bg-orange-50 text-gray-900 font-black disabled:opacity-40 transition-colors shadow-xs cursor-pointer">
                                            +
                                        </button>
                                    </div>

                                    <!-- Line Total -->
                                    <div class="text-left min-w-[90px]">
                                        <span class="text-sm sm:text-base font-black text-red-600 tabular-nums" 
                                              x-text="(item.price_usd * item.quantity).toFixed(2) + ' ج.م'"></span>
                                    </div>

                                    <!-- Delete Button -->
                                    <button type="button" 
                                            @click="removeItem(item.key)"
                                            class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors cursor-pointer"
                                            title="حذف">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>

                    <!-- Return to shopping button -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <a href="{{ route('site.products') }}" class="text-xs font-bold text-red-600 hover:underline inline-flex items-center gap-1">
                            <span>← متابعة التسوق وإضافة منتجات أخرى</span>
                        </a>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-4 space-y-5">
                    
                    <!-- Delivery Option Choice -->
                    <div class="bg-white rounded-3xl p-5 border-2 border-orange-100 shadow-sm space-y-3">
                        <h3 class="font-black text-xs text-gray-900">طريقة الاستلام المفضلة</h3>
                        <div class="grid grid-cols-2 gap-2 text-xs font-bold">
                            <button type="button" 
                                    @click="changeDeliveryType('delivery')"
                                    :class="deliveryType === 'delivery' ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-sm font-black' : 'bg-gray-50 text-gray-700 hover:bg-orange-50 border border-gray-200'"
                                    class="py-2.5 px-3 rounded-2xl flex flex-col items-center gap-1 transition-all cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>توصيل للمنزل</span>
                            </button>

                            <button type="button" 
                                    @click="changeDeliveryType('pickup')"
                                    :class="deliveryType === 'pickup' ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-sm font-black' : 'bg-gray-50 text-gray-700 hover:bg-orange-50 border border-gray-200'"
                                    class="py-2.5 px-3 rounded-2xl flex flex-col items-center gap-1 transition-all cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>استلام من الفرع</span>
                            </button>
                        </div>

                        <!-- Pickup Notice -->
                        <div x-show="deliveryType === 'pickup'" class="text-[11px] text-emerald-800 bg-emerald-50 p-2.5 rounded-xl border border-emerald-200 font-bold">
                            الاستلام من أي فرع بالعامرية مجاني تماماً وبدون أي رسوم توصيل!
                        </div>
                    </div>

                    <!-- Financial Summary Box -->
                    <div class="bg-white rounded-3xl p-6 border-2 border-orange-100 shadow-sm space-y-4">
                        <h3 class="font-black text-sm text-gray-900 border-b border-gray-100 pb-3">ملخص الحساب</h3>

                        <div class="space-y-2.5 text-xs">
                            <div class="flex items-center justify-between text-gray-600">
                                <span>المجموع الفرعي</span>
                                <span class="font-bold text-gray-900 tabular-nums" x-text="totals.formatted_subtotal"></span>
                            </div>

                            <div class="flex items-center justify-between text-gray-600">
                                <span>رسوم التوصيل</span>
                                <span class="font-bold tabular-nums" 
                                      :class="totals.delivery_fee == 0 ? 'text-emerald-600' : 'text-gray-900'"
                                      x-text="totals.formatted_delivery_fee"></span>
                            </div>

                            <div class="border-t border-gray-100 pt-3 flex items-center justify-between text-base font-black text-gray-900">
                                <span>الإجمالي النهائي</span>
                                <span class="text-xl text-red-600 tabular-nums" x-text="totals.formatted_total"></span>
                            </div>
                        </div>

                        <!-- Proceed to Checkout Button -->
                        <div class="pt-2">
                            <a :href="'{{ route('checkout.index') }}?delivery_type=' + deliveryType" 
                               class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-sm text-center shadow-md shadow-orange-500/20 hover:scale-[1.01] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <span>المتابعة لإتمام الطلب</span>
                                <span class="rotate-180">←</span>
                            </a>
                        </div>

                        <div class="pt-2 flex items-center justify-center gap-2 text-[11px] text-gray-500 text-center">
                            <span>طلب آمن ومحمي 100% • دفع عند الاستلام أو إنستاباي</span>
                        </div>
                    </div>

                </div>

            </div>
        </template>

        <!-- Empty Cart State -->
        <template x-if="items.length === 0">
            <div class="bg-white rounded-3xl p-12 sm:p-16 border border-orange-100 text-center max-w-lg mx-auto shadow-sm space-y-5 my-8">
                <div class="w-20 h-20 rounded-3xl bg-orange-50 text-red-600 flex items-center justify-center mx-auto border border-orange-200">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>

                <div class="space-y-1.5">
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900">سلة المشتريات فارغة</h2>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed">
                        لم تقم بإضافة أي منتجات إلى سلتك حتى الآن. استكشف أجود منتجات الألبان والأجبان البلدية والسلع الغذائية الآن!
                    </p>
                </div>

                <div class="pt-3">
                    <a href="{{ route('site.products') }}" 
                       class="inline-flex items-center gap-2 py-3 px-6 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-xs sm:text-sm transition-transform hover:scale-105 shadow-md">
                        <span>تسوق الآن</span>
                        <span class="rotate-180">←</span>
                    </a>
                </div>
            </div>
        </template>

    </div>
</div>
@endsection
