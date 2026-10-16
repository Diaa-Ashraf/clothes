@extends('layouts.admin')

@section('title', __('إنشاء إصدار مجلة عروض جديد'))

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="magazineForm({{ Js::from($products) }}, [])">
    
    <!-- Header -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <a href="{{ route('admin.magazines.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 transition-colors">
                    ← العودة لقائمة المجلات
                </a>
                <span class="text-xs text-gray-400">•</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-red-50 text-red-700">
                    إصدار جديد
                </span>
            </div>
            <h1 class="font-sans text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ __('إنشاء وتجهيز مجلة عروض جديدة') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                {{ __('حدد تواريخ المجلة وأضف المنتجات المخفضة التي ستظهر في البانر والصفحة الرئيسية بضغطة زر.') }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.magazines.index') }}" class="px-4 py-2.5 rounded-2xl border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-bold transition-all">
                {{ __('إلغاء') }}
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1.5 shadow-xs">
        <div class="font-bold flex items-center gap-2">
            <span class="w-5 h-5 rounded-full bg-rose-200 text-rose-800 flex items-center justify-center">!</span>
            <span>يرجى تصحيح الأخطاء التالية قبل الحفظ:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 pr-4">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.magazines.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. Magazine Main Details -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="pb-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                    <span>📰</span>
                    <span>{{ __('بيانات وتوقيت إصدار المجلة') }}</span>
                </h2>
                <p class="text-xs text-gray-500 mt-1">البيانات التي ستظهر في البانر العلوي لصفحة العروض.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-gray-900 mb-1.5">
                        {{ __('عنوان إصدار المجلة والكتالوج') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required
                        value="{{ old('title', 'كتالوج ومجلة ' . settings('site_name', 'أزياء لافيستا')) }}"
                        placeholder="مثال: كتالوج ومجلة {{ settings('site_name', 'أزياء لافيستا') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 font-bold text-xs focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                </div>

                <!-- Badge -->
                <div>
                    <label for="badge_text" class="block text-xs font-bold text-gray-900 mb-1.5">
                        {{ __('نص الشارة العلوية (Badge)') }}
                    </label>
                    <input type="text" name="badge_text" id="badge_text"
                        value="{{ old('badge_text', 'إصدار الموسم الجديد • تشكيلات حصرية') }}"
                        placeholder="إصدار الموسم الجديد • تشكيلات حصرية"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 font-bold text-xs focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                </div>

                <!-- Subtitle -->
                <div class="md:col-span-2">
                    <label for="subtitle" class="block text-xs font-bold text-gray-900 mb-1.5">
                        {{ __('الوصف الترويجي أسفل العنوان') }}
                    </label>
                    <input type="text" name="subtitle" id="subtitle"
                        value="{{ old('subtitle', 'تصفح أحدث موديلات وتنسيقات الموسم، إطلالات كاملة، وتخفيضات استثنائية مع حق المعاينة وقياس المقاس.') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 font-medium text-xs focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                </div>

                <!-- Branch Note -->
                <div class="md:col-span-2">
                    <label for="branch_note" class="block text-xs font-bold text-gray-900 mb-1.5">
                        {{ __('ملاحظة الفروع والتوصيل') }}
                    </label>
                    <input type="text" name="branch_note" id="branch_note"
                        value="{{ old('branch_note', 'متاحة بجميع الفروع والتوصيل المنزلي') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 text-gray-900 font-medium text-xs focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:outline-none transition-all">
                </div>
            </div>

            <!-- Timing and Countdown Period -->
            <div class="p-5 rounded-2xl bg-amber-50/60 border border-amber-200 space-y-4">
                <div class="flex items-center gap-2 text-xs font-black text-amber-900 pb-2 border-b border-amber-200/60">
                    <span>⏱️</span>
                    <span>فترة سريان المجلة والعداد التنازلي التلقائي</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="starts_at" class="block text-xs font-bold text-gray-800 mb-1.5">
                            {{ __('تاريخ وبدء سريان المجلة') }}
                        </label>
                        <input type="datetime-local" name="starts_at" id="starts_at"
                            value="{{ old('starts_at', now()->format('Y-m-d\TH:i')) }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-amber-300 font-bold text-gray-900 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label for="ends_at" class="block text-xs font-bold text-red-700 mb-1.5">
                            {{ __('تاريخ ووقت انتهاء المجلة والعداد التنازلي') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" name="ends_at" id="ends_at" required
                            value="{{ old('ends_at', now()->endOfWeek()->format('Y-m-d\TH:i')) }}"
                            class="w-full px-3.5 py-2.5 text-xs rounded-xl bg-white border border-red-300 font-bold text-gray-900 focus:ring-2 focus:ring-red-500">
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-800">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-red-600 focus:ring-red-500 border-gray-300">
                        <span>تعيين كمجلة نشطة حالية للمتجر (سيتم عرضها في صفحة العروض مباشرة)</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-800">
                        <input type="checkbox" name="show_countdown" value="1" {{ old('show_countdown', '1') == '1' ? 'checked' : '' }}
                            class="w-4 h-4 rounded text-red-600 focus:ring-red-500 border-gray-300">
                        <span>إظهار صندوق العداد التنازلي بالبانر</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 2. Interactive Products & Deals Selection -->
        <div class="bg-white border border-gray-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-100">
                <div>
                    <h2 class="text-base font-black text-gray-900 flex items-center gap-2">
                        <span>🏷️</span>
                        <span>{{ __('منتجات وعروض المجلة الحصرية') }}</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">
                        اختر المنتجات وحدد نسبة الخصم وسعر العرض لكل منتج في ثوانٍ.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs font-black px-3 py-1.5 rounded-xl bg-red-50 text-red-700 border border-red-200" x-text="'إجمالي المنتجات: ' + items.length + ' منتج'"></span>
                    <button type="button" @click="addItem()" class="px-4 py-2 rounded-xl bg-primary-800 hover:bg-primary-900 text-white text-xs font-black transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <span>+ إضافة منتج للمجلة</span>
                    </button>
                </div>
            </div>

            <!-- Quick Add Bar -->
            <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-gray-600 mb-1">إضافة سريعة من قائمة المنتجات:</label>
                    <select x-model="quickSelectedProductId" @change="onQuickSelect()" class="w-full px-3.5 py-2 text-xs rounded-xl bg-white border border-gray-300 font-bold text-gray-900 focus:ring-2 focus:ring-primary-700">
                        <option value="">-- اضغط لاختيار منتج وإضافته للمجلة فوراً --</option>
                        <template x-for="p in allProducts" :key="p.id">
                            <option :value="p.id" x-text="p.name + ' (السعر الأصلي: ' + p.price + ' ج.م)'"></option>
                        </template>
                    </select>
                </div>
                <div class="shrink-0 pt-4 md:pt-0">
                    <button type="button" @click="addItem()" class="w-full md:w-auto px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-xs cursor-pointer">
                        + إضافة سطر مخصص
                    </button>
                </div>
            </div>

            <!-- Items Table / Cards -->
            <div class="space-y-3">
                <template x-if="items.length === 0">
                    <div class="p-8 text-center rounded-2xl border-2 border-dashed border-gray-200 text-gray-400 space-y-2">
                        <span class="text-3xl block">🛒</span>
                        <span class="text-xs font-bold block text-gray-600">لم تقم بإضافة أي منتجات للمجلة بعد</span>
                        <p class="text-[11px] text-gray-400">اختر من القائمة أعلاه أو اضغط على "+ إضافة منتج للمجلة" للبدء.</p>
                    </div>
                </template>

                <div class="space-y-3">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="p-4.5 rounded-2xl border border-gray-200 hover:border-red-300 bg-white transition-all shadow-2xs space-y-4">
                            <div class="flex items-center justify-between gap-3 pb-2 border-b border-gray-100 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-red-100 text-red-700 font-black text-[11px] flex items-center justify-center" x-text="index + 1"></span>
                                    <span class="font-black text-gray-900" x-text="getProductName(item.product_id)"></span>
                                </div>
                                <button type="button" @click="removeItem(index)" class="text-rose-600 hover:text-rose-800 text-xs font-bold flex items-center gap-1 cursor-pointer">
                                    <span>🗑️</span>
                                    <span>حذف من المجلة</span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                                <!-- Product Select -->
                                <div class="lg:col-span-2">
                                    <label class="block text-[11px] font-bold text-gray-700 mb-1">المنتج <span class="text-red-500">*</span></label>
                                    <select :name="'items[' + index + '][product_id]'" x-model="item.product_id" @change="onProductChange(item)" required
                                        class="w-full px-3 py-2 text-xs rounded-xl bg-gray-50 border border-gray-300 font-bold text-gray-900 focus:bg-white focus:ring-2 focus:ring-red-500">
                                        <option value="">-- اختر المنتج --</option>
                                        <template x-for="p in allProducts" :key="p.id">
                                            <option :value="p.id" :selected="p.id == item.product_id" x-text="p.name"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Original Price -->
                                <div>
                                    <label class="block text-[11px] font-bold text-gray-500 mb-1">السعر الأصلي (ج.م)</label>
                                    <input type="number" step="0.01" :name="'items[' + index + '][original_price]'" x-model="item.original_price" @input="calculateFromOriginal(item)" required
                                        class="w-full px-3 py-2 text-xs rounded-xl bg-gray-100 border border-gray-300 font-black text-gray-700">
                                </div>

                                <!-- Discount Percent -->
                                <div>
                                    <label class="block text-[11px] font-bold text-red-600 mb-1">نسبة الخصم %</label>
                                    <input type="number" step="0.1" min="0" max="100" :name="'items[' + index + '][discount_percent]'" x-model="item.discount_percent" @input="calculateFromDiscount(item)"
                                        placeholder="مثال: 20%"
                                        class="w-full px-3 py-2 text-xs rounded-xl bg-red-50/50 border border-red-200 font-black text-red-700 focus:bg-white focus:ring-2 focus:ring-red-500">
                                </div>

                                <!-- Deal Price -->
                                <div>
                                    <label class="block text-[11px] font-bold text-emerald-700 mb-1">سعر العرض بالمجلة (ج.م) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" min="0.01" :name="'items[' + index + '][deal_price]'" x-model="item.deal_price" @input="calculateFromDealPrice(item)" required
                                        class="w-full px-3 py-2 text-xs rounded-xl bg-emerald-50/50 border border-emerald-300 font-black text-emerald-700 focus:bg-white focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>

                            <!-- Badge & Savings Footer -->
                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 text-[11px]">
                                <div class="flex items-center gap-2 flex-1 min-w-[200px]">
                                    <span class="text-gray-500 font-bold shrink-0">الشارة:</span>
                                    <input type="text" :name="'items[' + index + '][badge_text]'" x-model="item.badge_text" placeholder="مثال: خصم 20% أو عرض الأسبوع"
                                        class="px-2.5 py-1 text-xs rounded-lg bg-gray-50 border border-gray-200 font-medium text-gray-700 w-full max-w-xs focus:bg-white">
                                </div>

                                <div class="text-emerald-700 font-bold">
                                    <span x-show="item.original_price > item.deal_price">
                                        💰 وفر للعميل: <strong x-text="(item.original_price - item.deal_price).toFixed(2) + ' ج.م'"></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Sticky Save Floating Bar -->
        <div class="sticky bottom-4 z-30 bg-white/95 backdrop-blur-md border border-gray-200 rounded-3xl p-4 shadow-xl flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="font-medium">سيتم حفظ إصدار المجلة ونشر كافة عروضها في شاشة واحدة فوراً.</span>
            </div>

            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black transition-all shadow-md shadow-red-600/20 hover:scale-[1.01] active:scale-[0.98] cursor-pointer flex items-center gap-2">
                <span>✓</span>
                <span>{{ __('حفظ ونشر المجلة الآن') }}</span>
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
    function magazineForm(allProducts, initialItems) {
        return {
            allProducts: allProducts || [],
            items: initialItems && initialItems.length > 0 ? initialItems : [],
            quickSelectedProductId: '',

            init() {
                if (this.items.length === 0 && this.allProducts.length > 0) {
                    // Pre-fill 1 empty row
                    this.addItem();
                }
            },

            addItem(productId = '') {
                let p = this.allProducts.find(x => x.id == productId);
                let origPrice = p ? (p.price || 0) : 0;
                let dealPrice = p && p.discount_price && p.discount_price > 0 ? p.discount_price : (origPrice > 0 ? (origPrice * 0.85).toFixed(2) : 0);
                let discPercent = origPrice > 0 ? (((origPrice - dealPrice) / origPrice) * 100).toFixed(0) : 15;

                this.items.push({
                    product_id: productId,
                    original_price: origPrice,
                    deal_price: dealPrice,
                    discount_percent: discPercent,
                    badge_text: discPercent > 0 ? 'خصم ' + discPercent + '%' : 'عرض خاص',
                });
            },

            removeItem(index) {
                this.items.splice(index, 1);
            },

            onQuickSelect() {
                if (!this.quickSelectedProductId) return;
                this.addItem(this.quickSelectedProductId);
                this.quickSelectedProductId = '';
            },

            onProductChange(item) {
                let p = this.allProducts.find(x => x.id == item.product_id);
                if (p) {
                    item.original_price = p.price || 0;
                    if (p.discount_price && p.discount_price > 0 && p.discount_price < p.price) {
                        item.deal_price = p.discount_price;
                        item.discount_percent = (((p.price - p.discount_price) / p.price) * 100).toFixed(0);
                    } else {
                        item.deal_price = (item.original_price * 0.85).toFixed(2);
                        item.discount_percent = 15;
                    }
                    item.badge_text = 'خصم ' + item.discount_percent + '%';
                }
            },

            calculateFromDiscount(item) {
                let orig = parseFloat(item.original_price) || 0;
                let disc = parseFloat(item.discount_percent) || 0;
                if (orig > 0) {
                    item.deal_price = (orig - (orig * (disc / 100))).toFixed(2);
                    item.badge_text = disc > 0 ? 'خصم ' + disc + '%' : 'عرض خاص';
                }
            },

            calculateFromDealPrice(item) {
                let orig = parseFloat(item.original_price) || 0;
                let deal = parseFloat(item.deal_price) || 0;
                if (orig > 0 && deal > 0 && deal < orig) {
                    item.discount_percent = (((orig - deal) / orig) * 100).toFixed(0);
                    item.badge_text = 'خصم ' + item.discount_percent + '%';
                }
            },

            calculateFromOriginal(item) {
                this.calculateFromDiscount(item);
            },

            getProductName(productId) {
                let p = this.allProducts.find(x => x.id == productId);
                return p ? p.name : 'منتج جديد';
            }
        }
    }
</script>
@endpush
@endsection
