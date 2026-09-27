{{-- resources/views/storefront/products/shop.blade.php --}}
@extends('layouts.storefront')

@section('title', 'المتجر وتصفح المنتجات | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-8 bg-gradient-to-b from-orange-50/30 via-canvas-base to-canvas-base min-h-screen" 
     x-data="{ 
         mobileFiltersOpen: false,
         viewMode: 'grid',
         sort: '{{ request('sort', 'latest') }}'
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Breadcrumb & Top Indicator -->
        <nav class="flex items-center justify-between text-xs font-bold text-gray-500" aria-label="Breadcrumb">
            <div class="flex items-center gap-2">
                <a href="{{ route('site.home') }}" class="hover:text-red-600 transition-colors">الرئيسية</a>
                <span>/</span>
                <span class="text-gray-900 font-black">المتجر وتصفح المنتجات</span>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-primary-800 bg-white px-3 py-1 rounded-full border border-orange-200 shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>توصيل طازج يومياً لجميع أنحاء العامرية</span>
            </div>
        </nav>

        <!-- Premium Header Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-red-800 via-red-600 to-orange-500 text-white p-6 sm:p-10 shadow-xl border border-orange-300">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2 max-w-2xl">
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-amber-200 text-xs font-black tracking-wider uppercase">
                        سوبرماركت وألبان التوحيد • 4 فروع بالعامرية
                    </span>
                    <h1 class="text-2xl sm:text-4xl font-black tracking-tight leading-tight">
                        تصفح أجود منتجات الألبان والبقالة
                    </h1>
                    <p class="text-xs sm:text-sm text-white/90 leading-relaxed font-medium">
                        نوفر لكم تشكيلة واسعة من الألبان الفلاحي الطازجة، الأجبان الطبيعية، المواد الغذائية، والسلع الاستهلاكية بأسعار تنافسية وجودة مضمونة.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="bg-black/25 backdrop-blur-md border border-white/25 rounded-2xl p-4 text-center min-w-[140px]">
                        <span class="text-2xl sm:text-3xl font-black text-amber-300 block tabular-nums">{{ $products->total() }}</span>
                        <span class="text-[11px] text-gray-200 font-bold">صنف متوفر بالمتجر</span>
                    </div>
                </div>
            </div>

            <!-- Background subtle ornaments -->
            <div class="absolute -bottom-16 -left-16 w-60 h-60 bg-amber-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-16 -right-16 w-60 h-60 bg-red-500/30 rounded-full blur-3xl pointer-events-none"></div>
        </div>

        <!-- Horizontal Quick Categories Navigation Chips -->
        <div class="bg-white rounded-2xl p-3 border border-orange-100 shadow-xs flex items-center gap-2 overflow-x-auto no-scrollbar scroll-smooth">
            <a href="{{ route('site.products', array_merge(request()->except(['category_id', 'page']))) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black shrink-0 transition-all {{ !request('category_id') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-sm' : 'bg-gray-50 text-gray-700 hover:bg-orange-50 hover:text-red-700 border border-gray-100' }}">
                جميع الأقسام
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('site.products', array_merge(request()->except(['category_id', 'page']), ['category_id' => $cat->id])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold shrink-0 transition-all flex items-center gap-1.5 {{ request('category_id') == $cat->id ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-sm font-black' : 'bg-gray-50 text-gray-700 hover:bg-orange-50 hover:text-red-700 border border-gray-100' }}">
                    <span>{{ $cat->name }}</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('category_id') == $cat->id ? 'bg-white/25 text-white' : 'bg-gray-200 text-gray-600' }}">
                        {{ $cat->products_count }}
                    </span>
                </a>
            @endforeach
        </div>

        <!-- Active Filter Tags Bar -->
        @php
            $hasActiveFilters = request()->hasAny(['category_id', 'deals_only', 'featured_only', 'in_stock', 'min_price', 'max_price', 'sort', 'search']);
        @endphp
        @if($hasActiveFilters)
            <div class="bg-white rounded-2xl p-3.5 border border-orange-100 shadow-xs flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-black text-gray-900">الفلاتر المطبقة:</span>
                    
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-orange-50 text-orange-800 border border-orange-200 font-bold">
                            <span>بحث: "{{ request('search') }}"</span>
                            <a href="{{ route('site.products', request()->except('search')) }}" class="hover:text-red-600">✕</a>
                        </span>
                    @endif

                    @if(request('category_id'))
                        @php $activeCat = $categories->firstWhere('id', request('category_id')); @endphp
                        @if($activeCat)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-red-50 text-red-800 border border-red-200 font-bold">
                                <span>القسم: {{ $activeCat->name }}</span>
                                <a href="{{ route('site.products', request()->except('category_id')) }}" class="hover:text-red-600">✕</a>
                            </span>
                        @endif
                    @endif

                    @if(request('deals_only'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-red-50 text-red-800 border border-red-200 font-bold">
                            <span>العروض والخصومات فقط</span>
                            <a href="{{ route('site.products', request()->except('deals_only')) }}" class="hover:text-red-600">✕</a>
                        </span>
                    @endif

                    @if(request('in_stock'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold">
                            <span>المتوفر بالمخزون</span>
                            <a href="{{ route('site.products', request()->except('in_stock')) }}" class="hover:text-red-600">✕</a>
                        </span>
                    @endif

                    @if(request('min_price') || request('max_price'))
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-gray-100 text-gray-800 border border-gray-200 font-bold">
                            <span>السعر: {{ request('min_price', 0) }} - {{ request('max_price', '∞') }} ج.م</span>
                            <a href="{{ route('site.products', request()->except(['min_price', 'max_price'])) }}" class="hover:text-red-600">✕</a>
                        </span>
                    @endif
                </div>

                <a href="{{ route('site.products') }}" class="text-xs font-black text-red-600 hover:text-red-800 hover:underline">
                    إعادة ضبط ومسح الكل
                </a>
            </div>
        @endif

        <!-- Controls Toolbar: Mobile Filter Trigger & Sorting & Display View -->
        <div class="bg-white rounded-2xl p-4 border border-orange-100 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-start">
                <button type="button" 
                        @click="mobileFiltersOpen = true" 
                        class="lg:hidden flex items-center gap-2 px-4 py-2.5 rounded-xl bg-red-600 text-white font-black shadow-md hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>تصفية وفلاتر البحث</span>
                    @if($hasActiveFilters)
                        <span class="w-2 h-2 rounded-full bg-amber-300"></span>
                    @endif
                </button>

                <span class="text-gray-500 font-bold">
                    عرض <strong class="text-gray-900">{{ $products->count() }}</strong> من أصل <strong class="text-gray-900">{{ $products->total() }}</strong> منتج
                </span>
            </div>

            <!-- Sorting & Layout Toggle -->
            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                <div class="flex items-center gap-2">
                    <label for="sortSelect" class="font-bold text-gray-700 shrink-0">الترتيب:</label>
                    <select id="sortSelect" 
                            name="sort" 
                            onchange="window.location.href = this.value"
                            class="bg-gray-50 border border-orange-200 focus:border-red-500 rounded-xl px-3 py-2 text-xs font-bold text-gray-900 outline-none cursor-pointer">
                        <option value="{{ route('site.products', array_merge(request()->query(), ['sort' => 'latest'])) }}" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>الأحدث أولاً</option>
                        <option value="{{ route('site.products', array_merge(request()->query(), ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: الأقل إلى الأعلى</option>
                        <option value="{{ route('site.products', array_merge(request()->query(), ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: الأعلى إلى الأقل</option>
                        <option value="{{ route('site.products', array_merge(request()->query(), ['sort' => 'name'])) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>أبجدياً (أ - ي)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2 Column Master Layout (Filters Sidebar + Products Grid) -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
            
            <!-- Desktop Filter Sidebar -->
            <aside class="hidden lg:block lg:col-span-1 space-y-6 sticky top-24">
                <div class="bg-white rounded-3xl p-6 border-2 border-orange-100 shadow-sm space-y-6">
                    
                    <div class="flex items-center justify-between border-b border-orange-100 pb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                            </div>
                            <h3 class="font-black text-sm text-gray-900">تصفية المنتجات</h3>
                        </div>
                        @if($hasActiveFilters)
                            <a href="{{ route('site.products') }}" class="text-[11px] font-bold text-red-600 hover:underline">
                                مسح الكل
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('site.products') }}" method="GET" class="space-y-5 text-xs">
                        
                        <!-- Search Keyword inside Shop -->
                        <div class="space-y-1.5">
                            <label class="font-black text-gray-900 block">بحث باسم المنتج</label>
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="مثال: حليب، جبنة، أرز..." 
                                       class="w-full bg-gray-50 border border-gray-200 focus:border-red-600 focus:bg-white rounded-xl pr-9 pl-3 py-2.5 text-xs text-gray-900 outline-none transition-all">
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Categories List Radios -->
                        <div class="pt-4 border-t border-orange-100 space-y-2.5">
                            <label class="font-black text-gray-900 block">الأقسام والتصنيفات</label>
                            <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1">
                                <label class="flex items-center justify-between p-2 rounded-xl hover:bg-orange-50/60 cursor-pointer transition-colors {{ !request('category_id') ? 'bg-orange-50 font-bold text-red-700' : 'text-gray-700' }}">
                                    <div class="flex items-center gap-2">
                                        <input type="radio" 
                                               name="category_id" 
                                               value="" 
                                               onchange="this.form.submit()" 
                                               {{ !request('category_id') ? 'checked' : '' }}
                                               class="text-red-600 focus:ring-red-500">
                                        <span>جميع الأقسام</span>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $products->total() }}</span>
                                </label>
                                @foreach($categories as $cat)
                                    <label class="flex items-center justify-between p-2 rounded-xl hover:bg-orange-50/60 cursor-pointer transition-colors {{ request('category_id') == $cat->id ? 'bg-orange-50 font-bold text-red-700' : 'text-gray-700' }}">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" 
                                                   name="category_id" 
                                                   value="{{ $cat->id }}" 
                                                   onchange="this.form.submit()" 
                                                   {{ request('category_id') == $cat->id ? 'checked' : '' }}
                                                   class="text-red-600 focus:ring-red-500">
                                            <span>{{ $cat->name }}</span>
                                        </div>
                                        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ request('category_id') == $cat->id ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $cat->products_count }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Special Offers & Availability Switches -->
                        <div class="pt-4 border-t border-orange-100 space-y-3">
                            <label class="font-black text-gray-900 block">خيارات العرض والمخزون</label>
                            
                            <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all {{ request('deals_only') ? 'border-red-500 bg-red-50/40 text-red-900 font-bold' : 'border-gray-200 hover:border-orange-300' }}">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" 
                                           name="deals_only" 
                                           value="1" 
                                           {{ request('deals_only') ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="w-4 h-4 rounded text-red-600 focus:ring-red-500 border-gray-300">
                                    <span>عروض وخصومات فقط</span>
                                </div>
                                <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-red-600 text-white">توفير</span>
                            </label>

                            <label class="flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all {{ request('in_stock') ? 'border-emerald-500 bg-emerald-50/40 text-emerald-900 font-bold' : 'border-gray-200 hover:border-orange-300' }}">
                                <div class="flex items-center gap-2.5">
                                    <input type="checkbox" 
                                           name="in_stock" 
                                           value="1" 
                                           {{ request('in_stock') ? 'checked' : '' }}
                                           onchange="this.form.submit()"
                                           class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-gray-300">
                                    <span>المتوفر بالمخزون حالياً</span>
                                </div>
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            </label>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="pt-4 border-t border-orange-100 space-y-3">
                            <label class="font-black text-gray-900 block">نطاق السعر (جنيه مصري)</label>
                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="text-[10px] text-gray-500 font-bold block mb-1">الحد الأدنى</label>
                                    <input type="number" 
                                           name="min_price" 
                                           value="{{ request('min_price') }}" 
                                           placeholder="0" 
                                           min="0"
                                           class="w-full bg-gray-50 border border-gray-200 focus:border-red-600 focus:bg-white rounded-xl px-3 py-2 text-xs font-bold text-gray-900 outline-none">
                                </div>
                                <div>
                                    <label class="text-[10px] text-gray-500 font-bold block mb-1">الحد الأقصى</label>
                                    <input type="number" 
                                           name="max_price" 
                                           value="{{ request('max_price') }}" 
                                           placeholder="1000" 
                                           min="0"
                                           class="w-full bg-gray-50 border border-gray-200 focus:border-red-600 focus:bg-white rounded-xl px-3 py-2 text-xs font-bold text-gray-900 outline-none">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">

                        <!-- Submit Filter Button -->
                        <button type="submit" 
                                class="w-full py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-xs transition-all shadow-md shadow-orange-500/20 cursor-pointer">
                            تطبيق الفلترة والبحث
                        </button>
                    </form>

                </div>

                <!-- Hotline & Support Banner -->
                <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-3xl p-5 border border-orange-200 text-center space-y-2">
                    <span class="text-xs font-black text-red-900 block">محتاج طلبية خاصة أو كميات جملة؟</span>
                    <p class="text-[11px] text-gray-600 leading-relaxed">تواصل مع خدمة العملاء والطلبات المباشرة بفروع العامرية</p>
                    <a href="tel:{{ settings('store_phone', '01000000000') }}" class="inline-block px-4 py-2 rounded-xl bg-white border border-orange-200 text-red-700 font-black text-xs shadow-xs hover:bg-red-50 transition-colors" dir="ltr">
                        {{ settings('store_phone', '01000000000') }}
                    </a>
                </div>
            </aside>

            <!-- Products Grid Main Area -->
            <main class="lg:col-span-3 space-y-6">
                
                @if($products->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3.5 sm:gap-6">
                        @foreach($products as $product)
                            <x-storefront.ui.product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="pt-8 flex justify-center">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-3xl p-12 border border-orange-100 text-center space-y-4 shadow-sm">
                        <div class="w-16 h-16 mx-auto rounded-3xl bg-orange-50 text-orange-600 border border-orange-200 flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-lg font-black text-gray-900">لم يتم العثور على منتجات مطابقة</h3>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto leading-relaxed">
                                جرب البحث بكلمات أخرى أو قم بإلغاء بعض الفلاتر المحددة للوصول للأصناف المطلوبة.
                            </p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route('site.products') }}" 
                               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 text-white text-xs font-black shadow-md hover:from-red-700 hover:to-orange-600 transition-all">
                                <span>عرض كل المنتجات المتاحة</span>
                                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

            </main>

        </div>

    </div>

    <!-- Mobile Filters Slide-over Modal -->
    <div x-show="mobileFiltersOpen" 
         x-cloak 
         class="fixed inset-0 z-50 lg:hidden overflow-hidden" 
         aria-labelledby="slide-over-title" 
         role="dialog" 
         aria-modal="true">
        
        <div x-show="mobileFiltersOpen" 
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileFiltersOpen = false"
             class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs transition-opacity"></div>

        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-2xl flex flex-col z-50">
            <div class="p-4 bg-gradient-to-r from-red-700 to-orange-600 text-white flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    <h2 class="font-black text-sm">تصفية وبحث المنتجات</h2>
                </div>
                <button type="button" @click="mobileFiltersOpen = false" class="p-1.5 rounded-lg bg-white/20 text-white hover:bg-white/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-6">
                <form action="{{ route('site.products') }}" method="GET" class="space-y-5 text-xs">
                    <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">

                    <div class="space-y-1.5">
                        <label class="font-black text-gray-900 block">بحث باسم المنتج</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المنتج..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-900 outline-none">
                    </div>

                    <div class="pt-3 border-t border-gray-100 space-y-2">
                        <label class="font-black text-gray-900 block">القسم</label>
                        <select name="category_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-bold text-gray-900 outline-none">
                            <option value="">جميع الأقسام</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pt-3 border-t border-gray-100 space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="deals_only" value="1" {{ request('deals_only') ? 'checked' : '' }} class="w-4 h-4 rounded text-red-600 focus:ring-red-500">
                            <span class="font-bold text-gray-900">عروض وخصومات فقط</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                            <span class="font-bold text-gray-900">المتوفر بالمخزون فقط</span>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-gray-100 space-y-2">
                        <label class="font-black text-gray-900 block">نطاق السعر</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="من" class="bg-gray-50 border border-gray-200 rounded-lg p-2 text-xs">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="إلى" class="bg-gray-50 border border-gray-200 rounded-lg p-2 text-xs">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-3 rounded-xl bg-red-600 text-white font-black text-xs shadow-md">
                            عرض النتائج
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
