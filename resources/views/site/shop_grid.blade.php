@extends('layouts.site')

@section('content')
<div class="bg-[#F8F9FA] min-h-[85vh] py-6 sm:py-8 text-[#18181B]" 
 x-data="{ 
 cols: 4, 
 mobileFiltersOpen: false,
 sections: {
 categories: true,
 price: true,
 status: true
 }
 }">
 <div class="w-full max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
 
 {{-- Top Bar Controls: Product Counts, Sorting & Grid Switcher (Marketchino Style) --}}
 <div class="bg-white border border-[#E5E7EB] rounded-2xl p-3 sm:p-5 mb-6 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
 
 {{-- Right: Breadcrumb and Count --}}
 <div class="flex items-center justify-between sm:justify-start gap-2 sm:gap-3 flex-wrap">
 <nav class="text-xs text-[#71717A] flex items-center gap-1.5 sm:gap-2">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors">{{ __('الرئيسية') }}</a>
 <span>/</span>
 <span class="text-[#18181B] font-semibold">{{ __('المتجر') }}</span>
 </nav>
 <span class="text-gray-300 hidden sm:inline">|</span>
 <div class="text-xs text-[#71717A] font-medium tabular-nums">
 @if(!empty($filters['bundles_only']))
 {{ __('باقة') }} <span class="font-bold text-[#18181B]">{{ $bundles->count() }}</span> {{ __('متاحة') }}
 @elseif($products && $products->total() > 0)
 {{ __('منتج') }} <span class="font-bold text-[#18181B]">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> {{ __('من') }} <span class="font-bold text-[#18181B]">{{ $products->total() }}</span>
 @else
 {{ __('0 منتج') }}
 @endif
 </div>
 </div>

 {{-- Left: View Switcher, Sort Dropdown & Mobile Filter Button --}}
 <div class="flex items-center justify-between sm:justify-end gap-2 sm:gap-3 w-full sm:w-auto">
 
 {{-- Sort Dropdown --}}
 <form id="sortForm" action="{{ route('product.shop') }}" method="GET" class="flex items-center gap-1.5 sm:gap-2 flex-1 sm:flex-initial">
 {{-- Preserve existing filters --}}
 @if(!empty($filters['search'])) <input type="hidden" name="search" value="{{ $filters['search'] }}"> @endif
 @if(!empty($filters['min_price'])) <input type="hidden" name="min_price" value="{{ $filters['min_price'] }}"> @endif
 @if(!empty($filters['max_price'])) <input type="hidden" name="max_price" value="{{ $filters['max_price'] }}"> @endif
 @if(!empty($filters['featured'])) <input type="hidden" name="featured" value="1"> @endif
 @if(!empty($filters['discount_only'])) <input type="hidden" name="discount_only" value="1"> @endif
 @if(!empty($selectedCategories))
 @foreach($selectedCategories as $cId)
 <input type="hidden" name="categories[]" value="{{ $cId }}">
 @endforeach
 @endif

 <div class="flex items-center gap-1.5 text-xs text-[#71717A] w-full sm:w-auto">
 <span class="font-medium whitespace-nowrap hidden xs:inline">{{ __('فرز:') }}</span>
 <select name="sort" 
 onchange="document.getElementById('sortForm').submit()"
 class="w-full sm:w-auto bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl px-2.5 sm:px-3 py-1.5 text-xs font-semibold text-[#18181B] focus:outline-none focus:border-[#C5A059] cursor-pointer">
 <option value="latest" {{ ($filters['sort'] ?? '') === 'latest' ? 'selected' : '' }}>{{ __('الأحدث أولاً') }}</option>
 <option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>{{ __('السعر: الأقل إلى الأعلى') }}</option>
 <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>{{ __('السعر: الأعلى إلى الأقل') }}</option>
 <option value="discount" {{ ($filters['sort'] ?? '') === 'discount' ? 'selected' : '' }}>{{ __('أكبر نسبة تخفيض') }}</option>
 </select>
 </div>
 </form>

 {{-- Grid Display Switcher (2, 3, 4 columns) matching Marketchino toolbar --}}
 <div class="hidden md:flex items-center gap-1 bg-[#F8F9FA] border border-[#E5E7EB] p-1 rounded-xl">
 <button type="button" 
 @click="cols = 2" 
 :class="cols === 2 ? 'bg-white text-[#C5A059] shadow-xs' : 'text-gray-400 hover:text-[#18181B]'"
 class="p-1.5 rounded-lg transition-all" 
 title="{{ __('عرض عمودين') }}">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
 <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
 </svg>
 </button>
 <button type="button" 
 @click="cols = 3" 
 :class="cols === 3 ? 'bg-white text-[#C5A059] shadow-xs' : 'text-gray-400 hover:text-[#18181B]'"
 class="p-1.5 rounded-lg transition-all" 
 title="{{ __('عرض 3 أعمدة') }}">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
 <path d="M2 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H3a1 1 0 01-1-1V4zM2 10a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H3a1 1 0 01-1-1v-3zM2 16a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H3a1 1 0 01-1-1v-3zM8 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H9a1 1 0 01-1-1V4zM8 10a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H9a1 1 0 01-1-1v-3zM8 16a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H9a1 1 0 01-1-1v-3zM14 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1V4zM14 10a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3zM14 16a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-3z"/>
 </svg>
 </button>
 <button type="button" 
 @click="cols = 4" 
 :class="cols === 4 ? 'bg-white text-[#C5A059] shadow-xs' : 'text-gray-400 hover:text-[#18181B]'"
 class="p-1.5 rounded-lg transition-all" 
 title="{{ __('عرض 4 أعمدة') }}">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
 <path d="M3 3h4v4H3zm6 0h4v4H9zm6 0h4v4h-4zm6 0h4v4h-4zM3 9h4v4H3zm6 0h4v4H9zm6 0h4v4h-4zm6 0h4v4h-4zM3 15h4v4H3zm6 0h4v4H9zm6 0h4v4h-4zm6 0h4v4h-4z"/>
 </svg>
 </button>
 </div>

 {{-- Mobile Filter Trigger --}}
 <button type="button" 
 @click="mobileFiltersOpen = true"
 class="lg:hidden flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#C5A059] hover:bg-[#18181B] text-white text-xs font-bold shadow-xs transition-colors shrink-0">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
 </svg>
 <span>{{ __('الفلاتر') }}</span>
 </button>
 </div>
 </div>

 {{-- Main Content Layout (Sidebar on Right in RTL, Products Grid on Left) --}}
 <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
 
 {{-- Products & Bundles Grid Section --}}
 <div class="lg:col-span-3 space-y-6 order-2 lg:order-1">
 
 {{-- If Bundles Filter Active --}}
 @if(!empty($filters['bundles_only']))
 {{-- Only show Bundles --}}
 @if(isset($bundles) && $bundles->count() > 0)
 <div class="space-y-4">
 <div class="flex items-center justify-between pb-2 border-b border-gray-200">
 <h3 class="font-royal text-base sm:text-lg font-bold text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('الباقات الموفرة والعروض المجمعة') }}</span>
 </h3>
 <span class="text-xs text-[#71717A] tabular-nums font-semibold">({{ $bundles->count() }} {{ __('باقات متاحة') }})</span>
 </div>

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 @foreach($bundles as $bundle)
 <x-bundle-card :bundle="$bundle" />
 @endforeach
 </div>
 </div>
 @else
 <div class="bg-white border border-[#E5E7EB] rounded-2xl p-12 text-center space-y-4 shadow-xs">
 <div class="text-4xl"></div>
 <h3 class="text-base font-bold text-[#18181B]">{{ __('لا توجد باقات موفرة تطابق الفلاتر حالياً') }}</h3>
 <div>
 <a href="{{ route('product.shop') }}" class="inline-block px-6 py-2.5 rounded-full bg-[#C5A059] hover:bg-[#18181B] text-white text-xs font-bold shadow-xs transition-colors">
 {{ __('عرض كافة المنتجات') }}
 </a>
 </div>
 </div>
 @endif
 @else
 @if(isset($products) && $products->count() > 0)
 {{-- Grid responsive container (Default: 4 cards per row on desktop, 2 on mobile) --}}
 <div class="grid grid-cols-2 gap-3 sm:gap-4"
 :class="{
 'md:grid-cols-2': cols === 2,
 'md:grid-cols-3': cols === 3,
 'md:grid-cols-4': cols === 4
 }">
 @foreach($products as $product)
 <x-product-card :product="$product" />
 @endforeach
 </div>

 {{-- Clean Pagination --}}
 @if($products->hasPages())
 <div class="p-4 bg-white border border-[#E5E7EB] rounded-2xl shadow-xs flex justify-center">
 {{ $products->appends(request()->query())->links() }}
 </div>
 @endif
 @else
 <div class="bg-white border border-[#E5E7EB] rounded-2xl p-12 text-center space-y-4 shadow-xs">
 <div class="text-4xl"></div>
 <h3 class="text-base font-bold text-[#18181B]">{{ __('لم يتم العثور على منتجات تطابق الفلاتر المختارة') }}</h3>
 <p class="text-xs text-[#71717A] max-w-sm mx-auto">{{ __('يرجى تجربة تعديل خيارات الأسعار أو إزالة تحديد بعض الأقسام.') }}</p>
 <div>
 <a href="{{ route('product.shop') }}" 
 class="inline-block px-6 py-2.5 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white text-xs font-bold shadow-xs transition-colors">
 {{ __('عرض كافة المنتجات') }}
 </a>
 </div>
 </div>
 @endif
 @endif
 </div>

 {{-- Desktop Sidebar Filters (Marketchino Checkbox & Collapsible Style) --}}
 <aside class="hidden lg:block lg:col-span-1 bg-white border border-[#E5E7EB] rounded-2xl p-5 shadow-xs sticky top-28 order-1 lg:order-2">
 
 {{-- Header / Reset --}}
 <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
 <h2 class="font-bold text-sm text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('تصفية النتائج') }}</span>
 </h2>
 @if(!empty(array_filter($filters ?? [])))
 <a href="{{ route('product.shop') }}" class="text-xs text-[#C5A059] hover:underline font-bold">
 {{ __('إلغاء الفلاتر') }}
 </a>
 @endif
 </div>

 <form action="{{ route('product.shop') }}" method="GET" class="space-y-5">
 @if(!empty($filters['sort']))
 <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
 @endif

 {{-- Search Input --}}
 <div>
 <div class="relative">
 <input type="text" 
 name="search" 
 value="{{ $filters['search'] ?? '' }}"
 placeholder="{{ __('ابحث باسم المنتج...') }}" 
 class="w-full text-xs bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl ps-3 pe-8 py-2 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059] transition-colors">
 <button type="submit" class="absolute end-2.5 top-2.5 text-gray-400 hover:text-[#C5A059]">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
 </svg>
 </button>
 </div>
 </div>

 {{-- Section 1: Categories (Marketchino Checkbox List with Counts) --}}
 <div class="border-b border-gray-100 pb-4">
 <button type="button" 
 @click="sections.categories = !sections.categories"
 class="w-full flex items-center justify-between font-bold text-xs text-[#18181B] mb-2.5 select-none">
 <span>{{ __('الأقسام والتصنيفات') }}</span>
 <span class="text-gray-400 text-sm" x-text="sections.categories ? '−' : '+'"></span>
 </button>

 <div x-show="sections.categories" x-collapse class="space-y-2 max-h-56 overflow-y-auto no-scrollbar pt-1">
 @foreach($categories as $cat)
 @php
 $isChecked = in_array((string)$cat->id, array_map('strval', (array)($selectedCategories ?? [])));
 @endphp
 <label class="flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-[#F8F9FA] cursor-pointer transition-colors text-xs select-none group">
 <div class="flex items-center gap-2">
 <input type="checkbox" 
 name="categories[]" 
 value="{{ $cat->id }}" 
 {{ $isChecked ? 'checked' : '' }}
 onchange="this.form.submit()"
 class="w-4 h-4 rounded border-gray-300 text-[#C5A059] focus:ring-[#C5A059]">
 <span class="text-[#18181B] group-hover:text-[#C5A059] font-medium transition-colors">{{ $cat->name }}</span>
 </div>
 <span class="text-[10px] text-gray-400 tabular-nums">({{ $cat->active_products_count ?? 0 }})</span>
 </label>
 @endforeach
 </div>
 </div>

 {{-- Section 2: Special Badges (Bundles, Discounts & Featured) --}}
 <div class="border-b border-gray-100 pb-4">
 <button type="button" 
 @click="sections.status = !sections.status"
 class="w-full flex items-center justify-between font-bold text-xs text-[#18181B] mb-2.5 select-none">
 <span>{{ __('العروض والباقات') }}</span>
 <span class="text-gray-400 text-sm" x-text="sections.status ? '−' : '+'"></span>
 </button>

 <div x-show="sections.status" x-collapse class="space-y-2 pt-1">
 {{-- Bundles Offer Filter --}}
 <label class="flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-[#F8F9FA] cursor-pointer transition-colors text-xs select-none">
 <div class="flex items-center gap-2">
 <input type="checkbox" 
 name="bundles_only" 
 value="1" 
 {{ !empty($filters['bundles_only']) ? 'checked' : '' }}
 onchange="this.form.submit()"
 class="w-4 h-4 rounded border-gray-300 text-[#C5A059] focus:ring-[#C5A059]">
 <span class="font-bold text-[#C5A059]"> {{ __('باقات وعروض مجمعة') }}</span>
 </div>
 <span class="text-[10px] text-gray-400 tabular-nums">({{ isset($bundles) ? $bundles->count() : 0 }})</span>
 </label>

 {{-- Product Discounts --}}
 <label class="flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-[#F8F9FA] cursor-pointer transition-colors text-xs select-none">
 <div class="flex items-center gap-2">
 <input type="checkbox" 
 name="discount_only" 
 value="1" 
 {{ !empty($filters['discount_only']) ? 'checked' : '' }}
 onchange="this.form.submit()"
 class="w-4 h-4 rounded border-gray-300 text-[#C5A059] focus:ring-[#C5A059]">
 <span class="font-medium text-rose-600"> {{ __('تخفيضات المنتجات الفردية') }}</span>
 </div>
 </label>

 {{-- Featured Products --}}
 <label class="flex items-center justify-between gap-2 p-1.5 rounded-lg hover:bg-[#F8F9FA] cursor-pointer transition-colors text-xs select-none">
 <div class="flex items-center gap-2">
 <input type="checkbox" 
 name="featured" 
 value="1" 
 {{ !empty($filters['featured']) ? 'checked' : '' }}
 onchange="this.form.submit()"
 class="w-4 h-4 rounded border-gray-300 text-[#C5A059] focus:ring-[#C5A059]">
 <span class="font-medium text-[#18181B]">⭐ {{ __('منتجات مميزة') }}</span>
 </div>
 </label>
 </div>
 </div>

 {{-- Section 3: Price Filter (USD) --}}
 <div>
 <button type="button" 
 @click="sections.price = !sections.price"
 class="w-full flex items-center justify-between font-bold text-xs text-[#18181B] mb-2.5 select-none">
 <span>{{ __('نطاق السعر ($ USD)') }}</span>
 <span class="text-gray-400 text-sm" x-text="sections.price ? '−' : '+'"></span>
 </button>

 <div x-show="sections.price" x-collapse class="space-y-3 pt-1">
 <div class="grid grid-cols-2 gap-2">
 <div>
 <span class="text-[10px] text-gray-400 block mb-1">{{ __('من') }}</span>
 <input type="number" 
 name="min_price" 
 value="{{ $filters['min_price'] ?? '' }}" 
 placeholder="0" 
 class="w-full text-xs bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl px-2.5 py-1.5 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059]">
 </div>
 <div>
 <span class="text-[10px] text-gray-400 block mb-1">{{ __('إلى') }}</span>
 <input type="number" 
 name="max_price" 
 value="{{ $filters['max_price'] ?? '' }}" 
 placeholder="1000" 
 class="w-full text-xs bg-[#F8F9FA] border border-[#E5E7EB] rounded-xl px-2.5 py-1.5 text-[#18181B] placeholder-gray-400 focus:outline-none focus:border-[#C5A059]">
 </div>
 </div>
 
 <button type="submit" 
 class="w-full py-2 px-3 bg-[#C5A059] hover:bg-[#18181B] text-white font-bold text-xs rounded-xl shadow-xs transition-colors">
 {{ __('تطبيق السعر') }}
 </button>
 </div>
 </div>

 </form>
 </aside>

 </div>

 </div>

 {{-- Mobile Filter Drawer (Responsive Bottom Sheet) --}}
 <div x-show="mobileFiltersOpen" 
 class="fixed inset-0 z-50 overflow-hidden lg:hidden" 
 style="display: none;">
 <div x-show="mobileFiltersOpen"
 x-transition:enter="ease-out duration-300"
 x-transition:enter-start="opacity-0"
 x-transition:enter-end="opacity-100"
 x-transition:leave="ease-in duration-200"
 x-transition:leave-start="opacity-100"
 x-transition:leave-end="opacity-0"
 class="fixed inset-0 bg-black/60 backdrop-blur-xs" 
 @click="mobileFiltersOpen = false"></div>
 <div x-show="mobileFiltersOpen"
 x-transition:enter="transform transition ease-out duration-300"
 x-transition:enter-start="translate-y-full"
 x-transition:enter-end="translate-y-0"
 x-transition:leave="transform transition ease-in duration-200"
 x-transition:leave-start="translate-y-0"
 x-transition:leave-end="translate-y-full"
 class="fixed inset-x-0 bottom-0 max-h-[88vh] bg-white rounded-t-3xl p-5 sm:p-6 overflow-y-auto shadow-2xl flex flex-col z-50 border-t border-[#EADBCC]">
 <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
 <h3 class="font-bold text-sm text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('تصفية المنتجات') }}</span>
 </h3>
 <button @click="mobileFiltersOpen = false" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:text-gray-800 transition-colors"></button>
 </div>

 <form action="{{ route('product.shop') }}" method="GET" class="space-y-4">
 @if(!empty($filters['sort']))
 <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
 @endif

 <input type="text" 
 name="search" 
 value="{{ $filters['search'] ?? '' }}"
 placeholder="{{ __('ابحث بالاسم...') }}" 
 class="w-full text-xs bg-[#F8F9FA] border border-gray-200 rounded-xl px-3 py-2.5 text-[#18181B] focus:outline-none focus:border-[#C5A059]">

 <div>
 <h4 class="font-bold text-xs text-[#18181B] mb-2">{{ __('الأقسام والتصنيفات') }}</h4>
 <div class="space-y-1.5 max-h-48 overflow-y-auto no-scrollbar border border-gray-100 rounded-xl p-2 bg-[#F8F9FA]/50">
 @foreach($categories as $cat)
 @php
 $isChecked = in_array((string)$cat->id, array_map('strval', (array)($selectedCategories ?? [])));
 @endphp
 <label class="flex items-center justify-between text-xs p-1.5 rounded-lg hover:bg-white transition-colors cursor-pointer">
 <div class="flex items-center gap-2">
 <input type="checkbox" name="categories[]" value="{{ $cat->id }}" {{ $isChecked ? 'checked' : '' }} class="w-4 h-4 rounded text-[#C5A059] focus:ring-[#C5A059] cursor-pointer">
 <span class="font-medium text-[#18181B]">{{ $cat->name }}</span>
 </div>
 <span class="text-gray-400 text-[10px]">({{ $cat->active_products_count ?? 0 }})</span>
 </label>
 @endforeach
 </div>
 </div>

 <div class="pt-2 border-t border-gray-100 space-y-2">
 <label class="flex items-center gap-2 text-xs cursor-pointer p-1">
 <input type="checkbox" name="bundles_only" value="1" {{ !empty($filters['bundles_only']) ? 'checked' : '' }} class="w-4 h-4 rounded text-[#C5A059] focus:ring-[#C5A059]">
 <span class="font-bold text-[#C5A059]"> {{ __('باقات وعروض مجمعة فقط') }}</span>
 </label>
 <label class="flex items-center gap-2 text-xs cursor-pointer p-1">
 <input type="checkbox" name="discount_only" value="1" {{ !empty($filters['discount_only']) ? 'checked' : '' }} class="w-4 h-4 rounded text-[#C5A059] focus:ring-[#C5A059]">
 <span class="font-semibold text-rose-600"> {{ __('تخفيضات وعروض مميزة') }}</span>
 </label>
 </div>

 <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100">
 <div>
 <span class="text-[10px] text-gray-400 block mb-1">{{ __('أدنى سعر ($)') }}</span>
 <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="0" class="w-full text-xs bg-[#F8F9FA] border border-gray-200 rounded-xl px-3 py-2 text-[#18181B] focus:outline-none focus:border-[#C5A059]">
 </div>
 <div>
 <span class="text-[10px] text-gray-400 block mb-1">{{ __('أعلى سعر ($)') }}</span>
 <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="1000" class="w-full text-xs bg-[#F8F9FA] border border-gray-200 rounded-xl px-3 py-2 text-[#18181B] focus:outline-none focus:border-[#C5A059]">
 </div>
 </div>

 <div class="space-y-2 pt-3">
 <button type="submit" class="w-full py-3 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white font-bold text-xs shadow-sm transition-colors cursor-pointer">
 {{ __('عرض وتطبيق النتائج') }}
 </button>
 <a href="{{ route('product.shop') }}" class="block text-center py-2 text-xs text-gray-500 hover:text-rose-600 font-semibold transition-colors">
 {{ __('إعادة تعيين الفلاتر') }}
 </a>
 </div>
 </form>
 </div>
 </div>
</div>
@endsection