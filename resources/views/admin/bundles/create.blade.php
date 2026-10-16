@extends('layouts.admin')

@section('title', 'إنشاء عرض توفير مجمع (Bundle)')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" 
     x-data="{
         bundlePrice: {{ old('bundle_price', '0') }},
         catalog: {{ json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price])) }},
         items: {{ json_encode(old('products', [
             ['id' => '', 'quantity' => 1],
             ['id' => '', 'quantity' => 1]
         ])) }},
         addItem() {
             this.items.push({ id: '', quantity: 1 });
         },
         removeItem(index) {
             if (this.items.length > 1) {
                 this.items.splice(index, 1);
             }
         },
         getProduct(id) {
             return this.catalog.find(p => p.id == id);
         },
         get originalTotal() {
             let total = 0;
             for (let item of this.items) {
                 if (item.id) {
                     let prod = this.getProduct(item.id);
                     if (prod) {
                         total += (prod.price * (parseInt(item.quantity) || 1));
                     }
                 }
             }
             return total;
         },
         get discountPercent() {
             let orig = this.originalTotal;
             let price = parseFloat(this.bundlePrice) || 0;
             if (orig > 0 && price > 0 && price < orig) {
                 return Math.round(((orig - price) / orig) * 100);
             }
             return 0;
         },
         get savingsAmount() {
             let orig = this.originalTotal;
             let price = parseFloat(this.bundlePrice) || 0;
             if (orig > price && price > 0) {
                 return (orig - price).toFixed(2);
             }
             return '0.00';
         }
     }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.bundles.index') }}" class="hover:text-[#2B2118] transition-colors">العروض المجمعة</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">إنشاء باقة</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">إنشاء باقة وعرض توفير مجمع</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تحديد مجموعة قطع أزياء متناسقة بسعر إجمالي مخفض</p>
        </div>
        <a href="{{ route('admin.bundles.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#2B2118] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>العودة للعروض</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-red-50/50 border border-[#A9552F]/30 text-[#A9552F] text-xs space-y-1">
            <div class="font-bold">يرجى مراجعة وتصحيح الملاحظات التالية:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bundles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Right column: Details --}}
            <div class="lg:col-span-6 space-y-6">
                <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 shadow-sm space-y-5">
                    <h3 class="font-serif font-bold text-[#2B2118] text-base border-b border-[#EADFC8] pb-3 flex items-center gap-2">
                        <span>🛍️</span>
                        <span>1. بيانات الباقة والعرض</span>
                    </h3>
                    
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-[#2B2118]">
                            اسم العرض المجمع <span class="text-[#A9552F]">*</span>
                        </label>
                        <input type="text" name="name" 
                               class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all" 
                               placeholder="مثال: طقم شتوي كامل (معطف صوف + بلوفر + بنطال كلاسيك)" 
                               value="{{ old('name') }}" required>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-[#2B2118]">
                            وصف تفصيلي للباقة
                        </label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all" 
                                  placeholder="اكتب تفاصيل تنسيق القطع معاً ومميزات هذا العرض المجمع...">{{ old('description') }}</textarea>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-[#2B2118]">
                                سعر الباقة الإجمالي المخفض (ج.م) <span class="text-[#A9552F]">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" name="bundle_price" x-model="bundlePrice" 
                                       class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-[#CDB78F] bg-[#FDFBF7] text-[#2B2118] font-bold text-sm focus:border-[#2B2118] focus:outline-none dir-ltr text-right" 
                                       placeholder="0.00" required>
                                <span class="absolute left-3.5 top-2.5 text-xs text-[#8F7B63] font-bold">ج.م</span>
                            </div>
                            <span class="text-[11px] text-[#8F7B63] block">السعر المخفض النهائي الذي يدفعه المشتري عند إضافة الباقة للسلة</span>
                        </div>

                        {{-- Calculation Summary Card --}}
                        <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#EADFC8] space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-[#6B5642] font-medium">مجموع الأسعار الأصلية للقطع:</span>
                                <span class="font-bold text-[#2B2118] tabular-nums" x-text="originalTotal.toFixed(2) + ' ج.م'">0.00 ج.م</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-[#6B5642] font-medium">قيمة التوفير المباشر للعميل:</span>
                                <span class="font-bold text-[#A9552F] tabular-nums" x-text="savingsAmount + ' ج.م'">0.00 ج.م</span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-[#EADFC8]">
                                <span class="text-[#2B2118] font-bold">نسبة الخصم الإجمالية:</span>
                                <span class="px-2.5 py-0.5 rounded-full bg-red-50 text-[#A9552F] border border-red-200 font-bold tabular-nums" x-text="'%' + discountPercent">%0</span>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-[#2B2118]">صورة الباقة الترويجية</label>
                            <input type="file" name="image" 
                                   class="w-full px-3 py-2 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#382416] file:text-[#FBF6EA] cursor-pointer" 
                                   accept="image/*">
                        </div>
                    </div>
                </div>

                <!-- Timing Card -->
                <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 shadow-sm space-y-4">
                    <h3 class="font-serif font-bold text-[#2B2118] text-base border-b border-[#EADFC8] pb-3 flex items-center gap-2">
                        <span>⏳</span>
                        <span>3. فترات السريان وتاريخ الصلاحية</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-[#2B2118]">تاريخ بدء العرض</label>
                            <input type="datetime-local" name="starts_at" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] focus:outline-none" 
                                   value="{{ old('starts_at') }}">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-[#2B2118]">تاريخ انتهاء العرض</label>
                            <input type="datetime-local" name="ends_at" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] bg-white text-xs text-[#2B2118] focus:border-[#2B2118] focus:outline-none" 
                                   value="{{ old('ends_at') }}">
                        </div>
                    </div>
                    <div class="pt-2 border-t border-[#EADFC8]">
                        <label class="relative inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-[#EADFC8] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#382416]"></div>
                            <span class="text-xs font-bold text-[#2B2118]">تفعيل هذا العرض في المتجر فوراً</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Left column: Products Repeater --}}
            <div class="lg:col-span-6">
                <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-[#EADFC8] pb-3">
                        <div>
                            <h3 class="font-serif font-bold text-[#2B2118] text-base flex items-center gap-2">
                                <span>👗</span>
                                <span>2. القطع المشمولة في الباقة</span>
                            </h3>
                            <p class="text-[11px] text-[#8F7B63] mt-0.5">اختر الأصناف والكمية المحددة لكل صنف داخل العرض</p>
                        </div>
                        <button type="button" @click="addItem()" 
                                class="px-3 py-1.5 rounded-xl bg-[#F3E9D2] hover:bg-[#EADFC8] text-[#2B2118] text-xs font-medium transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>إضافة صنف آخر</span>
                        </button>
                    </div>

                    <div class="space-y-3.5">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#EADFC8] relative space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#2B2118] flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-[#EADFC8] text-[#2B2118] flex items-center justify-center text-[10px]" x-text="index + 1"></span>
                                        <span>القطعة رقم <span x-text="index + 1"></span></span>
                                    </span>
                                    <button type="button" 
                                            x-show="items.length > 1" 
                                            @click="removeItem(index)" 
                                            class="text-xs text-[#A9552F] hover:text-red-700 font-medium p-1 rounded-lg hover:bg-red-50 transition-colors cursor-pointer">
                                        ✕ إزالة
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                    <div class="sm:col-span-8 space-y-1">
                                        <label class="block text-[11px] font-bold text-[#2B2118]">اختر قطعة الأزياء <span class="text-[#A9552F]">*</span></label>
                                        <select :name="'products[' + index + '][id]'" 
                                                x-model="item.id" 
                                                class="w-full px-3 py-2 rounded-xl border border-[#EADFC8] bg-white text-[#2B2118] focus:border-[#2B2118] focus:outline-none text-xs" 
                                                required>
                                            <option value="">-- اضغط لاختيار منتج من القائمة --</option>
                                            <template x-for="p in catalog" :key="p.id">
                                                <option :value="p.id" 
                                                        :selected="item.id == p.id"
                                                        x-text="p.name + ' (' + p.price.toFixed(2) + ' ج.م)'">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="sm:col-span-4 space-y-1">
                                        <label class="block text-[11px] font-bold text-[#2B2118]">الكمية بالباقة</label>
                                        <input type="number" 
                                               min="1" 
                                               :name="'products[' + index + '][quantity]'" 
                                               x-model="item.quantity" 
                                               class="w-full px-3 py-2 rounded-xl border border-[#EADFC8] bg-white text-[#2B2118] focus:border-[#2B2118] focus:outline-none text-xs text-center font-bold" 
                                               required>
                                    </div>
                                </div>

                                {{-- Selected Product Info Pill --}}
                                <div x-show="item.id && getProduct(item.id)" class="pt-1 flex items-center justify-between text-[11px] text-[#6B5642] bg-white px-3 py-1.5 rounded-lg border border-[#EADFC8]">
                                    <span>سعر القطعة المفردة: <strong class="text-[#2B2118]" x-text="getProduct(item.id) ? getProduct(item.id).price.toFixed(2) + ' ج.م' : ''"></strong></span>
                                    <span>الإجمالي للصنف: <strong class="text-[#A9552F]" x-text="getProduct(item.id) ? ((getProduct(item.id).price * (parseInt(item.quantity) || 1)).toFixed(2) + ' ج.م') : ''"></strong></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addItem()" 
                            class="w-full py-2.5 rounded-xl border-2 border-dashed border-[#CDB78F] hover:border-[#2B2118] bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] text-xs font-medium transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>+ إضافة قطعة أزياء إضافية للباقة</span>
                    </button>

                    <div class="pt-4 border-t border-[#EADFC8]">
                        <button type="submit" 
                                class="w-full py-3 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-medium text-sm shadow-sm transition-all cursor-pointer flex items-center justify-center gap-2">
                            <span>حفظ ونشر العرض المجمع</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
