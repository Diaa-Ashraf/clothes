@extends('layouts.admin')

@section('title', 'إدارة المخزون والمستودعات')

@section('content')
<div class="space-y-6" 
     x-data="{
        adjustModal: false,
        selectedProduct: null,
        selectedVariant: null,
        variantsList: [],
        type: 'purchase',
        quantityDelta: 10,
        branchId: '',
        reason: '',

        openAdjust(product, variant = null) {
            this.selectedProduct = product;
            this.selectedVariant = variant;
            this.variantsList = product.variants || [];
            this.quantityDelta = 10;
            this.type = 'purchase';
            this.reason = '';
            this.branchId = '';
            this.adjustModal = true;
        }
     }">

    {{-- Top Header --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </span>
                <span class="text-xs font-semibold text-clay uppercase tracking-wider">لوحة جرد الأزياء</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-espresso">
                إدارة المخزون والمستودعات
            </h1>
            <p class="text-xs text-mocha mt-1">
                متابعة أرصدة قطع الملابس، المقاسات، الألوان، وتنفيذ تسويات وتوريدات المخزون الفورية.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.stocks.movements') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all shadow-2xs">
                <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                سجل حركات المخزون
            </a>
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة قطعة أزياء
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Stats Counters --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-cream border border-sand/40 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold text-mocha block">إجمالي القطع الجاهزة</span>
            <div class="text-2xl font-bold text-espresso mt-1 tabular-nums">
                {{ number_format($metrics['total_units']) }} <span class="text-xs font-normal text-taupe">قطعة</span>
            </div>
            <span class="text-[10px] text-taupe mt-0.5 block">في المستودعات والمنافذ</span>
        </div>

        <div class="bg-cream border border-sand/40 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold text-mocha block">المقاسات والألوان المسجلة</span>
            <div class="text-2xl font-bold text-espresso mt-1 tabular-nums">
                {{ number_format($metrics['total_variants']) }} <span class="text-xs font-normal text-taupe">تنويع</span>
            </div>
            <span class="text-[10px] text-taupe mt-0.5 block">عبر جميع الموديلات</span>
        </div>

        <div class="bg-cream border border-sand/40 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold text-amber-800 block">قطع أوشكت على النفاد</span>
            <div class="text-2xl font-bold text-amber-700 mt-1 tabular-nums">
                {{ number_format($metrics['low_stock_count']) }} <span class="text-xs font-normal text-amber-600">قطعة</span>
            </div>
            <span class="text-[10px] text-amber-700/80 mt-0.5 block">الرصيد أقل من 5 قطع</span>
        </div>

        <div class="bg-cream border border-sand/40 rounded-2xl p-4 shadow-2xs">
            <span class="text-[11px] font-bold text-rose-800 block">قطع نفدت بالكامل</span>
            <div class="text-2xl font-bold text-rose-700 mt-1 tabular-nums">
                {{ number_format($metrics['out_of_stock_count']) }} <span class="text-xs font-normal text-rose-600">موديل</span>
            </div>
            <span class="text-[10px] text-rose-700/80 mt-0.5 block">تحتاج إعادة تصنيع أو شراء</span>
        </div>
    </div>

    {{-- Filters Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('admin.stocks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <div class="lg:col-span-4">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث باسم الموديل أو رمز SKU أو الباركود..." 
                       class="w-full px-3.5 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso placeholder-taupe focus:outline-none focus:border-action">
            </div>

            <div class="lg:col-span-3">
                <select name="category_id" class="w-full px-3 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso focus:outline-none focus:border-action">
                    <option value="">-- جميع الأقسام --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->parent ? '— ' . $cat->name : '● ' . $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <select name="brand_id" class="w-full px-3 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso focus:outline-none focus:border-action">
                    <option value="">-- كل العلامات --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-2">
                <select name="stock_status" class="w-full px-3 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso focus:outline-none focus:border-action">
                    <option value="">-- كل حالات المخزون --</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>متوفر بكثرة (> 5)</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>منخفض (1 إلى 5)</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>نفدت الكمية (0)</option>
                </select>
            </div>

            <div class="lg:col-span-1 flex items-center gap-1.5">
                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold transition-all shadow-xs cursor-pointer">
                    تصفية
                </button>
            </div>
        </form>
    </div>

    {{-- Inventory Table --}}
    <div class="bg-cream border border-sand/40 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead class="bg-linen/60 text-mocha font-bold border-b border-sand/40 text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-right">القطعة والكود</th>
                        <th class="py-3 px-4 text-right">التصنيف والدار</th>
                        <th class="py-3 px-4 text-center">تفصيل المقاسات والألوان</th>
                        <th class="py-3 px-4 text-center">إجمالي الرصيد</th>
                        <th class="py-3 px-4 text-center">الحالة</th>
                        <th class="py-3 px-4 text-left">تسوية وتوريد</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand/30">
                    @forelse($products as $product)
                        <tr class="hover:bg-vanilla/30 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <div class="w-12 h-14 rounded-xl bg-vanilla border border-sand/50 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-espresso truncate block max-w-xs text-xs">{{ $product->name }}</span>
                                    @if($product->sku)
                                        <span class="font-mono text-[10px] text-taupe dir-ltr text-right block mt-0.5">{{ $product->sku }}</span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-linen border border-sand/40 text-[10px] font-medium text-mocha">
                                        {{ $product->category?->name ?? 'غير مصنف' }}
                                    </span>
                                    @if($product->brand)
                                        <span class="text-[10px] text-espresso font-semibold">
                                            {{ $product->brand->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($product->variants->isNotEmpty())
                                    <div class="flex flex-wrap gap-1.5 justify-center max-w-md mx-auto">
                                        @foreach($product->variants as $variant)
                                            <div class="group relative inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-vanilla/80 border border-sand/60 text-[11px] shadow-2xs hover:border-action transition-all">
                                                @php
                                                    $cVal = $variant->attributeValues->firstWhere('attribute.type', 'color') ?? $variant->attributeValues->firstWhere('color_code', '!=', null);
                                                    $sVal = $variant->attributeValues->firstWhere('attribute.type', 'button') ?? $variant->attributeValues->firstWhere('color_code', null);
                                                @endphp

                                                @if($cVal && $cVal->color_code)
                                                    <span class="w-2.5 h-2.5 rounded-full border border-black/10 shrink-0" style="background-color: {{ $cVal->color_code }};"></span>
                                                @endif

                                                <span class="font-bold text-espresso">
                                                    {{ $sVal ? $sVal->value : '' }}{{ ($sVal && $cVal) ? ' / ' : '' }}{{ $cVal ? $cVal->value : '' }}
                                                </span>

                                                <span class="px-1.5 py-0.2 rounded-md font-mono text-[10px] font-bold {{ $variant->stock_quantity > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                                    {{ $variant->stock_quantity }}
                                                </span>

                                                <button type="button" 
                                                        @click='openAdjust(@js($product), @js($variant))'
                                                        class="text-taupe hover:text-action cursor-pointer text-xs" 
                                                        title="تعديل هذا المقاس/اللون تحديداً">
                                                    ✏️
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-[11px] text-taupe text-center block">قطعة مفردة بدون تنويعات</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-bold text-xs text-espresso">
                                {{ $product->stock_quantity }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($product->stock_quantity > 5)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100/80 text-emerald-800 border border-emerald-200 text-[10px] font-bold">
                                        متوفر
                                    </span>
                                @elseif($product->stock_quantity > 0)
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100/80 text-amber-800 border border-amber-200 text-[10px] font-bold">
                                        منخفض
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-100/80 text-rose-800 border border-rose-200 text-[10px] font-bold">
                                        نفدت الكمية
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-left">
                                <button type="button" 
                                        @click='openAdjust(@js($product))'
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all cursor-pointer">
                                    <svg class="w-3.5 h-3.5 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    تسوية / توريد
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-taupe">
                                لا توجد قطع مطابقة لشروط البحث والتصفية.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-sand/40 bg-linen/20">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- Stock Adjustment Modal --}}
    <div x-show="adjustModal" 
         x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/40 flex items-center justify-center p-4">
        <div @click.away="adjustModal = false" class="bg-cream border border-sand/60 rounded-2xl max-w-lg w-full p-6 shadow-xl space-y-5">
            <div class="flex items-center justify-between border-b border-sand/40 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-espresso">تسوية وتوريد المخزون الفوري</h3>
                    <p class="text-[11px] text-mocha" x-text="selectedProduct ? selectedProduct.name : ''"></p>
                </div>
                <button @click="adjustModal = false" class="text-taupe hover:text-espresso text-base cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('admin.stocks.adjust') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" :value="selectedProduct ? selectedProduct.id : ''">

                {{-- Variant selector if product has variants --}}
                <div x-show="variantsList.length > 0">
                    <label class="block text-xs font-bold text-espresso mb-1">المقاس واللون المستهدف</label>
                    <select name="variant_id" 
                            x-model="selectedVariant ? selectedVariant.id : ''"
                            class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action">
                        <option value="">-- تسوية المنتج ككل أو اختر تنويع محدد --</option>
                        <template x-for="v in variantsList" :key="v.id">
                            <option :value="v.id" x-text="v.sku + ' (المخزون الحالي: ' + v.stock_quantity + ')'"></option>
                        </template>
                    </select>
                </div>

                {{-- Movement Type --}}
                <div>
                    <label class="block text-xs font-bold text-espresso mb-1">نوع الحركة المخزنية <span class="text-clay">*</span></label>
                    <select name="type" x-model="type" class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action">
                        <option value="purchase">توريد جديد (شراء أو تشغيل مصنع) (+)</option>
                        <option value="adjustment">تسوية جردية (+ أو -)</option>
                        <option value="return">مرتجع من عميل (+)</option>
                        <option value="sale">صرف بيع مباشر / معرض (-)</option>
                        <option value="damaged">تالف أو عيب تصنيع (-)</option>
                    </select>
                </div>

                {{-- Quantity Delta --}}
                <div>
                    <label class="block text-xs font-bold text-espresso mb-1">
                        كمية التعديل <span class="text-clay">*</span>
                        <span class="text-[10px] text-taupe font-normal">(استخدم إشارة السالب - للخصم، والموجب للإضافة)</span>
                    </label>
                    <input type="number" 
                           name="quantity_delta" 
                           x-model="quantityDelta"
                           class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso font-mono focus:outline-none focus:border-action" 
                           required>
                </div>

                {{-- Branch (Optional) --}}
                @if($branches->isNotEmpty())
                    <div>
                        <label class="block text-xs font-bold text-espresso mb-1">المستودع أو الفرع</label>
                        <select name="branch_id" x-model="branchId" class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action">
                            <option value="">-- المستودع الرئيسي الافتراضي --</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Reason / Notes --}}
                <div>
                    <label class="block text-xs font-bold text-espresso mb-1">سبب التعديل أو رقم إذن التوريد</label>
                    <input type="text" 
                           name="reason" 
                           x-model="reason"
                           placeholder="مثال: إذن استلام تشغيلة #402، جرد ربع سنوي" 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-sand/60 bg-vanilla/30 text-espresso focus:outline-none focus:border-action">
                </div>

                {{-- Actions --}}
                <div class="pt-4 border-t border-sand/40 flex items-center justify-end gap-2">
                    <button type="button" @click="adjustModal = false" class="px-4 py-2 rounded-xl bg-linen text-espresso text-xs font-semibold hover:bg-beige cursor-pointer">
                        إلغاء
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-bold shadow-xs cursor-pointer">
                        تنفيذ وحفظ الحركة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
