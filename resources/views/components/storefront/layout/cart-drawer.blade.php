{{-- resources/views/components/storefront/layout/cart-drawer.blade.php --}}
<div x-data="{
        open: false,
        items: [],
        totals: { formatted_subtotal: '0.00 ج.م', formatted_total: '0.00 ج.م', total: 0, subtotal: 0, count: 0 },
        loading: false,
        freeDeliveryThreshold: {{ (float) settings('free_delivery_threshold', 300) }},

        get progressPercent() {
            const sub = parseFloat(this.totals.subtotal) || 0;
            return Math.min(100, Math.round((sub / this.freeDeliveryThreshold) * 100));
        },

        get remainingForFreeDelivery() {
            const sub = parseFloat(this.totals.subtotal) || 0;
            return Math.max(0, this.freeDeliveryThreshold - sub);
        },

        init() {
            window.addEventListener('cart-updated', () => {
                this.fetchCartData(true);
            });
            window.addEventListener('open-cart-drawer', () => {
                this.open = true;
                this.fetchCartData(false);
            });
        },

        fetchCartData(autoOpen = false) {
            this.loading = true;
            fetch('{{ route('cart.payload') }}')
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    this.items = data.items || [];
                    this.totals = data.totals || {};
                    if (autoOpen && this.items.length > 0) {
                        this.open = true;
                    }
                })
                .catch(() => {
                    this.loading = false;
                });
        },

        updateQty(key, newQty) {
            if (newQty < 1) return;
            fetch('{{ route('update.cart.quantity') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ key: key, quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.fetchCartData(false);
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                } else {
                    toastr.error(data.message || 'تعذر تحديث الكمية');
                }
            });
        },

        removeItem(key) {
            fetch('{{ route('removefromcart') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ key: key })
            })
            .then(res => res.json())
            .then(data => {
                this.fetchCartData(false);
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                toastr.success('تم حذف الصنف من السلة');
            });
        }
    }"
    @keydown.escape.window="open = false"
    x-cloak
    class="relative z-[9999]">

    <!-- Backdrop overlay -->
    <div x-show="open" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-gray-950/60 backdrop-blur-xs"></div>

    <!-- Drawer Panel (Slides from left/right) -->
    <div x-show="open" 
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-md w-full bg-white shadow-2xl flex flex-col z-50 text-right"
         dir="rtl">
        
        <!-- Header -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-red-700 via-red-600 to-orange-500 text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center font-black text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-sm sm:text-base">سلة طلباتك السريعة</h3>
                    <p class="text-[10px] text-amber-200">ألبان وسوبرماركت التوحيد • العامرية</p>
                </div>
            </div>

            <button type="button" 
                    @click="open = false" 
                    class="p-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Dynamic Free Delivery Progress Bar -->
        <div class="p-3.5 bg-gradient-to-br from-amber-50 to-orange-50 border-b border-orange-100 text-xs space-y-1.5">
            <div class="flex items-center justify-between font-bold">
                <span class="text-gray-900" x-show="remainingForFreeDelivery > 0">
                    أضف بقيمة <strong class="text-red-600 tabular-nums" x-text="remainingForFreeDelivery.toFixed(1) + ' ج.م'"></strong> لتحصل على <strong>توصيل مجاني</strong>
                </span>
                <span class="text-emerald-700 font-black flex items-center gap-1" x-show="remainingForFreeDelivery <= 0">
                    <span>مبروك! حصلت على توصيل مجاني بالعامرية</span>
                </span>
                <span class="text-[10px] text-gray-500 font-bold tabular-nums" x-text="progressPercent + '%'"></span>
            </div>

            <div class="w-full h-2 rounded-full bg-gray-200 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-orange-500 to-emerald-500 transition-all duration-500 rounded-full"
                     :style="'width: ' + progressPercent + '%'"></div>
            </div>
        </div>

        <!-- Items Scroll Area -->
        <div class="flex-1 overflow-y-auto p-4 divide-y divide-gray-100">
            <template x-if="items.length > 0">
                <div>
                    <template x-for="item in items" :key="item.key">
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-start gap-3">
                            <!-- Image -->
                            <div class="w-14 h-14 rounded-2xl bg-orange-50/50 border border-orange-100 p-1 shrink-0 overflow-hidden flex items-center justify-center">
                                <template x-if="item.image">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-contain">
                                </template>
                                <template x-if="!item.image">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </template>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0 space-y-1">
                                <h4 class="font-black text-xs text-gray-900 truncate" x-text="item.name"></h4>
                                
                                <!-- Portion label -->
                                <template x-if="item.portion_label">
                                    <div class="inline-block px-1.5 py-0.2 rounded text-[10px] font-black bg-orange-100 text-orange-900">
                                        <span x-text="item.portion_label"></span>
                                    </div>
                                </template>

                                <!-- Notes -->
                                <template x-if="item.notes">
                                    <p class="text-[10px] text-gray-500 truncate" x-text="'ملاحظة: ' + item.notes"></p>
                                </template>

                                <div class="flex items-center justify-between pt-1">
                                    <!-- Stepper -->
                                    <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50 p-0.5 text-xs">
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity - 1)" 
                                                class="w-5 h-5 flex items-center justify-center bg-white rounded text-gray-800 font-black shadow-2xs hover:bg-orange-50">
                                            -
                                        </button>
                                        <span class="w-6 text-center font-black tabular-nums text-xs" x-text="item.quantity"></span>
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity + 1)" 
                                                class="w-5 h-5 flex items-center justify-center bg-white rounded text-gray-800 font-black shadow-2xs hover:bg-orange-50">
                                            +
                                        </button>
                                    </div>

                                    <!-- Price -->
                                    <span class="font-black text-xs text-red-600 tabular-nums" x-text="(item.price_usd * item.quantity).toFixed(2) + ' ج.م'"></span>
                                </div>
                            </div>

                            <!-- Delete -->
                            <button type="button" 
                                    @click="removeItem(item.key)"
                                    class="text-gray-400 hover:text-red-600 p-1 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty Drawer State -->
            <template x-if="items.length === 0">
                <div class="py-16 text-center space-y-3">
                    <div class="w-16 h-16 rounded-3xl bg-orange-50 text-red-600 flex items-center justify-center mx-auto border border-orange-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h4 class="font-black text-sm text-gray-900">سلة التسوق فارغة</h4>
                    <p class="text-xs text-gray-500 max-w-xs mx-auto">تصفح منتجات الألبان والأجبان الطازجة وأضف طلباتك الآن!</p>
                </div>
            </template>
        </div>

        <!-- Footer Checkout Actions -->
        <div class="p-4 sm:p-5 bg-gray-50 border-t border-gray-200 space-y-3" x-show="items.length > 0">
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-600 font-bold">المجموع الفرعي:</span>
                <span class="font-black text-base text-gray-900 tabular-nums" x-text="totals.formatted_subtotal"></span>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('cart') }}" 
                   @click="open = false"
                   class="py-3 px-4 rounded-xl border-2 border-orange-300 hover:bg-orange-50 text-gray-900 font-black text-xs text-center transition-all">
                    عرض السلة الكاملة
                </a>

                <a href="{{ route('checkout.index') }}" 
                   @click="open = false"
                   class="py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-xs text-center shadow-md transition-all flex items-center justify-center gap-1.5">
                    <span>إتمام الطلب الآن</span>
                    <span class="rotate-180">←</span>
                </a>
            </div>
        </div>

    </div>
</div>
