@extends('layouts.admin')

@section('title', 'تعديل قطعة الأزياء: ' . $product->name)

@php
    $initialVariants = $product->variants->map(function($v) {
        $sizeVal = $v->attributeValues->firstWhere('attribute.type', 'button') 
            ?? $v->attributeValues->firstWhere('attribute.code', 'size');
        $colorVal = $v->attributeValues->firstWhere('attribute.type', 'color') 
            ?? $v->attributeValues->firstWhere('attribute.code', 'color');
        $labelParts = [];
        if ($sizeVal) $labelParts[] = $sizeVal->value;
        if ($colorVal) $labelParts[] = $colorVal->value;
        return [
            'id' => $v->id,
            'label' => !empty($labelParts) ? implode(' / ', $labelParts) : ($v->sku ?? 'تنويع #' . $v->id),
            'sku' => $v->sku,
            'barcode' => $v->barcode,
            'price' => $v->price,
            'stock_quantity' => $v->stock_quantity,
            'colorHex' => $colorVal ? $colorVal->color_code : null,
            'attribute_values' => $v->attributeValues->pluck('id')->toArray(),
        ];
    });
@endphp

@section('content')
<div class="max-w-5xl mx-auto space-y-6" 
     x-data="{
        hasVariants: {{ $product->variants->count() > 0 ? 'true' : 'false' }},
        selectedSizes: [],
        selectedColors: [],
        variants: @js($initialVariants),
        basePrice: '{{ old('price', $product->price) }}',
        baseSku: '{{ old('sku', $product->sku) }}',

        generateVariants() {
            if (this.selectedSizes.length === 0 && this.selectedColors.length === 0) {
                return;
            }

            let sizes = this.selectedSizes.length > 0 ? this.selectedSizes : [{ id: null, name: '-' }];
            let colors = this.selectedColors.length > 0 ? this.selectedColors : [{ id: null, name: '-', hex: null }];

            sizes.forEach(size => {
                colors.forEach(color => {
                    let attrIds = [];
                    let labelParts = [];

                    if (size.id) {
                        attrIds.push(size.id);
                        labelParts.push(size.name);
                    }
                    if (color.id) {
                        attrIds.push(color.id);
                        labelParts.push(color.name);
                    }

                    let label = labelParts.join(' / ');
                    let autoSku = (this.baseSku ? this.baseSku : 'VAR') + '-' + (size.name !== '-' ? size.name : '') + (color.name !== '-' ? '-' + color.name : '');
                    autoSku = autoSku.replace(/\s+/g, '-');

                    // Check if already in variants to avoid duplicates
                    let exists = this.variants.some(v => v.label === label);
                    if (!exists) {
                        this.variants.push({
                            id: null,
                            label: label,
                            sizeName: size.name,
                            colorName: color.name,
                            colorHex: color.hex,
                            sku: autoSku,
                            barcode: '',
                            price: '',
                            stock_quantity: 5,
                            attribute_values: attrIds
                        });
                    }
                });
            });
        },

        toggleSize(id, name) {
            let index = this.selectedSizes.findIndex(s => s.id === id);
            if (index > -1) {
                this.selectedSizes.splice(index, 1);
            } else {
                this.selectedSizes.push({ id: id, name: name });
            }
            this.generateVariants();
        },

        toggleColor(id, name, hex) {
            let index = this.selectedColors.findIndex(c => c.id === id);
            if (index > -1) {
                this.selectedColors.splice(index, 1);
            } else {
                this.selectedColors.push({ id: id, name: name, hex: hex });
            }
            this.generateVariants();
        },

        removeVariant(index) {
            this.variants.splice(index, 1);
        }
     }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </span>
                <h1 class="text-xl font-bold text-espresso">تعديل قطعة الأزياء: {{ $product->name }}</h1>
            </div>
            <p class="text-xs text-mocha mt-1">تحديث المواصفات، المقاسات، درجات الألوان ومخزون الفروع</p>
        </div>
        <a href="{{ route('admin.products.index') }}" 
           class="px-3.5 py-2 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
            عودة لقائمة المنتجات
        </a>
    </div>

    {{-- Error Alert --}}
    @if (isset($errors) && $errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1">يرجى مراجعة وتصحيح الملاحظات التالية:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 1. Main Info Card --}}
        <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-espresso border-b border-sand/30 pb-2">1. المعلومات الأساسية والتصنيف</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-bold text-espresso mb-1.5">
                        اسم المنتج الكامل <span class="text-clay">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $product->name) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('name') border-rose-500 @enderror"
                           required>
                    @error('name')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sku" class="block text-xs font-bold text-espresso mb-1.5">
                        كود المنتج الرئيسي (SKU)
                    </label>
                    <input type="text" 
                           id="sku" 
                           name="sku" 
                           x-model="baseSku"
                           value="{{ old('sku', $product->sku) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action font-mono dir-ltr text-right transition-all @error('sku') border-rose-500 @enderror">
                    @error('sku')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="category_id" class="block text-xs font-bold text-espresso mb-1.5">
                        القسم والتصنيف <span class="text-clay">*</span>
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('category_id') border-rose-500 @enderror"
                            required>
                        <option value="">-- اختر التصنيف --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->parent ? '— ' . $category->name . ' (' . $category->parent->name . ')' : '● ' . $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="brand_id" class="block text-xs font-bold text-espresso mb-1.5">
                        العلامة التجارية / المصمم
                    </label>
                    <select id="brand_id" 
                            name="brand_id" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('brand_id') border-rose-500 @enderror">
                        <option value="">-- بدون علامة محددة --</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-espresso mb-1.5">
                    تفاصيل ووصف القطعة
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('description') border-rose-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 2. Fashion Attributes --}}
        <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-espresso border-b border-sand/30 pb-2">2. مواصفات وخصائص الموضة</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <label for="gender" class="block text-xs font-bold text-espresso mb-1.5">الفئة المستهدفة</label>
                    <select id="gender" name="gender" class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">
                        <option value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>للجنسين (Unisex)</option>
                        <option value="men" {{ old('gender', $product->gender) == 'men' ? 'selected' : '' }}>رجالي (Men)</option>
                        <option value="women" {{ old('gender', $product->gender) == 'women' ? 'selected' : '' }}>نسائي (Women)</option>
                        <option value="kids" {{ old('gender', $product->gender) == 'kids' ? 'selected' : '' }}>أطفال (Kids)</option>
                    </select>
                </div>

                <div>
                    <label for="season" class="block text-xs font-bold text-espresso mb-1.5">الموسم</label>
                    <select id="season" name="season" class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">
                        <option value="all_seasons" {{ old('season', $product->season) == 'all_seasons' ? 'selected' : '' }}>جميع المواسم</option>
                        <option value="summer" {{ old('season', $product->season) == 'summer' ? 'selected' : '' }}>صيف</option>
                        <option value="winter" {{ old('season', $product->season) == 'winter' ? 'selected' : '' }}>شتاء</option>
                        <option value="spring" {{ old('season', $product->season) == 'spring' ? 'selected' : '' }}>ربيع</option>
                        <option value="autumn" {{ old('season', $product->season) == 'autumn' ? 'selected' : '' }}>خريف</option>
                    </select>
                </div>

                <div>
                    <label for="material" class="block text-xs font-bold text-espresso mb-1.5">نوع القماش / الخامة</label>
                    <input type="text" 
                           id="material" 
                           name="material" 
                           value="{{ old('material', $product->material) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">
                </div>

                <div>
                    <label for="fit" class="block text-xs font-bold text-espresso mb-1.5">القَصّة (Fit)</label>
                    <select id="fit" name="fit" class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action">
                        <option value="regular" {{ old('fit', $product->fit) == 'regular' ? 'selected' : '' }}>Regular Fit (عادي)</option>
                        <option value="slim" {{ old('fit', $product->fit) == 'slim' ? 'selected' : '' }}>Slim Fit (مجسم)</option>
                        <option value="oversized" {{ old('fit', $product->fit) == 'oversized' ? 'selected' : '' }}>Oversized (واسع عصري)</option>
                        <option value="relaxed" {{ old('fit', $product->fit) == 'relaxed' ? 'selected' : '' }}>Relaxed Fit (مريح)</option>
                        <option value="loose" {{ old('fit', $product->fit) == 'loose' ? 'selected' : '' }}>Loose Fit (فضفاض)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- 3. Base Pricing & Base Stock --}}
        <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-espresso border-b border-sand/30 pb-2">3. التسعير والمخزون الأساسي</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="price" class="block text-xs font-bold text-espresso mb-1.5">
                        السعر الأساسي (ج.م) <span class="text-clay">*</span>
                    </label>
                    <input type="number" 
                           step="0.01" 
                           id="price" 
                           name="price" 
                           x-model="basePrice"
                           value="{{ old('price', $product->price) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('price') border-rose-500 @enderror"
                           required>
                    @error('price')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_price" class="block text-xs font-bold text-espresso mb-1.5">
                        سعر الخصم / العرض (اختياري)
                    </label>
                    <input type="number" 
                           step="0.01" 
                           id="discount_price" 
                           name="discount_price" 
                           value="{{ old('discount_price', $product->discount_price) }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('discount_price') border-rose-500 @enderror">
                    @error('discount_price')
                        <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_quantity" class="block text-xs font-bold text-espresso mb-1.5">
                        إجمالي المخزون <span class="text-clay">*</span>
                    </label>
                    <input type="number" 
                           id="stock_quantity" 
                           name="stock_quantity" 
                           value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                           min="0"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-sand/60 bg-vanilla/30 text-espresso text-xs focus:outline-none focus:border-action transition-all @error('stock_quantity') border-rose-500 @enderror"
                           required>
                </div>
            </div>
        </div>

        {{-- 4. Variants Matrix Generator --}}
        <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-sand/30 pb-2">
                <div>
                    <h2 class="text-sm font-bold text-espresso">4. مصفوفة المقاسات والألوان (Product Variants)</h2>
                    <p class="text-[11px] text-mocha">إدارة وتعديل تنويعات المقاسات وكميات المخزون للقطعة</p>
                </div>
                <button type="button" 
                        @click="hasVariants = !hasVariants" 
                        class="text-xs px-3 py-1.5 rounded-xl border border-sand/60 transition-all font-semibold"
                        :class="hasVariants ? 'bg-action text-action-ink' : 'bg-vanilla text-espresso hover:bg-beige'">
                    <span x-text="hasVariants ? 'إخفاء لوحة التنويعات' : '+ تفعيل تنويعات المقاسات والألوان'"></span>
                </button>
            </div>

            <div x-show="hasVariants" x-transition.opacity class="space-y-6">
                {{-- Attributes Selector --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-vanilla/40 p-4 rounded-xl border border-sand/40">
                    @php
                        $sizeAttr = $attributes->firstWhere('code', 'size') ?? $attributes->firstWhere('type', 'button');
                        $colorAttr = $attributes->firstWhere('code', 'color') ?? $attributes->firstWhere('type', 'color');
                    @endphp

                    <div>
                        <span class="text-xs font-bold text-espresso block mb-2">إضافة مقاسات جديدة:</span>
                        <div class="flex flex-wrap gap-1.5">
                            @if($sizeAttr)
                                @foreach($sizeAttr->values as $sVal)
                                    <button type="button" 
                                            @click="toggleSize({{ $sVal->id }}, '{{ $sVal->value }}')"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-all cursor-pointer"
                                            :class="selectedSizes.some(s => s.id === {{ $sVal->id }}) 
                                                ? 'bg-action text-action-ink border-action shadow-xs' 
                                                : 'bg-cream text-espresso border-sand/60 hover:border-action'">
                                        {{ $sVal->value }}
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-bold text-espresso block mb-2">إضافة ألوان جديدة:</span>
                        <div class="flex flex-wrap gap-2">
                            @if($colorAttr)
                                @foreach($colorAttr->values as $cVal)
                                    <button type="button" 
                                            @click="toggleColor({{ $cVal->id }}, '{{ $cVal->value }}', '{{ $cVal->color_code }}')"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-semibold border transition-all cursor-pointer"
                                            :class="selectedColors.some(c => c.id === {{ $cVal->id }}) 
                                                ? 'bg-action text-action-ink border-action shadow-xs' 
                                                : 'bg-cream text-espresso border-sand/60 hover:border-action'">
                                        @if($cVal->color_code)
                                            <span class="w-3 h-3 rounded-full border border-black/10 shrink-0" style="background-color: {{ $cVal->color_code }};"></span>
                                        @endif
                                        <span>{{ $cVal->value }}</span>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Variants Table --}}
                <div x-show="variants.length > 0" class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-espresso">القطع الفرعية (<span x-text="variants.length"></span> تنويع):</span>
                        <span class="text-[11px] text-taupe">القطع المحفوظة في قاعدة البيانات ومتاحة في المتجر</span>
                    </div>

                    <div class="overflow-x-auto border border-sand/40 rounded-xl bg-cream">
                        <table class="w-full text-right text-xs">
                            <thead class="bg-linen/60 text-mocha text-[11px] border-b border-sand/40">
                                <tr>
                                    <th class="py-2.5 px-3">المقاس / اللون</th>
                                    <th class="py-2.5 px-3">كود التنويع (SKU)</th>
                                    <th class="py-2.5 px-3">الباركود</th>
                                    <th class="py-2.5 px-3">السعر (إذا اختلف)</th>
                                    <th class="py-2.5 px-3">كمية المخزون</th>
                                    <th class="py-2.5 px-3 text-center">حذف</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-sand/30">
                                <template x-for="(v, index) in variants" :key="index">
                                    <tr class="hover:bg-vanilla/20">
                                        <td class="py-2 px-3 font-semibold text-espresso">
                                            <div class="flex items-center gap-1.5">
                                                <template x-if="v.colorHex">
                                                    <span class="w-3 h-3 rounded-full border border-black/10 shrink-0" :style="'background-color: ' + v.colorHex"></span>
                                                </template>
                                                <span x-text="v.label"></span>
                                            </div>
                                            <template x-if="v.id">
                                                <input type="hidden" :name="'variants[' + index + '][id]'" :value="v.id">
                                            </template>
                                            <template x-for="attrId in v.attribute_values">
                                                <input type="hidden" :name="'variants[' + index + '][attribute_values][]'" :value="attrId">
                                            </template>
                                        </td>
                                        <td class="py-2 px-3">
                                            <input type="text" 
                                                   :name="'variants[' + index + '][sku]'" 
                                                   x-model="v.sku" 
                                                   class="w-36 px-2 py-1 text-xs rounded-lg border border-sand/60 bg-vanilla/30 text-espresso font-mono dir-ltr text-right">
                                        </td>
                                        <td class="py-2 px-3">
                                            <input type="text" 
                                                   :name="'variants[' + index + '][barcode]'" 
                                                   x-model="v.barcode" 
                                                   placeholder="اختياري"
                                                   class="w-28 px-2 py-1 text-xs rounded-lg border border-sand/60 bg-vanilla/30 text-espresso font-mono dir-ltr text-right">
                                        </td>
                                        <td class="py-2 px-3">
                                            <input type="number" 
                                                   step="0.01" 
                                                   :name="'variants[' + index + '][price]'" 
                                                   x-model="v.price" 
                                                   :placeholder="basePrice || 'أساسي'"
                                                   class="w-24 px-2 py-1 text-xs rounded-lg border border-sand/60 bg-vanilla/30 text-espresso">
                                        </td>
                                        <td class="py-2 px-3">
                                            <input type="number" 
                                                   min="0" 
                                                   :name="'variants[' + index + '][stock_quantity]'" 
                                                   x-model="v.stock_quantity" 
                                                   class="w-20 px-2 py-1 text-xs rounded-lg border border-sand/60 bg-vanilla/30 text-espresso">
                                        </td>
                                        <td class="py-2 px-3 text-center">
                                            <button type="button" @click="removeVariant(index)" class="text-rose-500 hover:text-rose-700 font-bold text-sm cursor-pointer">&times;</button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. Media & Visibility --}}
        <div class="bg-cream border border-sand/40 rounded-2xl p-6 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-espresso border-b border-sand/30 pb-2">5. صورة المنتج وحالة العرض</h2>

            <div>
                <label for="image" class="block text-xs font-bold text-espresso mb-1.5">
                    صورة الموديل الرئيسية (Cover Photo)
                </label>

                @if($product->image_url)
                    <div class="mb-3 flex items-center gap-3 p-3 rounded-xl bg-vanilla/40 border border-sand/40 w-fit">
                        <img src="{{ $product->image_url }}" class="w-14 h-16 rounded-lg object-cover border border-sand/50" alt="{{ $product->name }}">
                        <div class="text-[11px] text-mocha">
                            <span>الصورة الحالية للقطعة محفوظة بنجاح.</span>
                            <span class="block text-taupe">اختر ملفاً جديداً لاستبدالها.</span>
                        </div>
                    </div>
                @endif

                <input type="file" 
                       id="image" 
                       name="image" 
                       accept="image/*"
                       class="w-full px-3 py-2 rounded-xl border border-sand/60 bg-vanilla/20 text-espresso text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-action file:text-action-ink hover:file:bg-espresso cursor-pointer @error('image') border-rose-500 @enderror">
                <p class="text-[11px] text-taupe mt-1">يُفضل رفع صور ذات خلفية نظيفة ونسبة عرض إلى ارتفاع (3:4).</p>
                @error('image')
                    <p class="text-rose-600 text-[11px] mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-6 pt-2">
                <div class="flex items-center gap-2">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $product->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-action border-sand/80 focus:ring-action">
                    <label for="is_active" class="text-xs font-bold text-espresso cursor-pointer">
                        تفعيل المنتج وعرضه للشراء في المتجر
                    </label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" 
                           id="featured" 
                           name="featured" 
                           value="1" 
                           {{ old('featured', $product->featured ? '1' : '0') == '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-action border-sand/80 focus:ring-action">
                    <label for="featured" class="text-xs font-bold text-espresso cursor-pointer">
                        تمييز المنتج في الواجهة الرئيسية (Featured Piece)
                    </label>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="pt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.products.index') }}" 
               class="px-5 py-2.5 rounded-xl bg-linen hover:bg-beige text-espresso text-xs font-semibold border border-sand/60 transition-all">
                إلغاء
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold shadow-sm transition-all cursor-pointer">
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection