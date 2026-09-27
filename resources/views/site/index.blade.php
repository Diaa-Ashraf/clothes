@extends('layouts.site')

@section('content')

{{-- =========================================================================
 OPTIONAL HERO BANNER (If active banners exist in database)
 ========================================================================= --}}
@if($banners->count() > 0)
<section class="py-3 sm:py-5 bg-[#F8F9FA] w-full overflow-hidden group/hero">
 <div class="w-full px-3 sm:px-6 lg:px-8 max-w-[1600px] mx-auto">
 <div class="swiper heroSwiper rounded-3xl sm:rounded-[2.5rem] overflow-hidden shadow-lg border border-gray-200/80 bg-[#18181B] relative w-full">
 <div class="swiper-wrapper w-full">
 @foreach($banners as $banner)
 <div class="swiper-slide relative w-full">
 {{-- Banner Item: Exact height and layout matching the HTML mockup --}}
 <div class="relative w-full min-h-[360px] sm:min-h-[420px] md:min-h-[460px] flex items-center p-6 sm:p-12 lg:p-16 overflow-hidden bg-[#14161D] text-white">

 {{-- Background Image --}}
 @if($banner->image_url)
 <img src="{{ $banner->image_url }}"
 alt="{{ $banner->title ?? 'Banner' }}"
 class="absolute inset-0 w-full h-full object-cover">
 @endif

 {{-- Soft Gradient to ensure text readability without darkening the whole image --}}
 <div class="absolute inset-0 bg-gradient-to-r rtl:bg-gradient-to-l from-black/60 via-black/20 to-transparent pointer-events-none"></div>

 {{-- Content Container --}}
 <div class="relative z-10 max-w-xl space-y-4 text-start">

 {{-- Mini Pill Tag --}}
 <div>
 <span class="inline-block px-3 py-1 rounded-full bg-white/10 text-[#C5A059] text-xs font-semibold border border-white/10">
 {{ __('تشكيلة المقتنيات الملكية 2026') }}
 </span>
 </div>

 {{-- Main Headline --}}
 <h2 class="font-royal text-3xl sm:text-5xl font-extrabold leading-tight text-white">
 {{ $banner->title ?? 'TIMELESS LUXURY & MASTERY' }}
 </h2>

 {{-- Description --}}
 <p class="text-xs sm:text-sm text-gray-300 leading-relaxed max-w-lg">
 {{ $banner->description ?? 'اكتشف أرقى ساعات اليد الميكانيكية والكلاسيكية المصنوعة بحرفية ودقة تليق بإطلالتك المرموقة.' }}
 </p>

 {{-- Action Buttons --}}
 <div class="pt-2 flex flex-wrap items-center gap-3">
 @if($banner->url ?? $banner->link)
 <a href="{{ $banner->url ?? $banner->link }}"
 class="px-6 py-3 rounded-full bg-[#C5A059] hover:bg-[#B38E44] text-white text-xs font-bold transition-all shadow-md flex items-center gap-1.5">
 <span>{{ __('استكشف التشكيلة') }}</span>
 <span>&larr;</span>
 </a>
 @else
 <a href="{{ route('product.shop') }}"
 class="px-6 py-3 rounded-full bg-[#C5A059] hover:bg-[#B38E44] text-white text-xs font-bold transition-all shadow-md flex items-center gap-1.5">
 <span>{{ __('استكشف التشكيلة') }}</span>
 <span>&larr;</span>
 </a>
 @endif

 @php
 $storePhone = preg_replace('/[^0-9]/', '', settings('whatsapp_number', '963999999999'));
 $waBannerUrl = "https://wa.me/{$storePhone}?text=" . urlencode("مرحباً، أود الاستفسار والطلب الفوري للتشكيلة الفاخرة ");
 @endphp
 <a href="{{ $waBannerUrl }}"
 target="_blank"
 class="px-6 py-3 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 transition-all flex items-center gap-2">
 <span></span>
 <span>{{ __('طلب فوري عبر واتساب') }}</span>
 </a>
 </div>
 </div>
 </div>
 </div>
 @endforeach
 </div>

 {{-- Slider Navigation Arrows (Exact Marketchino white circular controls) --}}
 <button type="button" class="hero-prev absolute start-3 sm:start-6 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/95 hover:bg-white text-[#18181B] shadow-md border border-gray-200 flex items-center justify-center transition-all duration-300 hover:scale-105">
 <svg class="w-5 h-5 rtl:rotate-180 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="hero-next absolute end-3 sm:end-6 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/95 hover:bg-white text-[#18181B] shadow-md border border-gray-200 flex items-center justify-center transition-all duration-300 hover:scale-105">
 <svg class="w-4 sm:w-5 h-4 sm:h-5 rtl:rotate-180 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>

 {{-- Swiper Pagination Dots --}}
 <div class="swiper-pagination !bottom-3 sm:!bottom-5 z-20"></div>
 </div>
 </div>
</section>
@endif

{{-- =========================================================================
 SECTION 1: CIRCULAR CATEGORIES SLIDER (Swiper with Navigation Arrows & Autoplay)
 ========================================================================= --}}
@if($categories->count() > 0)
<section class="py-8 sm:py-12 bg-white border-b border-[#E5E7EB] relative w-full overflow-hidden group/slider">
 <div class="w-full px-4 sm:px-8 lg:px-12 relative">
 <div class="swiper categoriesSwiper px-2 py-2">
 <div class="swiper-wrapper items-center">
 @foreach($categories as $cat)
 <div class="swiper-slide !w-auto">
 <a href="{{ route('category.product', $cat->id) }}" class="group flex flex-col items-center text-center w-32 sm:w-44 md:w-48">
 {{-- Large Circular Image Pedestal matching Marketchino --}}
 <div class="w-28 h-28 sm:w-36 sm:h-36 md:w-44 md:h-44 rounded-full bg-[#F8F9FA] border-2 border-gray-100 group-hover:border-[#C5A059] p-1.5 sm:p-2 flex items-center justify-center overflow-hidden transition-all duration-500 shadow-sm group-hover:shadow-xl mb-3.5">
 @if($cat->image_url)
 <img src="{{ $cat->image_url }}" alt="{{ $cat->name }}" class="w-full h-full object-cover rounded-full transition-transform duration-700 ease-out group-hover:scale-110">
 @else
 <span class="text-4xl sm:text-5xl"></span>
 @endif
 </div>
 <h3 class="font-bold text-sm sm:text-lg text-[#18181B] group-hover:text-[#C5A059] transition-colors truncate w-full tracking-wide">
 {{ $cat->name }}
 </h3>
 <span class="text-xs sm:text-sm text-[#71717A] mt-1 font-medium">
 {{ $cat->active_products_count ?? 0 }} {{ __('منتجات') }}
 </span>
 </a>
 </div>
 @endforeach
 </div>
 </div>

 {{-- Custom Navigation Arrows for Categories --}}
 <button type="button" class="categories-prev absolute start-2 sm:start-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/95 border border-gray-200 shadow-xl flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white transition-all duration-300">
 <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="categories-next absolute end-2 sm:end-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-white/95 border border-gray-200 shadow-xl flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white transition-all duration-300">
 <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>
 </div>
</section>
@endif

{{-- =========================================================================
 SECTION 2: الأكثر مبيعاً (Best Sellers 6-Card SLIDER - Marketchino Exact)
 ========================================================================= --}}
@if($bestSellers->count() > 0)
<section class="py-10 sm:py-14 bg-[#F8F9FA] border-b border-[#E5E7EB] w-full overflow-hidden group/slider">
 <div class="w-full px-4 sm:px-8 lg:px-12">
 {{-- Section Header --}}
 <div class="text-center mb-8">
 <h2 class="text-2xl sm:text-3xl font-bold text-[#18181B]">{{ __('الأكثر مبيعًا') }}</h2>
 <p class="text-xs sm:text-sm text-[#71717A] mt-1">{{ __('إليكم بعضًا من أكثر منتجاتنا رواجًا والتي نالت إعجاب الكثير من العملاء.') }}</p>
 </div>

 {{-- 6-Slide Swiper Slider Container --}}
 <div class="relative px-2 sm:px-4">
 <div class="swiper bestSellersSwiper">
 <div class="swiper-wrapper py-2">
 @foreach($bestSellers as $product)
 <div class="swiper-slide">
 <x-product-card :product="$product" />
 </div>
 @endforeach
 </div>
 </div>

 {{-- Slider Navigation Arrows: Hidden by default, visible on slider hover --}}
 <button type="button" class="bestSellers-prev absolute -start-2 sm:-start-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="bestSellers-next absolute -end-2 sm:-end-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>
 </div>
 </div>
</section>
@endif

{{-- =========================================================================
 SECTION 3: الفئات المميزة (Featured Categories Visual Grid - Marketchino Style)
 ========================================================================= --}}
@if($featuredCategories->count() > 0)
<section class="py-10 sm:py-14 bg-white border-b border-[#E5E7EB] w-full overflow-hidden">
 <div class="w-full px-4 sm:px-8 lg:px-12">
 {{-- Section Header --}}
 <div class="text-center mb-8">
 <h2 class="text-2xl sm:text-3xl font-bold text-[#18181B]">{{ __('الفئات المميزة') }}</h2>
 <p class="text-xs sm:text-sm text-[#71717A] mt-1">{{ __('تصفح أفضل الفئات التي اخترناها بعناية خصيصًا لك.') }}</p>
 </div>

 {{-- Category Mosaic Cards (Rounded-3xl with bottom floating white pill) --}}
 <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
 @foreach($featuredCategories as $category)
 <a href="{{ route('category.product', $category->id) }}"
 class="group relative aspect-[4/5] rounded-3xl overflow-hidden bg-[#F8F9FA] border border-[#E5E7EB] hover:border-[#C5A059]/60 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-end p-4">

 @if($category->image_url)
 <img src="{{ $category->image_url }}"
 alt="{{ $category->name }}"
 class="absolute inset-0 w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 ease-out">
 @else
 <div class="absolute inset-0 flex items-center justify-center text-4xl bg-gradient-to-t from-gray-200 to-gray-50">
 
 </div>
 @endif

 <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

 {{-- Floating Bottom Pill (Exact Marketchino Design) --}}
 <div class="relative z-10 w-full bg-white/95 backdrop-blur-sm rounded-full py-2.5 px-4 text-center shadow-md group-hover:bg-[#18181B] transition-colors">
 <span class="text-xs sm:text-sm font-bold text-[#18181B] group-hover:text-white transition-colors">
 {{ $category->name }}
 </span>
 </div>
 </a>
 @endforeach
 </div>
 </div>
</section>
@endif

{{-- =========================================================================
 SECTION 4: DYNAMIC FILTER TABS SLIDERS (Trending / Deals / Best Sellers)
 ========================================================================= --}}
<section class="py-10 sm:py-14 bg-[#F8F9FA] border-b border-[#E5E7EB] w-full overflow-hidden group/slider"
 x-data="{ 
 activeTab: 'trending',
 switchTab(tab) {
 this.activeTab = tab;
 $nextTick(() => {
 window.dispatchEvent(new Event('resize'));
 });
 }
 }">
 <div class="w-full px-4 sm:px-8 lg:px-12">

 {{-- Filter Tab Buttons (Marketchino Style Pills) --}}
 <div class="flex items-center justify-center gap-2 sm:gap-3 flex-wrap mb-10">
 <button @click="switchTab('trending')"
 :class="activeTab === 'trending' ? 'bg-[#C5A059] text-white shadow-sm font-bold' : 'text-[#52525B] hover:text-[#18181B] bg-white border border-gray-200'"
 class="px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 shadow-xs">
 {{ __('الإعلانات المميزة') }}
 </button>
 <button @click="switchTab('deals')"
 :class="activeTab === 'deals' ? 'bg-[#C5A059] text-white shadow-sm font-bold' : 'text-[#52525B] hover:text-[#18181B] bg-white border border-gray-200'"
 class="px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 shadow-xs">
 {{ __('العروض اليومية') }}
 </button>
 <button @click="switchTab('best_sellers')"
 :class="activeTab === 'best_sellers' ? 'bg-[#C5A059] text-white shadow-sm font-bold' : 'text-[#52525B] hover:text-[#18181B] bg-white border border-gray-200'"
 class="px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all duration-200 shadow-xs">
 {{ __('الأكثر مبيعاً') }}
 </button>
 </div>

 {{-- Tab 1: Featured Ads / Promoted Products Slider --}}
 <div x-show="activeTab === 'trending'" x-transition class="relative px-2 sm:px-4">
 <div class="swiper trendingSwiper">
 <div class="swiper-wrapper py-2">
 @foreach($trendingProducts as $product)
 <div class="swiper-slide">
 <x-product-card :product="$product" />
 </div>
 @endforeach
 </div>
 </div>
 <button type="button" class="trending-prev absolute -start-2 sm:-start-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="trending-next absolute -end-2 sm:-end-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>
 </div>

 {{-- Tab 2: Daily Flash Deals Slider --}}
 <div x-show="activeTab === 'deals'" x-transition style="display: none;" class="relative px-2 sm:px-4">
 @php
 $dealsList = $dealsProducts->count() > 0 ? $dealsProducts : ($deals->count() > 0 ? $deals->pluck('product') : collect());
 @endphp
 @if($dealsList->count() > 0)
 <div class="swiper dealsSwiper">
 <div class="swiper-wrapper py-2">
 @foreach($dealsList as $item)
 @if($item)
 <div class="swiper-slide">
 <x-product-card :product="$item" />
 </div>
 @endif
 @endforeach
 </div>
 </div>
 <button type="button" class="deals-prev absolute -start-2 sm:-start-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="deals-next absolute -end-2 sm:-end-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>
 @else
 <div class="py-12 text-center bg-white rounded-2xl border border-gray-100 p-8 shadow-xs">
 
 <h3 class="text-base font-bold text-gray-800">{{ __('لا توجد عروض يومية حالياً') }}</h3>
 <p class="text-xs text-gray-500 mt-1">{{ __('ترقبوا عروضنا وخصوماتنا الحصرية قريباً، أو يمكنكم إضافتها من لوحة التحكم.') }}</p>
 </div>
 @endif
 </div>

 {{-- Tab 3: Best Sellers Slider --}}
 <div x-show="activeTab === 'best_sellers'" x-transition style="display: none;" class="relative px-2 sm:px-4">
 <div class="swiper tabBestSellersSwiper">
 <div class="swiper-wrapper py-2">
 @foreach($bestSellers as $product)
 <div class="swiper-slide">
 <x-product-card :product="$product" />
 </div>
 @endforeach
 </div>
 </div>
 <button type="button" class="tabBestSellers-prev absolute -start-2 sm:-start-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="tabBestSellers-next absolute -end-2 sm:-end-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>
 </div>
 </div>
