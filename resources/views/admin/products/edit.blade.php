@extends('layouts.admin')

@section('title', __('تعديل المنتج'))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('تعديل بيانات المنتج') }}</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('تحديث مواصفات وبيانات المنتج') }}: <span class="font-bold text-red-600">{{ $product->name }}</span></p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-900 text-xs font-bold transition-colors">
            <span>&rarr;</span>
            <span>{{ __('العودة لقائمة المنتجات') }}</span>
        </a>
    </div>

    {{-- Error Alert --}}
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

    {{-- Main Form Card --}}
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6"
              x-data="{ 
                  isWeighted: {{ old('is_weighted', $product->is_weighted) ? 'true' : 'false' }}, 
                  basePrice: parseFloat('{{ old('price', $product->price) }}') || 0,
                  unitType: '{{ old('unit_type', $product->unit_type ?? 'kg') }}'
              }">
            @csrf
            @method('PUT')

            {{-- Category Selection --}}
            <div class="p-5 rounded-2xl bg-canvas-base border border-orange-100">
                <label for="category_id" class="block text-xs font-bold text-gray-900 mb-2">
                    {{ __('التصنيف والقسم الرئيسي') }} <span class="text-red-500">*</span>
                </label>
                <select id="category_id" name="category_id" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-white text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all @error('category_id') border-red-500 @enderror" required>
                    <option value="">-- {{ __('اختر التصنيف') }} --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
                            🏷️ {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Product Name --}}
            <div>
                <label for="name" class="block text-xs font-bold text-gray-900 mb-2">
                    {{ __('اسم المنتج الكامل') }} <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" 
                       class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all @error('name') border-red-500 @enderror" 
                       value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Product Description --}}
            <div>
                <label for="description" class="block text-xs font-bold text-gray-900 mb-2">
                    {{ __('وصف وتفاصيل المنتج') }}
                </label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-medium transition-all @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Weighted Product & Slicing Settings Card --}}
            <div class="p-6 rounded-3xl transition-all duration-200 border-2"
                 :class="isWeighted ? 'bg-gradient-to-br from-amber-50/90 via-orange-50/50 to-amber-50/90 border-orange-300 shadow-sm' : 'bg-canvas-base border-orange-100'">
                
                {{-- Toggle Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">⚖️</span>
                            <h4 class="font-black text-sm text-gray-900">
                                {{ __('إعدادات البيع بالوزن والتقطيع (للأجبان واللحوم والألبان)') }}
                            </h4>
                            <span class="text-[11px] font-black px-2.5 py-0.5 rounded-full"
                                  :class="isWeighted ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-600'"
                                  x-text="isWeighted ? '✓ مفعّل للبيع بالوزن' : 'معطّل (بيع بالقطعة)'">
                            </span>
                        </div>
                        <p class="text-xs text-gray-600 font-medium">
                            {{ __('تفعيل هذا الخيار يمكن العملاء من اختيار الأوزان (ثمن، ربع، نصف، كيلو) أو شراء بمبلغ مالي مخصص (مثال: جبنة بـ 20 ج.م) واختيار طريقة التقطيع.') }}
                        </p>
                    </div>

                    {{-- Switch Control --}}
                    <div class="flex items-center gap-3">
                        <input type="hidden" name="is_weighted" :value="isWeighted ? '1' : '0'">
                        <button type="button" 
                                @click="isWeighted = !isWeighted"
                                class="relative inline-flex h-7 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                :class="isWeighted ? 'bg-orange-600' : 'bg-gray-300'">
                            <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                  :class="isWeighted ? 'translate-x-0 rtl:-translate-x-7' : 'translate-x-7 rtl:translate-x-0'"></span>
                        </button>
                    </div>
                </div>

                {{-- Expanded Weighted Details (Visible when isWeighted is true) --}}
                <div x-show="isWeighted" x-transition class="space-y-5 pt-5 mt-5 border-t border-orange-200">
                    
                    {{-- Unit Type & Instructions --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="unit_type" class="block text-xs font-bold text-amber-950 mb-1.5">
                                {{ __('وحدة القياس والتسعير الأساسية') }} <span class="text-red-500">*</span>
                            </label>
                            <select id="unit_type" name="unit_type" x-model="unitType" class="w-full px-4 py-2.5 rounded-xl border border-orange-200 bg-white text-xs font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                                <option value="kg">كيلوجرام (كجم) - السعر هو سعر الكيلو الكامل (الافتراضي للأجبان)</option>
                                <option value="gram">جرام</option>
                                <option value="piece">قطعة / قالب</option>
                                <option value="liter">لتر</option>
                            </select>
                        </div>
                        <div class="flex items-center text-xs text-amber-950 bg-white/90 p-3.5 rounded-2xl border border-orange-200 font-medium">
                            <span>💡 يرجى كتابة <strong>سعر الكيلو الكامل</strong> في خانة "السعر الأساسي" بالأسفل، وسيتكفل المتجر بحساب أسعار الربع والثمن والـ 20 ج.م تلقائياً للعميل.</span>
                        </div>
                    </div>

                    {{-- Live Price Breakdown Cards --}}
                    <div>
                        <h5 class="text-xs font-black text-amber-950 mb-2.5 flex items-center gap-1.5">
                            <span>📊</span>
                            <span>{{ __('معاينة حية لأسعار الأوزان المحسوبة تلقائياً للعميل:') }}</span>
                        </h5>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-white p-3 rounded-2xl border border-orange-200 text-center shadow-xs">
                                <span class="text-[11px] font-bold text-gray-500 block">ثمن كيلو (125 جم)</span>
                                <span class="text-sm font-black text-red-600 block mt-1" x-text="(basePrice * 0.125).toFixed(2) + ' ج.م'">0.00 ج.م</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-orange-200 text-center shadow-xs">
                                <span class="text-[11px] font-bold text-gray-500 block">ربع كيلو (250 جم)</span>
                                <span class="text-sm font-black text-red-600 block mt-1" x-text="(basePrice * 0.25).toFixed(2) + ' ج.م'">0.00 ج.م</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-orange-200 text-center shadow-xs">
                                <span class="text-[11px] font-bold text-gray-500 block">نصف كيلو (500 جم)</span>
                                <span class="text-sm font-black text-red-600 block mt-1" x-text="(basePrice * 0.50).toFixed(2) + ' ج.م'">0.00 ج.م</span>
                            </div>
                            <div class="bg-white p-3 rounded-2xl border border-orange-200 text-center shadow-xs">
                                <span class="text-[11px] font-bold text-gray-500 block">كيلو كامل (1000 جم)</span>
                                <span class="text-sm font-black text-red-600 block mt-1" x-text="(basePrice * 1.0).toFixed(2) + ' ج.م'">0.00 ج.م</span>
                            </div>
                        </div>
                    </div>

                    {{-- Slicing Options Supported --}}
                    <div class="p-4 bg-white/80 rounded-2xl border border-orange-200">
                        <h5 class="text-xs font-black text-amber-950 mb-2 flex items-center gap-1.5">
                            <span>🔪</span>
                            <span>{{ __('خيارات التقطيع المتاحة للزبون في صفحة المنتج:') }}</span>
                        </h5>
                        <div class="flex flex-wrap gap-2 text-xs font-bold text-gray-700">
                            <span class="px-3 py-1 bg-amber-100/70 border border-amber-200 rounded-xl">🥪 شرائح رقيقة (ساندوتشات)</span>
                            <span class="px-3 py-1 bg-amber-100/70 border border-amber-200 rounded-xl">🧀 شرائح سميكة</span>
                            <span class="px-3 py-1 bg-amber-100/70 border border-amber-200 rounded-xl">🧱 قالب سليم (بدون تقطيع)</span>
                            <span class="px-3 py-1 bg-amber-100/70 border border-amber-200 rounded-xl">🥣 مكعبات / بشر</span>
                            <span class="px-3 py-1 bg-emerald-100/70 border border-emerald-200 text-emerald-900 rounded-xl">💵 شراء بقيمة مخصصة (مثال: بـ 20 ج.م)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Keywords --}}
            <div>
                <label for="keywords" class="block text-xs font-bold text-gray-900 mb-2">
                    {{ __('الكلمات المفتاحية للبحث') }}
                </label>
                @php
                    $keywordsVal = is_array($product->keywords) ? implode(', ', $product->keywords) : $product->keywords;
                @endphp
                <input type="text" id="keywords" name="keywords" 
                       class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-medium transition-all @error('keywords') border-red-500 @enderror" 
                       value="{{ old('keywords', $keywordsVal) }}" placeholder="ألبان، جبن، طازج">
                @error('keywords')
                    <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug & Image --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="slug" class="block text-xs font-bold text-gray-900 mb-2">
                        {{ __('الرابط الدائم (Slug)') }}
                    </label>
                    <input type="text" id="slug" name="slug" dir="ltr"
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all text-right @error('slug') border-red-500 @enderror" 
                           value="{{ old('slug', $product->slug) }}">
                    @error('slug')
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-xs font-bold text-gray-900 mb-2">
                        {{ __('صورة المنتج الرئيسية') }}
                    </label>
                    @if($product->image_url)
                        <div class="mb-3 flex items-center gap-3 p-2 bg-canvas-base rounded-2xl border border-orange-100">
                            <img src="{{ $product->image_url }}" class="w-14 h-14 rounded-xl object-cover border border-orange-200" alt="{{ $product->name }}">
                            <div>
                                <span class="text-xs font-bold text-gray-700 block">الصورة الحالية للمنتج</span>
                                <span class="text-[11px] text-gray-400 font-medium">يمكنك رفع صورة جديدة للاستبدال</span>
                            </div>
                        </div>
                    @endif
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-3.5 py-2 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-red-600 cursor-pointer @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pricing & Stock --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
                <div>
                    <label for="price" class="block text-xs font-bold text-gray-900 mb-2">
                        {{ __('السعر الأساسي (أو سعر الكيلو)') }} (ج.م) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" id="price" name="price" 
                               @input="basePrice = parseFloat($event.target.value) || 0"
                               class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-black transition-all @error('price') border-red-500 @enderror" 
                               value="{{ old('price', $product->price) }}" required>
                        <span class="absolute left-3 top-2.5 text-xs font-bold text-gray-400">ج.م</span>
                    </div>
                    @error('price')
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_price" class="block text-xs font-bold text-gray-900 mb-2">
                        {{ __('سعر الخصم الترويجي') }} (ج.م)
                    </label>
                    <div class="relative">
                        <input type="number" step="0.01" id="discount_price" name="discount_price" 
                               class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-black transition-all @error('discount_price') border-red-500 @enderror" 
                               value="{{ old('discount_price', $product->discount_price) }}">
                        <span class="absolute left-3 top-2.5 text-xs font-bold text-gray-400">ج.م</span>
                    </div>
                    @error('discount_price')
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_quantity" class="block text-xs font-bold text-gray-900 mb-2">
                        {{ __('المخزون المتوفر (الكمية / كجم)') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock_quantity" name="stock_quantity" 
                           class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-black transition-all @error('stock_quantity') border-red-500 @enderror" 
                           value="{{ old('stock_quantity', $product->stock_quantity ?? $product->quantity) }}" required>
                    @error('stock_quantity')
                        <p class="text-red-500 text-xs mt-1.5 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Flags & Switches --}}
            <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100 flex flex-wrap items-center gap-8">
                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="featured" value="1" class="w-4 h-4 rounded text-red-600 focus:ring-red-500 border-orange-200" {{ $product->featured ? 'checked' : '' }}>
                    <span class="text-xs font-bold text-gray-900">⭐ تمييز في الصفحة الرئيسية (Featured)</span>
                </label>

                <label class="inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-orange-200" {{ $product->is_active ? 'checked' : '' }}>
                    <span class="text-xs font-bold text-gray-900">✓ تفعيل المنتج بالمتجر مباشرة</span>
                </label>
            </div>

            {{-- Submit Actions --}}
            <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-800 text-xs font-bold transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all cursor-pointer">
                    {{ __('حفظ التعديلات') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection