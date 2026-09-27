@extends('layouts.admin')

@section('title', 'كتالوج المنتجات والمخزون')

@section('content')
<div class="space-y-6">
    
    <!-- Top Header -->
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-black text-orange-600 uppercase tracking-wider">سوبرماركت وألبان التوحيد بالعامرية</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900">
                كتالوج المنتجات والمخزون
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                إدارة الأصناف والأوزان، تحديد المنتجات المميزة، وتحديث كميات المخزون والأسعار.
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-2 shrink-0 cursor-pointer">
            <span>+</span>
            <span>إضافة صنف جديد</span>
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-white border border-orange-100 rounded-2xl p-4 sm:p-6 shadow-xs">
        <form method="GET" action="{{ route('admin.products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <div class="lg:col-span-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث باسم المنتج..." 
                       class="w-full px-4 py-2 text-xs rounded-xl bg-canvas-base border border-orange-100 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white">
            </div>

            <div class="lg:col-span-3">
                <select name="category_id" class="w-full px-3 py-2 text-xs rounded-xl bg-canvas-base border border-orange-100 text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white">
                    <option value="">-- جميع الأقسام والتصنيفات --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="lg:col-span-3">
                <select name="featured" class="w-full px-3 py-2 text-xs rounded-xl bg-canvas-base border border-orange-100 text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white">
                    <option value="">-- كل المنتجات --</option>
                    <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>⭐ المميزة فقط (Featured)</option>
                </select>
            </div>

            <div class="lg:col-span-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition-all shadow-xs cursor-pointer">
                    تصفية
                </button>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white border border-orange-100 rounded-3xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-canvas-base text-gray-500 font-bold border-b border-orange-100">
                    <tr>
                        <th class="py-3.5 px-6 text-right">الصورة والمنتج</th>
                        <th class="py-3.5 px-4 text-right">القسم</th>
                        <th class="py-3.5 px-4 text-right">السعر الأساسي</th>
                        <th class="py-3.5 px-4 text-right">سعر الخصم</th>
                        <th class="py-3.5 px-4 text-right">المخزون / نوع البيع</th>
                        <th class="py-3.5 px-4 text-center">المميز بالرئيسية</th>
                        <th class="py-3.5 px-6 text-left">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-orange-50/40 transition-colors">
                            <td class="py-4 px-6 flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-canvas-base border border-orange-100 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover rounded-lg">
                                </div>
                                <div class="min-w-0">
                                    <span class="font-bold text-gray-900 truncate block max-w-xs text-xs">{{ $product->name }}</span>
                                    <span class="text-[11px] text-gray-500 truncate block max-w-xs">{{ Str::limit($product->description, 40) }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-orange-50 border border-orange-200 text-[11px] font-bold text-orange-800">
                                    {{ $product->category?->name ?? 'غير مصنف' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-black text-gray-900 tabular-nums">
                                {{ $product->formatted_price }}
                            </td>
                            <td class="py-4 px-4">
                                @if($product->has_discount)
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-black text-red-600 tabular-nums">{{ format_currency($product->final_price) }}</span>
                                        <span class="px-1.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-600 text-[10px] font-black">-{{ $product->discount_percentage }}%</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <div class="space-y-1">
                                    @if($product->available_stock > 5)
                                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold block w-fit">
                                            {{ $product->available_stock }} قطعة
                                        </span>
                                    @elseif($product->available_stock > 0)
                                        <span class="px-2.5 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-[10px] font-bold block w-fit">
                                            {{ $product->available_stock }} منخفض
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-bold block w-fit">
                                            نفدت الكمية
                                        </span>
                                    @endif

                                    @if($product->is_weighted)
                                        <span class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-800 text-[9px] font-black block w-fit">
                                            كيلو / أوزان
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <form action="{{ route('admin.products.toggle-featured', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-full text-[11px] font-bold transition-all cursor-pointer {{ $product->featured ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 border border-gray-200 hover:bg-orange-50 hover:text-red-700' }}">
                                        {{ $product->featured ? '⭐ مميز' : 'عادي' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-left">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="px-3 py-1.5 rounded-lg border border-orange-200 bg-orange-50 hover:bg-orange-100 text-red-700 font-bold text-xs transition-colors">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" data-confirm-message="هل أنت متأكد من رغبتك في حذف المنتج: {{ $product->name }}؟">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold text-xs transition-colors cursor-pointer">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                لا توجد منتجات مسجلة. اضغط على "إضافة صنف جديد" لإضافة أول منتج للمتجر.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-orange-100 bg-canvas-base/40">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