</section>

{{-- =========================================================================
 SECTION 5: BUNDLES (عروض البكجات الموفرة إن وجدت - أعلى قسم المنتجات الجديدة)
 ========================================================================= --}}
@if($bundles->count() > 0)
<section class="py-12 sm:py-16 bg-[#F8F9FA] border-b border-[#E5E7EB] w-full overflow-hidden">
 <div class="w-full px-4 sm:px-8 lg:px-12">
 <div class="text-center mb-10">
 <span class="text-xs font-bold tracking-widest text-[#C5A059] uppercase mb-1 block"> {{ __('عروض وتوفير حصري') }}</span>
 <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-[#18181B] tracking-tight">{{ __('باقات موفّرة وعروض مجمعة') }}</h2>
 <p class="text-xs sm:text-sm text-[#71717A] mt-1.5 max-w-lg mx-auto leading-relaxed">{{ __('وفّر أكثر عند اقتناء الباقات والعروض الخاصة المجمعة المصممة خصيصاً لك') }}</p>
 </div>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 max-w-[1600px] mx-auto">
 @foreach($bundles->take(4) as $bundle)
 <x-bundle-card :bundle="$bundle" />
 @endforeach
 </div>
 </div>
</section>
@endif

{{-- =========================================================================
 SECTION 6: المنتجات الجديدة (Latest Arrivals 6-Card SLIDER)
 ========================================================================= --}}
@if($latestProducts->count() > 0)
<section class="py-10 sm:py-14 bg-white border-b border-[#E5E7EB] w-full overflow-hidden group/slider">
 <div class="w-full px-4 sm:px-8 lg:px-12">
 {{-- Section Header --}}
 <div class="text-center mb-8">
 <h2 class="text-2xl sm:text-3xl font-bold text-[#18181B]">{{ __('المنتجات الجديدة') }}</h2>
 <p class="text-xs sm:text-sm text-[#71717A] mt-1">{{ __('اكتشف كل ما هو جديد ومثير. أحدث تشكيلاتنا من الهدايا في انتظارك!') }}</p>
 </div>

 {{-- 6-Slide Swiper Slider Container --}}
 <div class="relative px-2 sm:px-4">
 <div class="swiper latestSwiper">
 <div class="swiper-wrapper py-2">
 @foreach($latestProducts as $product)
 <div class="swiper-slide">
 <x-product-card :product="$product" />
 </div>
 @endforeach
 </div>
 </div>

 {{-- Slider Navigation Arrows: Hidden by default, visible on slider hover --}}
 <button type="button" class="latest-prev absolute -start-2 sm:-start-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
 </svg>
 </button>
 <button type="button" class="latest-next absolute -end-2 sm:-end-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white border border-gray-200 shadow-lg flex items-center justify-center text-[#18181B] hover:bg-[#18181B] hover:text-white opacity-0 group-hover/slider:opacity-100 pointer-events-none group-hover/slider:pointer-events-auto transition-all duration-300">
 <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
 </svg>
 </button>
 </div>

 {{-- View All CTA Button --}}
 <div class="mt-10 text-center">
 <a href="{{ route('product.shop') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-[#C5A059] hover:bg-[#18181B] text-white text-xs font-bold transition-all shadow-sm">
 <span>{{ __('استكشف كافة المنتجات والتشكيلات') }}</span>
 <span>&larr;</span>
 </a>
 </div>
 </div>
</section>
@endif

@endsection

@push('scripts')
<script>
 document.addEventListener('DOMContentLoaded', function() {
 // Standard Product Slider Config (6 desktop cards, 5-second automatic sliding, pause on mouse enter)
 const standardSliderOptions = (prevEl, nextEl) => ({
 slidesPerView: 2,
 spaceBetween: 14,
 speed: 700,
 autoplay: {
 delay: 5000,
 disableOnInteraction: false,
 pauseOnMouseEnter: true,
 },
 navigation: {
 prevEl: prevEl,
 nextEl: nextEl,
 },
 breakpoints: {
 640: {
 slidesPerView: 3,
 spaceBetween: 16,
 },
 768: {
 slidesPerView: 4,
 spaceBetween: 16,
 },
 1024: {
 slidesPerView: 5,
 spaceBetween: 20,
 },
 1280: {
 slidesPerView: 6,
 spaceBetween: 20,
 },
 1536: {
 slidesPerView: 6,
 spaceBetween: 24,
 }
 }
 });

 // 1. Hero Swiper
 if (document.querySelector('.heroSwiper')) {
 new Swiper('.heroSwiper', {
 loop: true,
 speed: 800,
 autoplay: {
 delay: 5000,
 disableOnInteraction: false,
 pauseOnMouseEnter: true
 },
 pagination: {
 el: '.swiper-pagination',
 clickable: true
 },
 navigation: {
 prevEl: '.hero-prev',
 nextEl: '.hero-next',
 },
 });
 }

 // 2. Categories Slider with 5-Second Autoplay
 if (document.querySelector('.categoriesSwiper')) {
 new Swiper('.categoriesSwiper', {
 slidesPerView: 'auto',
 spaceBetween: 24,
 speed: 600,
 autoplay: {
 delay: 5000,
 disableOnInteraction: false,
 pauseOnMouseEnter: true,
 },
 navigation: {
 prevEl: '.categories-prev',
 nextEl: '.categories-next',
 },
 breakpoints: {
 640: {
 spaceBetween: 36
 },
 1024: {
 spaceBetween: 48
 }
 }
 });
 }

 // 3. Best Sellers Slider
 if (document.querySelector('.bestSellersSwiper')) {
 new Swiper('.bestSellersSwiper', standardSliderOptions('.bestSellers-prev', '.bestSellers-next'));
 }

 // 4. Tab Sliders
 let trendingSwiper, dealsSwiper, tabBestSellersSwiper;

 if (document.querySelector('.trendingSwiper')) {
 trendingSwiper = new Swiper('.trendingSwiper', standardSliderOptions('.trending-prev', '.trending-next'));
 }
 if (document.querySelector('.dealsSwiper')) {
 dealsSwiper = new Swiper('.dealsSwiper', standardSliderOptions('.deals-prev', '.deals-next'));
 }
 if (document.querySelector('.tabBestSellersSwiper')) {
 tabBestSellersSwiper = new Swiper('.tabBestSellersSwiper', standardSliderOptions('.tabBestSellers-prev', '.tabBestSellers-next'));
 }

 // 5. Latest Arrivals Slider
 if (document.querySelector('.latestSwiper')) {
 new Swiper('.latestSwiper', standardSliderOptions('.latest-prev', '.latest-next'));
 }

 // Re-update swiper dimensions when switching tabs
 window.addEventListener('resize', () => {
 if (trendingSwiper) trendingSwiper.update();
 if (dealsSwiper) dealsSwiper.update();
 if (tabBestSellersSwiper) tabBestSellersSwiper.update();
 });
 });
</script>
@endpush