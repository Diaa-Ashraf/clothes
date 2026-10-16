{{-- resources/views/storefront/products/shop.blade.php --}}
@extends('layouts.storefront')

@section('title', 'متجر الملابس والأزياء المصرية | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-6 sm:py-8 bg-[#F9FAFB] min-h-screen" 
     x-data="{ 
         mobileFiltersOpen: false,
         sort: '{{ request('sort', 'latest') }}'
     }"
     dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- 1. Breadcrumb Navigation -->
        <nav class="flex items-center justify-between text-xs font-semibold text-gray-500 py-2 border-b border-gray-200" aria-label="Breadcrumb">
            <div class="flex items-center gap-2">
                <a href="{{ route('site.home') }}" class="hover:text-[#AC9653] transition-colors">الرئيسية</a>
                <span>/</span>
                <a href="{{ route('shop') }}" class="hover:text-[#AC9653] transition-colors">متجر الملابس</a>
                @if(request('category_id'))
                    @php
                        $currentCat = $categories->firstWhere('id', request('category_id'));
                    @endphp
                    @if($currentCat)
                        <span>/</span>
                        <span class="text-gray-900 font-bold">{{ $currentCat->name }}</span>
                    @endif
                @endif
                @if(request('search'))
                    <span>/</span>
                    <span class="text-gray-900 font-bold">نتائج البحث: "{{ request('search') }}"</span>
                @endif
            </div>
            
            <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-[#AC9653] bg-white px-3 py-1.5 rounded-lg border border-[#BDAB75]/30 shadow-2xs">
                <span>🇪🇬</span>
                <span>أسعار بالجنيه المصري • معاينة قبل الاستلام</span>
            </div>
        </nav>

        <!-- 2. Store Catalog Header Banner (Refined E-Commerce Style) -->
        <div class="relative overflow-hidden bg-gradient-to-l from-[#AC9653] via-[#BDAB75] to-[#C2B280] text-white p-3.5 sm:p-8 rounded-xl sm:rounded-2xl shadow-sm border border-[#BDAB75]/40">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-6 text-right">
                <div class="space-y-1 sm:space-y-2 max-w-2xl">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:px-3 sm:py-1 bg-white/20 backdrop-blur-xs rounded-full text-white text-[9.5px] sm:text-[11px] font-bold">
                        <span>{{ settings('site_name', 'أزياء لافيستا') }}</span>
                        <span>•</span>
                        <span>تشكيلة الموسم الجديد {{ date('Y') }}</span>
                    </div>
                    <h1 class="text-base sm:text-2xl md:text-3xl font-black text-white leading-tight">
                        كافة الملابس والأزياء المصرية العصرية
                    </h1>
                    <p class="text-[11px] sm:text-sm text-white/95 leading-relaxed font-normal">
                        تصفح أرقى الموديلات الرجالية، الحريمية، وأزياء الأطفال من أجود الخامات بأفضل الأسعار مع الشحن السريع لكافة المحافظات والدفع عند الاستلام.
                    </p>
                </div>

                <div class="flex items-center gap-3 shrink-0 self-start md:self-center">
                    <div class="bg-black/20 backdrop-blur-xs border border-white/25 rounded-xl px-3 py-1.5 sm:p-5 text-center flex md:flex-col items-center gap-2 md:gap-0 min-w-0 sm:min-w-[140px]">
                        <span class="text-base sm:text-3xl font-black text-white block tabular-nums">{{ $products->total() }}</span>
                        <span class="text-[10px] sm:text-xs text-white/90 font-medium">موديل متاح</span>
                    </div>
                </div>
            </div>
            <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <!-- 3. Active Filters Chips (If Any Filter Applied) -->
        @if(request('category_id') || request('search') || request('min_price') || request('max_price') || request('deals_only'))
            <div class="flex flex-wrap items-center gap-2 bg-white p-3.5 rounded-xl border border-gray-200 shadow-2xs text-xs">
                <span class="font-bold text-gray-700">الفلاتر المطبقة:</span>

                @if(request('category_id'))
                    @php $catMatch = $categories->firstWhere('id', request('category_id')); @endphp
                    @if($catMatch)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#AC9653]/15 text-[#AC9653] font-bold rounded-lg border border-[#AC9653]/30">
                            القسم: {{ $catMatch->name }}
                            <a href="{{ route('shop', request()->except(['category_id', 'page'])) }}" class="hover:text-red-500 font-black">×</a>
                        </span>
                    @endif
                @endif

                @if(request('deals_only'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#CBA367]/15 text-[#CBA367] font-bold rounded-lg border border-[#CBA367]/30">
                        العروض والتخفيضات فقط
                        <a href="{{ route('shop', request()->except(['deals_only', 'page'])) }}" class="hover:text-red-500 font-black">×</a>
                    </span>
                @endif

                @if(request('search'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-800 font-bold rounded-lg border border-gray-300">
                        بحث: "{{ request('search') }}"
                        <a href="{{ route('shop', request()->except(['search', 'page'])) }}" class="hover:text-red-500 font-black">×</a>
                    </span>
                @endif

                @if(request('min_price') || request('max_price'))
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-800 font-bold rounded-lg border border-gray-300">
                        السعر: {{ request('min_price', 0) }} إلى {{ request('max_price', 'الكل') }} ج.م
                        <a href="{{ route('shop', request()->except(['min_price', 'max_price', 'page'])) }}" class="hover:text-red-500 font-black">×</a>
                    </span>
                @endif

                <a href="{{ route('shop') }}" class="mr-auto text-xs font-bold text-red-600 hover:underline">
                    مسح كافة الفلاتر
                </a>
            </div>
        @endif

        <!-- 4. Main Two-Column Layout: Sidebar Filters + Products Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <!-- Desktop Filter Sidebar -->
            <aside class="hidden lg:block lg:col-span-1 space-y-6 bg-white p-5 rounded-2xl border border-gray-200 shadow-2xs sticky top-28">
                
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <h3 class="font-black text-sm text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#AC9653]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <span>تصفية الملابس</span>
                    </h3>
                    @if(request()->anyFilled(['category_id', 'min_price', 'max_price', 'deals_only', 'search']))
                        <a href="{{ route('shop') }}" class="text-[11px] font-bold text-red-600 hover:underline">
                            إعادة ضبط
                        </a>
                    @endif
                </div>

                <!-- 1. Categories Filter -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-800">أقسام الملابس</label>
                    <div class="space-y-1 max-h-72 overflow-y-auto pr-1">
                        <a href="{{ route('shop', request()->except(['category_id', 'page'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ !request('category_id') ? 'bg-[#AC9653] text-white font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span>كافة الأقسام</span>
                            <span class="text-[11px] {{ !request('category_id') ? 'text-white/90' : 'text-gray-400' }}">{{ $products->total() }}</span>
                        </a>

                        @foreach($categories as $cat)
                            <a href="{{ route('shop', array_merge(request()->except(['category_id', 'page']), ['category_id' => $cat->id])) }}" 
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold transition-colors {{ request('category_id') == $cat->id ? 'bg-[#AC9653] text-white font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="truncate">{{ $cat->name }}</span>
                                <span class="text-[11px] {{ request('category_id') == $cat->id ? 'text-white/90' : 'text-gray-400' }} bg-white/20 px-1.5 py-0.5 rounded">
                                    {{ $cat->products_count }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- 2. Price Range Filter in EGP (ج.م) -->
                <form action="{{ route('shop') }}" method="GET" class="pt-4 border-t border-gray-100 space-y-3">
                    @foreach(request()->except(['min_price', 'max_price', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    <label class="block text-xs font-bold text-gray-800">نطاق السعر (بالجنيه المصري)</label>
                    <div class="flex items-center gap-2">
                        <div class="flex-1">
                            <span class="text-[10px] text-gray-400 block mb-1">من (ج.م)</span>
                            <input type="number" 
                                   name="min_price" 
                                   value="{{ request('min_price') }}" 
                                   placeholder="0" 
                                   min="0"
                                   class="w-full px-2.5 py-1.5 text-xs border border-gray-300 rounded-lg outline-none focus:border-[#AC9653]">
                        </div>
                        <span class="text-gray-400 mt-4">-</span>
                        <div class="flex-1">
                            <span class="text-[10px] text-gray-400 block mb-1">إلى (ج.م)</span>
                            <input type="number" 
                                   name="max_price" 
                                   value="{{ request('max_price') }}" 
                                   placeholder="3000" 
                                   min="0"
                                   class="w-full px-2.5 py-1.5 text-xs border border-gray-300 rounded-lg outline-none focus:border-[#AC9653]">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2 bg-[#AC9653] hover:bg-[#968244] text-white text-xs font-bold rounded-lg transition-colors cursor-pointer">
                        تطبيق السعر
                    </button>
                </form>

                <!-- 3. Deals Only Toggle -->
                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ route('shop', array_merge(request()->except(['deals_only', 'page']), request('deals_only') ? [] : ['deals_only' => 1])) }}" 
                       class="flex items-center justify-between p-3 rounded-xl border {{ request('deals_only') ? 'border-[#CBA367] bg-[#CBA367]/10' : 'border-gray-200 hover:border-gray-300' }} transition-colors cursor-pointer">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <div>
                                <span class="block text-xs font-bold text-gray-900">عروض وتخفيضات اليوم</span>
                                <span class="block text-[10px] text-gray-500">عرض المنتجات المخفضة فقط</span>
                            </div>
                        </div>
                        <input type="checkbox" {{ request('deals_only') ? 'checked' : '' }} class="accent-[#CBA367] pointer-events-none">
                    </a>
                </div>

                <!-- 4. Egyptian Store Guarantee -->
                <div class="pt-4 border-t border-gray-100 bg-[#FDFBF7] p-3.5 rounded-xl border border-[#BDAB75]/30 space-y-2 text-right">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#AC9653]">
                        <svg class="w-4 h-4 text-[#AC9653]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <span>ضمان {{ settings('site_name', 'أزياء لافيستا') }}</span>
                    </div>
                    <p class="text-[11px] text-gray-600 leading-relaxed">
                        يحق للعميل معاينة وقياس الملابس عند وصول مندوب الشحن قبل دفع ثمن الطلب لضمان المقاس والجودة 100%.
                    </p>
                </div>

            </aside>

            <!-- Main Catalog Content -->
            <div class="lg:col-span-3 space-y-6">
                
                <!-- Toolbar: Count + Mobile Filter Trigger + Sorting Dropdown -->
                <div class="bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 shadow-2xs flex flex-row items-center justify-between gap-2 sm:gap-4 text-xs">
                    
                    <div class="flex items-center gap-1.5 sm:gap-4 min-w-0">
                        <span class="text-gray-600 font-medium text-[11px] sm:text-xs truncate">
                            <strong class="text-gray-900 font-bold">{{ $products->total() }}</strong> قطعة
                        </span>
                    </div>

                    <!-- Filter Button + Sorting in One Single Responsive Row -->
                    <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
                        <!-- Mobile Filter Button -->
                        <button type="button" 
                                @click="mobileFiltersOpen = true"
                                class="lg:hidden flex items-center gap-1.5 px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-bold text-[11px] sm:text-xs cursor-pointer border border-gray-200 transition-colors">
                            <svg class="w-3.5 h-3.5 text-[#AC9653]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <span>الفلاتر</span>
                            @if(request()->anyFilled(['category_id', 'min_price', 'max_price', 'deals_only', 'search']))
                                <span class="w-2 h-2 rounded-full bg-[#AC9653]"></span>
                            @endif
                        </button>

                        <!-- Sorting Select -->
                        <div class="flex items-center gap-1">
                            <label for="sortSelect" class="font-bold text-gray-700 shrink-0 hidden sm:inline">الترتيب:</label>
                            <select id="sortSelect" 
                                    onchange="window.location.href = this.value"
                                    class="bg-gray-50 border border-gray-300 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 text-[11px] sm:text-xs font-bold text-gray-800 outline-none focus:border-[#AC9653] cursor-pointer">
                                <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'latest'])) }}" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>الأحدث أولاً</option>
                                <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: الأقل</option>
                                <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: الأعلى</option>
                                <option value="{{ route('shop', array_merge(request()->query(), ['sort' => 'name'])) }}" {{ request('sort') == 'name' ? 'selected' : '' }}>أبجدياً</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Products Grid: 2 columns on mobile, 2 on tablet, 3 on desktop -->
                @if($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6">
                        @foreach($products as $product)
                            <x-storefront.ui.product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty Results State -->
                    <div class="bg-white p-8 sm:p-12 rounded-2xl border border-gray-200 text-center space-y-4">
                        <div class="w-16 h-16 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">لم يتم العثور على ملابس مطابقة</h3>
                        <p class="text-xs text-gray-500 max-w-md mx-auto">
                            جرب البحث بكلمات أخرى أو قم بإلغاء بعض الفلاتر لعرض كافة المنتجات المتوفرة في المتجر.
                        </p>
                        <a href="{{ route('shop') }}" class="inline-block px-6 py-2.5 bg-[#AC9653] hover:bg-[#968244] text-white text-xs font-bold rounded-lg transition-colors">
                            عرض كافة ملابس المتجر
                        </a>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <!-- Mobile Filters Drawer (Full Feature Set for Mobile Users) -->
    <div x-show="mobileFiltersOpen" 
         x-cloak
         class="fixed inset-0 z-50 lg:hidden"
         dir="rtl">
        
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs" @click="mobileFiltersOpen = false"></div>

        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-2xl p-5 overflow-y-auto space-y-5 flex flex-col justify-between">
            <div class="space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <h3 class="font-black text-sm text-gray-900 flex items-center gap-2">
                        <span>تصفية الملابس</span>
                    </h3>
                    <button type="button" @click="mobileFiltersOpen = false" class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center text-xs">✕</button>
                </div>

                <!-- Deals Only on Mobile Drawer -->
                <div>
                    <a href="{{ route('shop', array_merge(request()->except(['deals_only', 'page']), request('deals_only') ? [] : ['deals_only' => 1])) }}" 
                       class="flex items-center justify-between p-3 rounded-xl border {{ request('deals_only') ? 'border-[#CBA367] bg-[#CBA367]/10' : 'border-gray-200' }} transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-xs font-bold text-gray-900">عروض وتخفيضات اليوم فقط</span>
                        </div>
                        <input type="checkbox" {{ request('deals_only') ? 'checked' : '' }} class="accent-[#CBA367] pointer-events-none">
                    </a>
                </div>

                <!-- Categories in Mobile Drawer -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-800">أقسام الملابس</label>
                    <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
                        <a href="{{ route('shop', request()->except(['category_id', 'page'])) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold {{ !request('category_id') ? 'bg-[#AC9653] text-white font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span>كافة الأقسام</span>
                            <span class="text-[10px] {{ !request('category_id') ? 'text-white' : 'text-gray-400' }}">{{ $products->total() }}</span>
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('shop', array_merge(request()->except(['category_id', 'page']), ['category_id' => $cat->id])) }}" 
                               class="flex items-center justify-between px-3 py-2 rounded-lg text-xs font-semibold {{ request('category_id') == $cat->id ? 'bg-[#AC9653] text-white font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                <span class="truncate">{{ $cat->name }}</span>
                                <span class="text-[10px] {{ request('category_id') == $cat->id ? 'text-white' : 'text-gray-400' }}">({{ $cat->products_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range on Mobile Drawer -->
                <form action="{{ route('shop') }}" method="GET" class="pt-3 border-t border-gray-100 space-y-3">
                    @foreach(request()->except(['min_price', 'max_price', 'page']) as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach

                    <label class="block text-xs font-bold text-gray-800">السعر (ج.م)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" 
                               name="min_price" 
                               value="{{ request('min_price') }}" 
                               placeholder="من" 
                               min="0"
                               class="w-full px-2.5 py-1.5 text-xs border border-gray-300 rounded-lg outline-none focus:border-[#AC9653]">
                        <span class="text-gray-400">-</span>
                        <input type="number" 
                               name="max_price" 
                               value="{{ request('max_price') }}" 
                               placeholder="إلى" 
                               min="0"
                               class="w-full px-2.5 py-1.5 text-xs border border-gray-300 rounded-lg outline-none focus:border-[#AC9653]">
                    </div>
                    <button type="submit" class="w-full py-2 bg-[#AC9653] text-white text-xs font-bold rounded-lg shadow-xs">
                        تطبيق السعر
                    </button>
                </form>
            </div>

            <div class="pt-3 border-t border-gray-200">
                <a href="{{ route('shop') }}" class="block text-center w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-bold rounded-lg transition-colors">
                    إعادة ضبط الفلاتر
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
