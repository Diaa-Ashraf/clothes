{{-- resources/views/components/storefront/layout/cart-drawer.blade.php --}}
<div x-data="{
        open: false,
        items: [],
        totals: { formatted_subtotal: '0.00 ج.م', formatted_total: '0.00 ج.م', total: 0, subtotal: 0, count: 0 },
        loading: false,
        freeDeliveryThreshold: {{ (float) settings('free_delivery_threshold', 500) }},

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
                toastr.success('تم حذف القطعة من حقيبة التسوق');
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
         class="fixed inset-0 bg-[#2B2118]/60 backdrop-blur-xs"></div>

    <!-- Drawer Panel -->
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
        <div class="p-5 bg-[#AC9653] text-white flex items-center justify-between border-b border-[#BDAB75]/40">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-none bg-white/20 text-white flex items-center justify-center font-bold text-sm border border-white/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-serif font-bold text-base text-[#FBF6EA]">حقيبة المشتريات</h3>
                    <p class="text-[11px] text-[#CDB78F]">{{ settings('site_name', 'أزياء لافيستا') }}</p>
                </div>
            </div>

            <button type="button" 
                    @click="open = false" 
                    class="p-2 rounded-xl bg-[#382416] text-[#EADFC8] hover:text-white transition-colors cursor-pointer"
                    aria-label="إغلاق حقيبة التسوق">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Free Delivery Dynamic Progress Bar -->
        <div class="p-4 bg-[#FBF6EA] border-b border-[#EADFC8] text-xs space-y-2">
            <div class="flex items-center justify-between font-medium">
                <div class="flex items-center gap-1.5" x-show="remainingForFreeDelivery > 0">
                    <svg class="w-4 h-4 text-[#AC9653] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                    </svg>
                    <span class="text-[#2B2118]">
                        أضف بقيمة <strong class="text-[#A9552F] tabular-nums font-bold" x-text="remainingForFreeDelivery.toFixed(1) + ' ج.م'"></strong> للشحن المجاني
                    </span>
                </div>
                <div class="flex items-center gap-1.5 text-emerald-800 font-bold" x-show="remainingForFreeDelivery <= 0">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>مبروك! طلبيتك مؤهلة للشحن المجاني</span>
                </div>
                <span class="text-[11px] text-[#8F7B63] font-bold tabular-nums" x-text="progressPercent + '%'"></span>
            </div>

            <div class="w-full h-2 rounded-full bg-[#EADFC8] overflow-hidden p-0.5">
                <div class="h-full transition-all duration-500 rounded-full"
                     :class="progressPercent >= 100 ? 'bg-emerald-600' : 'bg-gradient-to-l from-[#AC9653] to-[#CBA367]'"
                     :style="'width: ' + progressPercent + '%'"></div>
            </div>
        </div>

        <!-- Items Scroll Area -->
        <div class="flex-1 overflow-y-auto p-4 divide-y divide-[#EADFC8]">
            <template x-if="items.length > 0">
                <div>
                    <template x-for="item in items" :key="item.key">
                        <div class="py-3.5 first:pt-0 last:pb-0 flex items-start gap-3">
                            <!-- Image -->
                            <div class="w-16 h-16 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] p-0.5 shrink-0 overflow-hidden flex items-center justify-center">
                                <template x-if="item.image">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover rounded-lg">
                                </template>
                                <template x-if="!item.image">
                                    <svg class="w-6 h-6 text-[#A9552F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </template>
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0 space-y-1">
                                <h4 class="font-bold text-xs text-[#2B2118] truncate" x-text="item.name"></h4>
                                
                                <!-- Variant preview: Size / Color -->
                                <template x-if="item.portion_label">
                                    <div class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8]">
                                        <span x-text="item.portion_label"></span>
                                    </div>
                                </template>

                                <div class="flex items-center justify-between pt-1">
                                    <!-- Stepper -->
                                    <div class="flex items-center border border-[#EADFC8] rounded-lg bg-[#FDFBF7] p-0.5 text-xs">
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity - 1)" 
                                                class="w-5 h-5 flex items-center justify-center bg-white rounded text-[#2B2118] font-bold shadow-2xs hover:bg-[#F3E9D2]">
                                            -
                                        </button>
                                        <span class="w-6 text-center font-bold tabular-nums text-xs" x-text="item.quantity"></span>
                                        <button type="button" 
                                                @click="updateQty(item.key, item.quantity + 1)" 
                                                class="w-5 h-5 flex items-center justify-center bg-white rounded text-[#2B2118] font-bold shadow-2xs hover:bg-[#F3E9D2]">
                                            +
                                        </button>
                                    </div>

                                    <!-- Price -->
                                    <span class="font-bold text-xs text-[#A9552F] tabular-nums" x-text="(item.price_usd * item.quantity).toFixed(2) + ' ج.م'"></span>
                                </div>
                            </div>

                            <!-- Delete -->
                            <button type="button" 
                                    @click="removeItem(item.key)"
                                    class="text-[#8F7B63] hover:text-[#A9552F] p-1 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="items.length === 0">
                <div class="py-16 text-center space-y-3">
                    <div class="w-16 h-16 rounded-3xl bg-[#F3E9D2] text-[#8F7B63] flex items-center justify-center mx-auto border border-[#EADFC8]">
                        <svg class="w-7 h-7 text-[#A9552F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h4 class="font-serif font-bold text-base text-[#2B2118]">حقيبة التسوق فارغة</h4>
                    <p class="text-xs text-[#6B5642] max-w-xs mx-auto">تصفحي أحدث تشكيلات الأزياء الراقية وأضيفي قطعك المفضلة للحقيبة الآن!</p>
                </div>
            </template>
        </div>

        <!-- Footer Checkout Actions -->
        <div class="p-4 sm:p-5 bg-[#FBF6EA] border-t border-[#EADFC8] space-y-3" x-show="items.length > 0">
            <div class="flex items-center justify-between text-xs">
                <span class="text-[#6B5642] font-medium">المجموع الفرعي:</span>
                <span class="font-bold text-base text-[#2B2118] tabular-nums" x-text="totals.formatted_subtotal"></span>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('cart') }}" 
                   @click="open = false"
                   class="py-3 px-4 border border-[#BDAB75]/40 bg-white hover:bg-[#FDFBF7] text-[#2B2118] font-bold text-xs text-center transition-all">
                    عرض الحقيبة
                </a>

                <a href="{{ route('checkout.index') }}" 
                   @click="open = false"
                   class="py-3 px-4 bg-[#CBA367] hover:bg-[#b88f55] text-white font-bold text-xs tracking-wider text-center shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <span>إتمام الطلب الفاخر</span>
                    <span>←</span>
                </a>
            </div>
        </div>

    </div>
</div>
