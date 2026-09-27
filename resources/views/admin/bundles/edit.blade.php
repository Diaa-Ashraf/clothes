@extends('layouts.admin')

@section('title', __('تعديل العرض المجمع'))

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
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
            <div class="lg:col-span-7 space-y-6">
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('سعر الباقة الإجمالي') }} (ج.م) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" name="bundle_price" class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 font-black text-xs focus:outline-none" value="{{ old('bundle_price', $bundle->bundle_price) }}" required>
                                <span class="absolute left-3 top-2.5 text-xs text-emerald-600 font-bold">ج.م</span>
                            </div>
                            <small class="text-[11px] text-gray-400 mt-1 block font-medium">السعر الأصلي: {{ number_format($bundle->original_price, 2) }} ج.م (خصم: {{ $bundle->discount_percent }}%)</small>
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

            {{-- Right column: Products selector --}}
            <div class="lg:col-span-5">
                <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-7 shadow-xs space-y-5">
                    <div>
                        <h3 class="font-black text-gray-900 text-sm flex items-center gap-2">
                            <span>🛍️</span>
                            <span>2. المنتجات المشمولة في الباقة</span>
                        </h3>
                        <p class="text-xs text-gray-400 mt-1 font-medium">يمكنك تعديل المنتجات المرتبطة بهذا العرض.</p>
                    </div>

                    @php
                        $selectedProducts = $bundle->products->pluck('id')->toArray();
                    @endphp

                    <div class="space-y-4">
                        @for($i = 0; $i < 3; $i++)
                            @php
                                $currentId = $selectedProducts[$i] ?? null;
                            @endphp
                            <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100">
                                <label class="block text-xs font-bold {{ $i < 2 ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-wider mb-2">
                                    المنتج رقم {{ $i + 1 }} {{ $i < 2 ? '*' : '(اختياري)' }}
                                </label>
                                <select name="products[{{ $i }}][id]" class="w-full px-3.5 py-2.5 rounded-xl border border-orange-100 bg-white text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold" {{ $i < 2 ? 'required' : '' }}>
                                    <option value="">-- {{ $i < 2 ? 'اختر المنتج' : 'لا يوجد منتج' }} --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}" {{ $currentId == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ number_format($p->price, 2) }} ج.م)
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="products[{{ $i }}][quantity]" value="1">
                            </div>
                        @endfor
                    </div>

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
