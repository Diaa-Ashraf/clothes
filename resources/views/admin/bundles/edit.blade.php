@extends('layouts.admin')

@section('title', __('تعديل العرض المجمع'))

@section('content')
@php
    $initialItems = $bundle->products->map(function($p) {
        return [
            'id' => $p->id,
            'quantity' => (int) ($p->pivot->quantity ?? 1),
        ];
    })->toArray();
    if (empty($initialItems)) {
        $initialItems = [['id' => '', 'quantity' => 1]];
    }
@endphp

<div class="max-w-5xl mx-auto space-y-6" 
     x-data="{
         bundlePrice: {{ old('bundle_price', $bundle->bundle_price) }},
         catalog: {{ json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'price' => (float)$p->price])) }},
         items: {{ json_encode(old('products', $initialItems)) }},
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
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('تعديل العرض المجمع') }}: <span class="text-red-600">{{ $bundle->name }}</span></h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('تعديل المنتجات المشمولة، سعر العرض المخفض، أو المواعيد') }}</p>
        </div>
        <a href="{{ route('admin.bundles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-900 text-xs font-bold transition-colors">
            <span>&rarr;</span>
            <span>{{ __('العودة للعروض المجمعة') }}</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold">
            <div class="mb-1">{{ __('يرجى تصحيح الأخطاء التالية:') }}</div>
            <ul class="list-disc list-inside space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.bundles.update', $bundle->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            {{-- Left column: Details --}}
            <div class="lg:col-span-6 space-y-6">
                <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-7 shadow-xs space-y-5">
                    <h3 class="font-black text-gray-900 text-sm border-b border-orange-100 pb-3 flex items-center gap-2">
                        <span>📦</span>
                        <span>1. تفاصيل الباقة والعرض</span>
                    </h3>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('اسم العرض المجمع') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('name', $bundle->name) }}" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('وصف مختصر للعرض') }}</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-medium transition-all">{{ old('description', $bundle->description) }}</textarea>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('سعر الباقة الإجمالي المخفض') }} (ج.م) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" name="bundle_price" x-model="bundlePrice" class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 font-black text-xs focus:outline-none" required>
                                <span class="absolute left-3 top-2.5 text-xs text-emerald-600 font-bold">ج.م</span>
                            </div>
                            <small class="text-[11px] text-gray-400 mt-1 block font-medium">السعر المخفض النهائي للزبون</small>
                        </div>

                        {{-- Real-time calculation summary card --}}
                        <div class="p-4 rounded-2xl bg-orange-50/50 border border-orange-200 space-y-2.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600 font-bold">مجموع الأسعار الأصلية:</span>
                                <span class="font-black text-gray-900 tabular-nums" x-text="originalTotal.toFixed(2) + ' ج.م'">{{ number_format($bundle->original_total, 2) }} ج.م</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-600 font-bold">قيمة التوفير للعميل:</span>
                                <span class="font-black text-emerald-600 tabular-nums" x-text="savingsAmount + ' ج.م'">0.00 ج.م</span>
                            </div>
                            <div class="flex items-center justify-between text-xs pt-2 border-t border-orange-200/60">
                                <span class="text-gray-700 font-bold">نسبة الخصم المحسوبة:</span>
                                <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-black text-xs tabular-nums" x-text="'%' + discountPercent">%{{ $bundle->discount_percent }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('صورة العرض الترويجي') }}</label>
                            @if($bundle->image_url)
                                <div class="mb-2 flex items-center gap-3 p-2 bg-canvas-base rounded-2xl border border-orange-100">
                                    <img src="{{ $bundle->image_url }}" alt="" class="w-12 h-12 rounded-xl object-cover border border-orange-200">
                                    <span class="text-[11px] text-gray-400 font-medium">الصورة الحالية للباقة</span>
                                </div>
                            @endif
                            <input type="file" name="image" class="w-full px-3.5 py-2 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-red-600 cursor-pointer" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-orange-100 rounded-3xl p-6 shadow-xs space-y-4">
                    <h3 class="font-black text-gray-900 text-sm border-b border-orange-100 pb-3 flex items-center gap-2">
                        <span>⏰</span>
                        <span>3. توقيت الصلاحية والحالة</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('تاريخ بدء العرض') }}</label>
                            <input type="datetime-local" name="starts_at" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('starts_at', $bundle->starts_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('تاريخ انتهاء العرض') }}</label>
                            <input type="datetime-local" name="ends_at" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('ends_at', $bundle->ends_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-orange-200" {{ $bundle->is_active ? 'checked' : '' }}>
                            <span class="text-xs font-bold text-gray-900">✓ تفعيل هذا العرض في المتجر</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Right column: Dynamic Products Repeater --}}
            <div class="lg:col-span-6">
                <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-7 shadow-xs space-y-5">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-3">
                        <div>
                            <h3 class="font-black text-gray-900 text-sm flex items-center gap-2">
                                <span>🛍️</span>
                                <span>2. المنتجات المشمولة في الباقة</span>
                            </h3>
                            <p class="text-[11px] text-gray-400 mt-0.5 font-medium">عدّل أو أضف أي أصناف بالكميات المطلوبة</p>
                        </div>
                        <button type="button" @click="addItem()" class="px-3 py-1.5 rounded-xl bg-orange-100 hover:bg-orange-200 text-orange-800 text-xs font-black transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span>+</span>
                            <span>إضافة منتج آخر</span>
                        </button>
                    </div>

                    <div class="space-y-3.5">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100 transition-all hover:border-orange-300 relative space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-black text-red-600 flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-red-100 text-red-700 flex items-center justify-center text-[10px]" x-text="index + 1"></span>
                                        <span>المنتج رقم <span x-text="index + 1"></span></span>
                                    </span>
                                    <button type="button" 
                                            x-show="items.length > 1" 
                                            @click="removeItem(index)" 
                                            class="text-xs text-rose-500 hover:text-rose-700 font-bold p-1 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer"
                                            title="حذف هذا المنتج من الباقة">
                                        ✕ إزالة
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                                    <div class="sm:col-span-8">
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">اختر المنتج <span class="text-red-500">*</span></label>
                                        <select :name="'products[' + index + '][id]'" 
                                                x-model="item.id" 
                                                class="w-full px-3 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold" 
                                                required>
                                            <option value="">-- اختر المنتج من القائمة --</option>
                                            <template x-for="p in catalog" :key="p.id">
                                                <option :value="p.id" 
                                                        :selected="item.id == p.id"
                                                        x-text="p.name + ' (' + p.price.toFixed(2) + ' ج.م)'">
                                                </option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="sm:col-span-4">
                                        <label class="block text-[11px] font-bold text-gray-700 mb-1">الكمية في الباقة</label>
                                        <div class="relative">
                                            <input type="number" 
                                                   min="1" 
                                                   :name="'products[' + index + '][quantity]'" 
                                                   x-model="item.quantity" 
                                                   class="w-full px-3 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold text-center" 
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                {{-- Selected Product Info Pill --}}
                                <div x-show="item.id && getProduct(item.id)" class="pt-1 flex items-center justify-between text-[11px] text-gray-500 bg-white/70 px-3 py-1.5 rounded-lg border border-orange-50">
                                    <span>سعر القطعة المفردة: <strong class="text-gray-900" x-text="getProduct(item.id) ? getProduct(item.id).price.toFixed(2) + ' ج.م' : ''"></strong></span>
                                    <span>الإجمالي للصنف: <strong class="text-red-600" x-text="getProduct(item.id) ? ((getProduct(item.id).price * (parseInt(item.quantity) || 1)).toFixed(2) + ' ج.م') : ''"></strong></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addItem()" class="w-full py-2.5 rounded-xl border-2 border-dashed border-orange-200 hover:border-orange-400 bg-orange-50/40 hover:bg-orange-50 text-orange-800 text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span>+ إضافة منتج إضافي للعرض المجمع</span>
                    </button>

                    <div class="pt-4 border-t border-orange-100">
                        <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-xs shadow-md shadow-red-500/20 transition-all cursor-pointer flex items-center justify-center gap-2">
                            <span>✓</span>
                            <span>حفظ التعديلات</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
