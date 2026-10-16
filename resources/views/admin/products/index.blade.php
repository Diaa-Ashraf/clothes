@extends('layouts.admin')

@section('title', 'كتالوج منتجات الأزياء والمخزون')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </span>
                <span class="text-xs font-semibold text-clay uppercase tracking-wider">كتالوج الأزياء والملابس</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-espresso">
                كتالوج المنتجات والمخزون
            </h1>
            <p class="text-xs text-mocha mt-1">
                إدارة تشكيلات الملابس، المقاسات، درجات الألوان، ومتابعة مستويات المخزون والأسعار.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all shrink-0 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>إضافة قطعة أزياء جديدة</span>
        </a>
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

    <!-- Filter Card -->
    <div class="bg-cream border border-sand/40 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <div class="lg:col-span-3">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث بالاسم أو رمز SKU..." 
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
                <select name="gender" class="w-full px-3 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso focus:outline-none focus:border-action">
                    <option value="">-- الفئة (الجنس) --</option>
                    <option value="men" {{ request('gender') == 'men' ? 'selected' : '' }}>رجالي</option>
                    <option value="women" {{ request('gender') == 'women' ? 'selected' : '' }}>نسائي</option>
                    <option value="kids" {{ request('gender') == 'kids' ? 'selected' : '' }}>أطفال</option>
                    <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>للجنسين</option>
                </select>
            </div>

            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-3 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold transition-all shadow-xs cursor-pointer">
                    تصفية
                </button>
                @if(request()->hasAny(['search', 'category_id', 'brand_id', 'gender', 'featured']))
                    <a href="{{ route('admin.products.index') }}" class="px-2.5 py-2 text-xs text-mocha hover:text-espresso underline">إلغاء</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-cream border border-sand/40 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead class="bg-linen/60 text-mocha font-bold border-b border-sand/40 text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-right">القطعة والكود</th>
                        <th class="py-3 px-4 text-right">التصنيف والعلامة</th>
                        <th class="py-3 px-4 text-right">السعر</th>
                        <th class="py-3 px-4 text-center">المقاسات والتنويعات</th>
                        <th class="py-3 px-4 text-center">المخزون المتوفر</th>
                        <th class="py-3 px-4 text-center">المميز بالواجهة</th>
                        <th class="py-3 px-4 text-left">إجراءات</th>
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
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @if($product->sku)
                                            <span class="font-mono text-[10px] text-taupe dir-ltr text-right">{{ $product->sku }}</span>
                                        @endif
                                        @if($product->gender)
                                            <span class="text-[10px] text-clay font-medium">
                                                {{ match($product->gender) { 'men' => 'رجالي', 'women' => 'نسائي', 'kids' => 'أطفال', default => 'للجنسين' } }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex flex-col gap-1 items-start">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full bg-linen border border-sand/40 text-[10px] font-medium text-mocha">
                                        {{ $product->category?->name ?? 'غير مصنف' }}
                                    </span>
                                    @if($product->brand)
                                        <span class="text-[10px] text-espresso font-semibold flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-clay"></span>
                                            {{ $product->brand->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3.5 px-4 font-bold text-espresso tabular-nums">
                                @if($product->has_discount)
                                    <div>
                                        <span class="text-clay text-xs">{{ format_currency($product->final_price) }}</span>
                                        <span class="text-[10px] text-taupe line-through block">{{ format_currency($product->price) }}</span>
                                    </div>
                                @else
                                    <span>{{ format_currency($product->price) }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($product->variants_count > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-action/10 text-action text-[11px] font-bold border border-action/20">
                                        {{ $product->variants_count }} مقاس/لون
                                    </span>
                                @else
                                    <span class="text-[11px] text-taupe">قطعة مفردة</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($product->available_stock > 5)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold">
                                        {{ $product->available_stock }} قطعة
                                    </span>
                                @elseif($product->available_stock > 0)
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold">
                                        {{ $product->available_stock }} منخفض
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-bold">
                                        نفدت الكمية
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-all cursor-pointer {{ $product->featured ? 'bg-action text-action-ink shadow-xs' : 'bg-linen text-mocha border border-sand/60 hover:bg-beige' }}">
                                        {{ $product->featured ? '⭐ مميز' : 'عادي' }}
                                    </button>
                                </form>
                            </td>

                            <td class="py-3.5 px-4 text-left">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="p-1.5 rounded-lg hover:bg-beige text-mocha hover:text-espresso transition-all" 
                                       title="تعديل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف المنتج: {{ $product->name }}؟');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-50 text-rose-500 hover:text-rose-700 transition-all cursor-pointer" title="حذف">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-taupe">
                                لا توجد قطع أزياء مسجلة. اضغط على "إضافة قطعة أزياء جديدة" للبدء.
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

</div>
@endsection
