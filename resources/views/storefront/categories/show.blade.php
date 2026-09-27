{{-- resources/views/storefront/categories/show.blade.php --}}
@extends('layouts.storefront')

@section('title', $category->name . ' | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-8 bg-canvas-base min-h-screen">
 <div class="container mx-auto px-4 space-y-6">
 
 <!-- Breadcrumb -->
 <nav class="flex items-center gap-2 text-xs text-charcoal-muted">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <a href="{{ route('categories.index') }}" class="hover:text-primary-800 transition-colors">أقسام المتجر</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">{{ $category->name }}</span>
 </nav>

 <!-- Category Hero Banner -->
 <div class="bg-white rounded-3xl p-6 sm:p-8 border border-cream shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
 <div class="space-y-1 text-center sm:text-start">
 <span class="text-xs font-bold text-accent-700 uppercase tracking-wider block">قسم في ألبان وسوبرماركت التوحيد</span>
 <h1 class="text-2xl sm:text-3xl font-black text-charcoal-heading">{{ $category->name }}</h1>
 @if($category->description)
 <p class="text-xs sm:text-sm text-charcoal-muted max-w-xl leading-relaxed">{{ $category->description }}</p>
 @endif
 </div>

 <div class="flex items-center gap-3">
 <span class="px-4 py-2 rounded-xl bg-canvas-base border border-cream text-xs font-bold text-charcoal">
 {{ $products->total() }} منتج متاح
 </span>
 </div>
 </div>

 <!-- 2 Column Layout (Sidebar Filters + Products Grid) -->
 <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
 
 <!-- Filters Sidebar -->
 <aside class="space-y-6">
 <!-- Filters Card -->
 <div class="bg-white rounded-2xl p-5 border border-cream shadow-sm space-y-5">
 <div class="flex items-center justify-between border-b border-cream pb-3">
 <h3 class="font-bold text-sm text-charcoal-heading">تصفية المنتجات</h3>
 @if(request()->hasAny(['deals_only', 'in_stock', 'min_price', 'max_price', 'sort']))
 <a href="{{ route('category.product', $category->id) }}" class="text-xs text-rose-600 hover:underline">
 مسح الفلاتر
 </a>
 @endif
 </div>

 <form action="{{ route('category.product', $category->id) }}" method="GET" class="space-y-4 text-xs">
 <!-- Quick Checkboxes -->
 <div class="space-y-2">
 <label class="flex items-center gap-2 cursor-pointer">
 <input type="checkbox" name="deals_only" value="1" {{ request('deals_only') ? 'checked' : '' }}
 onchange="this.form.submit()"
 class="w-4 h-4 rounded text-primary-800 focus:ring-primary-500 border-cream">
 <span class="font-bold text-charcoal">عروض وخصومات فقط </span>
 </label>

 <label class="flex items-center gap-2 cursor-pointer">
 <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
 onchange="this.form.submit()"
 class="w-4 h-4 rounded text-primary-800 focus:ring-primary-500 border-cream">
 <span class="font-bold text-charcoal">متوفر بالمخزون فقط</span>
 </label>
 </div>

 <!-- Price Range Filter -->
 <div class="pt-3 border-t border-cream space-y-2">
 <span class="font-bold text-charcoal-heading block">نطاق السعر (ج.م)</span>
 <div class="grid grid-cols-2 gap-2">
 <div>
 <label class="text-[10px] text-charcoal-muted block mb-1">من</label>
 <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0"
 class="w-full bg-canvas-base border border-cream rounded-lg px-2.5 py-1.5 text-xs text-charcoal outline-none">
 </div>
 <div>
 <label class="text-[10px] text-charcoal-muted block mb-1">إلى</label>
 <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="1000"
 class="w-full bg-canvas-base border border-cream rounded-lg px-2.5 py-1.5 text-xs text-charcoal outline-none">
 </div>
 </div>
 </div>

 <!-- Sort Order -->
 <div class="pt-3 border-t border-cream space-y-2">
 <span class="font-bold text-charcoal-heading block">ترتيب حسب</span>
 <select name="sort" onchange="this.form.submit()" class="w-full bg-canvas-base border border-cream rounded-lg px-3 py-2 text-xs text-charcoal outline-none">
 <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>الأحدث أولاً</option>
 <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
 <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
 <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>أبجدياً (أ - ي)</option>
 </select>
 </div>

 <button type="submit" class="w-full py-2 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs transition-colors shadow-xs">
 تطبيق الفلاتر
 </button>
 </form>
 </div>

 <!-- Other Categories Navigation -->
 <div class="bg-white rounded-2xl p-5 border border-cream shadow-sm space-y-3">
 <h3 class="font-bold text-sm text-charcoal-heading border-b border-cream pb-2">أقسام أخرى</h3>
 <div class="space-y-1 text-xs">
 @foreach($otherCategories as $otherCat)
 <a href="{{ route('category.product', $otherCat->id) }}" 
 class="flex items-center justify-between py-1.5 px-2 rounded-lg transition-colors {{ $otherCat->id == $category->id ? 'bg-primary-50 text-primary-800 font-bold' : 'text-charcoal hover:bg-canvas-subtle' }}">
 <span>{{ $otherCat->name }}</span>
 <span class="text-[10px] text-charcoal-muted font-normal">{{ $otherCat->products_count }}</span>
 </a>
 @endforeach
 </div>
 </div>
 </aside>

 <!-- Main Products Grid -->
 <main class="lg:col-span-3 space-y-6">
 @if($products->isNotEmpty())
 <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
 @foreach($products as $product)
 <x-storefront.ui.product-card :product="$product" />
 @endforeach
 </div>

 @if($products->hasPages())
 <div class="pt-6">
 {{ $products->links() }}
 </div>
 @endif
 @else
 <div class="bg-white rounded-3xl p-12 border border-cream text-center space-y-3 shadow-sm">
 <span class="text-4xl block"></span>
 <h3 class="text-base font-bold text-charcoal-heading">لا توجد منتجات مطابقة في هذا القسم حالياً</h3>
 <p class="text-xs text-charcoal-muted max-w-sm mx-auto">
 جرب تغيير خيارات الفلترة أو تصفح باقي أقسام سوبرماركت التوحيد.
 </p>
 <a href="{{ route('category.product', $category->id) }}" class="inline-block px-5 py-2.5 rounded-xl bg-primary-800 text-white font-bold text-xs">
 عرض كل منتجات القسم
 </a>
 </div>
 @endif
 </main>

 </div>

 </div>
</div>
@endsection
