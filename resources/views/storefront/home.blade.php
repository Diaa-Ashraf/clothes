{{-- resources/views/storefront/home.blade.php --}}
@extends('layouts.storefront')

@section('title', settings('site_name', 'أزياء لافيستا') . ' | متجر الملابس والأزياء المصرية العصرية')

@section('content')
<div class="bg-white" dir="rtl">

    <!-- 1. Hero Campaign Carousel / Banners -->
    <section class="relative bg-gradient-to-l from-[#AC9653] via-[#BDAB75] to-[#C2B280] text-white overflow-hidden border-b border-[#BDAB75]/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 lg:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center">
                
                <!-- Hero Content (Right in RTL) -->
                <div class="lg:col-span-7 space-y-3.5 sm:space-y-6 text-right z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-xs rounded-full border border-white/25 text-white text-[11px] sm:text-xs font-bold">
                        <span class="w-2 h-2 rounded-full bg-[#CBA367] animate-pulse"></span>
                        <span>أحدث صيحات الملابس المصرية لعام {{ date('Y') }}</span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black text-white leading-snug tracking-tight">
                        أناقة عصرية تناسب<br>
                        <span class="text-[#FDFBF7] underline decoration-[#CBA367] decoration-4 underline-offset-8">كافة أفراد الأسرة المصرية</span>
                    </h1>

                    <p class="text-xs sm:text-base text-white/95 leading-relaxed font-normal max-w-xl">
                        اكتشف تشكيلات متكاملة من أرقى الملابس الرجالية، النسائية، وأزياء الأطفال. خامات قطن مصري 100% وصوف ناعم مع إمكانية المعاينة والقياس عند الاستلام قبل دفع أي مليم.
                    </p>

                    <!-- Action Buttons in Camel #CBA367 and White Outline -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-4 pt-2">
                        <a href="{{ route('shop') }}" 
                           class="px-6 py-3 bg-[#CBA367] hover:bg-[#b88f55] text-white font-bold text-xs sm:text-sm rounded-lg shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                            <span>تسوق كافة الملابس الآن</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>

                        <a href="{{ route('shop', ['deals_only' => 1]) }}" 
                           class="px-5 py-3 bg-white/15 hover:bg-white text-white hover:text-[#2B2118] border border-white/40 font-bold text-xs sm:text-sm rounded-lg transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4 text-[#CBA367]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>أقوى عروض اليوم</span>
                        </a>
                    </div>

                    <!-- Trust Points in Hero -->
                    <div class="pt-3 sm:pt-4 border-t border-white/25 flex flex-wrap items-center gap-3 sm:gap-6 text-[11px] sm:text-xs text-white/90 font-medium">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#CBA367]">✓</span>
                            <span>معاينة وقياس قبل الدفع</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#CBA367]">✓</span>
                            <span>شحن لكافة محافظات مصر</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-[#CBA367]">✓</span>
                            <span>دفع كاش أو إلكتروني</span>
                        </div>
                    </div>
                </div>

                <!-- Hero Visual (Left in RTL) -->
                <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                    <div class="relative mx-auto max-w-sm lg:max-w-none">
                        <!-- Card 1 (Main Featured Fashion Photo) -->
                        <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white/30 transform hover:scale-[1.02] transition-transform duration-300">
                            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80" 
                                 alt="{{ settings('site_name', 'أزياء لافيستا') }} كولكشن الشتاء" 
                                 class="w-full h-72 sm:h-96 object-cover object-top">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-4 sm:p-5">
                                <div class="text-white">
                                    <span class="px-2.5 py-1 bg-[#CBA367] text-white font-bold text-xs rounded-md shadow-xs mb-1 inline-block">
                                        خصومات حتى 40%
                                    </span>
                                    <h3 class="text-sm sm:text-base font-bold">كولكشن الشتاء والملابس الدافئة</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Badge on Hero with Soft Float Animation -->
                        <div class="absolute -bottom-3 right-2 sm:-right-6 bg-white text-gray-900 p-2.5 sm:p-4 rounded-xl shadow-xl border border-gray-100 flex items-center gap-2.5 sm:gap-3 animate-float max-w-[calc(100%-1rem)]">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#AC9653]/15 text-[#AC9653] flex items-center justify-center font-bold text-base sm:text-lg shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[#AC9653]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="text-right">
                                <span class="block text-[11px] sm:text-xs font-black text-gray-900">+500 تصميم متاح</span>
                                <span class="block text-[9.5px] sm:text-[10px] text-gray-500 font-medium">خامات قطنية مصرية 100%</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. Shop by Category / Department (تسوق حسب القسم with Card Lift & Smooth Zoom) -->
    <section class="py-8 sm:py-14 bg-[#F9FAFB] border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
            
            <div class="flex items-end justify-between">
                <div class="space-y-1 text-right">
                    <span class="text-xs font-bold text-[#AC9653] uppercase tracking-wider">أقسام المتجر الرئيسية</span>
                    <h2 class="text-lg sm:text-2xl font-black text-gray-900">تسوق حسب فئة الملابس</h2>
                </div>
                <a href="{{ route('shop') }}" class="text-xs font-bold text-[#AC9653] hover:text-[#8f7a3f] flex items-center gap-1 transition-colors group">
                    <span>عرض الكل</span>
                    <span class="text-sm group-hover:-translate-x-1 transition-transform">‹</span>
                </a>
            </div>

            <!-- Categories Grid: 3 columns on mobile, 6 on desktop -->
            <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-5">
                
                <!-- Category 1: ملابس رجالي -->
                <a href="{{ route('shop', ['category_id' => 9]) }}" 
                   class="group bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 card-lift text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full overflow-hidden mb-2 sm:mb-3 border-2 border-gray-100 group-hover:border-[#AC9653] transition-colors shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=300&q=80" 
                             alt="ملابس رجالية" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    <h3 class="font-bold text-[11px] sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors">ملابس رجالي</h3>
                    <span class="text-[9.5px] sm:text-[11px] text-gray-400 mt-0.5 hidden sm:block">قمصان، بدل، جينز</span>
                </a>

                <!-- Category 2: أزياء نسائية -->
                <a href="{{ route('shop', ['category_id' => 14]) }}" 
                   class="group bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 card-lift text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full overflow-hidden mb-2 sm:mb-3 border-2 border-gray-100 group-hover:border-[#AC9653] transition-colors shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?auto=format&fit=crop&w=300&q=80" 
                             alt="أزياء نسائية" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    <h3 class="font-bold text-[11px] sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors">أزياء نسائية</h3>
                    <span class="text-[9.5px] sm:text-[11px] text-gray-400 mt-0.5 hidden sm:block">فساتين، عبايات</span>
                </a>

                <!-- Category 3: أطفال ومواليد -->
                <a href="{{ route('shop', ['category_id' => 19]) }}" 
                   class="group bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 card-lift text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full overflow-hidden mb-2 sm:mb-3 border-2 border-gray-100 group-hover:border-[#AC9653] transition-colors shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1519457431-44ccd64a579b?auto=format&fit=crop&w=300&q=80" 
                             alt="أطفال ومواليد" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    <h3 class="font-bold text-[11px] sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors">أطفال ومواليد</h3>
                    <span class="text-[9.5px] sm:text-[11px] text-gray-400 mt-0.5 hidden sm:block">ترنجات، فساتين</span>
                </a>

                <!-- Category 4: معاطف وجاكيتات -->
                <a href="{{ route('shop', ['category_id' => 13]) }}" 
                   class="group bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 card-lift text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full overflow-hidden mb-2 sm:mb-3 border-2 border-gray-100 group-hover:border-[#AC9653] transition-colors shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=300&q=80" 
                             alt="معاطف وجاكيتات" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    <h3 class="font-bold text-[11px] sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors">معاطف وجواكت</h3>
                    <span class="text-[9.5px] sm:text-[11px] text-gray-400 mt-0.5 hidden sm:block">صوف دافئ</span>
                </a>

                <!-- Category 5: أحذية وحقائب -->
                <a href="{{ route('shop', ['category_id' => 22]) }}" 
                   class="group bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 card-lift text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full overflow-hidden mb-2 sm:mb-3 border-2 border-gray-100 group-hover:border-[#AC9653] transition-colors shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=300&q=80" 
                             alt="أحذية وحقائب" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    <h3 class="font-bold text-[11px] sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors">أحذية وحقائب</h3>
                    <span class="text-[9.5px] sm:text-[11px] text-gray-400 mt-0.5 hidden sm:block">جلد طبيعي</span>
                </a>

                <!-- Category 6: إكسسوارات وساعات -->
                <a href="{{ route('shop', ['category_id' => 25]) }}" 
                   class="group bg-white p-2.5 sm:p-4 rounded-xl sm:rounded-2xl border border-gray-200 card-lift text-center flex flex-col items-center">
                    <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full overflow-hidden mb-2 sm:mb-3 border-2 border-gray-100 group-hover:border-[#AC9653] transition-colors shadow-2xs">
                        <img src="https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=300&q=80" 
                             alt="إكسسوارات وساعات" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                    </div>
                    <h3 class="font-bold text-[11px] sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors">إكسسوارات</h3>
                    <span class="text-[9.5px] sm:text-[11px] text-gray-400 mt-0.5 hidden sm:block">أحزمة، محافظ</span>
                </a>

            </div>

        </div>
    </section>

    <!-- 4. Flash Sale / عروض وتخفيضات اليوم (Deals Section) -->
    @if($deals->isNotEmpty())
        <section class="py-10 sm:py-14 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#CBA367]/15 text-[#CBA367] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl sm:text-2xl font-black text-gray-900">عروض وتخفيضات اليوم</h2>
                            <p class="text-xs text-gray-500">خصومات فورية حصرية لفترة محدودة بالجنيه المصري</p>
                        </div>
                    </div>
                    <a href="{{ route('shop', ['deals_only' => 1]) }}" class="px-4 py-2 bg-[#CBA367] hover:bg-[#b88f55] text-white text-xs font-bold rounded-lg transition-colors">
                        مشاهدة كافة العروض
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($deals->take(4) as $deal)
                        @if($deal->product)
                            <x-storefront.ui.product-card :product="$deal->product" />
                        @endif
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- 5. Two Mid-Page Promotional Banners (Egyptian Men & Women Campaigns) -->
    <section class="py-8 sm:py-10 bg-[#F9FAFB] border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                
                @if(isset($collectionBanners) && $collectionBanners->isNotEmpty())
                    @foreach($collectionBanners->take(2) as $banner)
                        @php
                            $defaultBg = $loop->first ? '#AC9653' : '#BDAB75';
                            $bgColor = $banner->bg_color ?: $defaultBg;
                            $btnBg = $loop->first ? '#CBA367' : '#AC9653';
                        @endphp
                        <div class="relative overflow-hidden rounded-2xl text-white p-5 sm:p-8 flex flex-col justify-between min-h-[190px] sm:min-h-[220px] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group"
                             style="background-color: {{ $bgColor }};">
                            <div class="relative z-10 space-y-1.5 sm:space-y-2 max-w-xs">
                                @if($banner->subtitle)
                                    <span class="inline-block px-2.5 py-0.5 bg-white/20 rounded-md text-[10px] sm:text-[11px] font-bold">
                                        {{ $banner->subtitle }}
                                    </span>
                                @endif
                                <h3 class="text-lg sm:text-2xl font-black leading-tight">
                                    {{ $banner->title }}
                                </h3>
                                @if($banner->description)
                                    <p class="text-[11px] sm:text-xs text-white/90">
                                        {{ $banner->description }}
                                    </p>
                                @endif
                                @if($banner->url)
                                    <a href="{{ $banner->url }}" 
                                       class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2 sm:py-2.5 active:scale-95 text-white text-[11px] sm:text-xs font-bold rounded-lg shadow-sm hover:shadow-md transition-all mt-1 sm:mt-2"
                                       style="background-color: {{ $btnBg }};">
                                        <span>{{ $banner->button_text ?: 'تسوق الكولكشن الآن' }}</span>
                                        <span class="group-hover:-translate-x-1 transition-transform">‹</span>
                                    </a>
                                @endif
                            </div>
                            @if($banner->image)
                                <img src="{{ $banner->image_url }}" 
                                     alt="{{ $banner->title }}" 
                                     class="absolute -left-6 -bottom-6 w-36 h-36 sm:w-52 sm:h-52 object-cover rounded-full opacity-65 group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700 ease-out">
                            @endif
                        </div>
                    @endforeach
                @else
                    <!-- Fallback Classic Men & Women Campaigns -->
                    <!-- Men Banner -->
                    <div class="relative overflow-hidden rounded-2xl bg-[#AC9653] text-white p-5 sm:p-8 flex flex-col justify-between min-h-[190px] sm:min-h-[220px] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group">
                        <div class="relative z-10 space-y-1.5 sm:space-y-2 max-w-xs">
                            <span class="inline-block px-2.5 py-0.5 bg-white/20 rounded-md text-[10px] sm:text-[11px] font-bold">
                                كولكشن الشباب والرجال
                            </span>
                            <h3 class="text-lg sm:text-2xl font-black leading-tight">
                                بدل، بليزرات، وقمصان كلاسيك للعمل والمناسبات
                            </h3>
                            <p class="text-[11px] sm:text-xs text-white/90">
                                قصات سمارت كاجوال مريحة وتشطيب مصري فائق الدقة.
                            </p>
                            <a href="{{ route('shop', ['category_id' => 9]) }}" 
                               class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2 sm:py-2.5 bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 text-white text-[11px] sm:text-xs font-bold rounded-lg shadow-sm hover:shadow-md transition-all mt-1 sm:mt-2">
                                <span>تسوق الرجالي الآن</span>
                                <span class="group-hover:-translate-x-1 transition-transform">‹</span>
                            </a>
                        </div>
                        <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=400&q=80" 
                             alt="ملابس رجالية" 
                             class="absolute -left-6 -bottom-6 w-36 h-36 sm:w-52 sm:h-52 object-cover rounded-full opacity-65 group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700 ease-out">
                    </div>

                    <!-- Women Banner -->
                    <div class="relative overflow-hidden rounded-2xl bg-[#BDAB75] text-white p-5 sm:p-8 flex flex-col justify-between min-h-[190px] sm:min-h-[220px] shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group">
                        <div class="relative z-10 space-y-1.5 sm:space-y-2 max-w-xs">
                            <span class="inline-block px-2.5 py-0.5 bg-white/20 rounded-md text-[10px] sm:text-[11px] font-bold">
                                كولكشن السيدات والفتيات
                            </span>
                            <h3 class="text-lg sm:text-2xl font-black leading-tight">
                                فساتين، دريسات كاجوال، وعبايات استقبال راقية
                            </h3>
                            <p class="text-[11px] sm:text-xs text-white/90">
                                أقمشة شيفون وكريب ناعمة تناسب كافة الأوقات والخروجات.
                            </p>
                            <a href="{{ route('shop', ['category_id' => 14]) }}" 
                               class="inline-flex items-center gap-1.5 px-4 sm:px-5 py-2 sm:py-2.5 bg-[#AC9653] hover:bg-[#968244] active:scale-95 text-white text-[11px] sm:text-xs font-bold rounded-lg shadow-sm hover:shadow-md transition-all mt-1 sm:mt-2">
                                <span>تسوقي النسائي الآن</span>
                                <span class="group-hover:-translate-x-1 transition-transform">‹</span>
                            </a>
                        </div>
                        <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=400&q=80" 
                             alt="أزياء نسائية" 
                             class="absolute -left-6 -bottom-6 w-36 h-36 sm:w-52 sm:h-52 object-cover rounded-full opacity-65 group-hover:scale-110 group-hover:rotate-2 transition-transform duration-700 ease-out">
                    </div>
                @endif

            </div>
        </div>
    </section>

    <!-- 6. وصل حديثاً (New Arrivals Section) -->
    <section class="py-8 sm:py-14 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex items-end justify-between">
                <div class="space-y-1 text-right">
                    <span class="text-xs font-bold text-[#AC9653] uppercase tracking-wider">وصل حديثاً للمتجر</span>
                    <h2 class="text-lg sm:text-2xl font-black text-gray-900">أحدث موديلات الأسبوع</h2>
                </div>
                <a href="{{ route('shop') }}" class="text-xs font-bold text-[#AC9653] hover:underline flex items-center gap-1">
                    <span>تصفح الكل</span>
                    <span>‹</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($latestProducts->take(8) as $prod)
                    <x-storefront.ui.product-card :product="$prod" />
                @endforeach
            </div>

        </div>
    </section>

    <!-- 7. الأكثر طلباً ومبيعاً (Best Sellers) -->
    @if($featuredProducts->isNotEmpty())
        <section class="py-8 sm:py-14 bg-[#F9FAFB] border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <div class="flex items-end justify-between">
                    <div class="space-y-1 text-right">
                        <span class="text-xs font-bold text-[#CBA367] uppercase tracking-wider">الأعلى تقييماً وإقبالاً</span>
                        <h2 class="text-lg sm:text-2xl font-black text-gray-900">الأكثر طلباً ومبيعاً في مصر</h2>
                    </div>
                    <a href="{{ route('shop') }}" class="text-xs font-bold text-[#AC9653] hover:underline flex items-center gap-1">
                        <span>عرض التشكيلة</span>
                        <span>‹</span>
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
                    @foreach($featuredProducts->take(8) as $prod)
                        <x-storefront.ui.product-card :product="$prod" />
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- 8. Real Customer Reviews from Egyptian Governorates -->
    @if(isset($reviews) && $reviews->isNotEmpty())
        <section class="py-12 sm:py-16 bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 text-center">
                
                <div class="space-y-2 max-w-xl mx-auto">
                    <span class="text-xs font-bold text-[#AC9653] uppercase tracking-wider">تجارب حقيقية</span>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900">ماذا يقول عملاؤنا في محافظات مصر؟</h2>
                    <p class="text-xs text-gray-500">تقييمات موثقة من عملاء استلموا وعاينوا طلباتهم في مختلف المحافظات</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 text-right">
                    @foreach($reviews as $rev)
                        <div class="p-5 sm:p-6 rounded-2xl bg-[#F9FAFB] border border-gray-200 hover:border-[#AC9653]/40 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between space-y-3">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="w-3.5 h-3.5 {{ $star <= round($rev->rating) ? 'text-[#CBA367] fill-[#CBA367]' : 'text-gray-300 fill-gray-200' }}" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-[10.5px] text-gray-400 font-medium">
                                        {{ $rev->created_at ? $rev->created_at->diffForHumans() : '' }}
                                    </span>
                                </div>

                                <p class="text-xs text-gray-700 leading-relaxed font-medium">
                                    "{{ $rev->comment }}"
                                </p>

                                @if($rev->product)
                                    <div class="pt-1">
                                        <a href="{{ route('product.details', $rev->product->id) }}" class="inline-flex items-center gap-1.5 text-[10.5px] font-semibold text-[#AC9653] hover:underline bg-[#AC9653]/10 px-2 py-0.5 rounded-md transition-colors">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            <span class="truncate max-w-[200px]">{{ $rev->product->name }}</span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-gray-200/80 flex items-center justify-between">
                                <div>
                                    <span class="block text-xs font-bold text-gray-900">{{ $rev->author_name }}</span>
                                    <span class="block text-[10px] text-gray-500 font-medium">{{ $rev->author_location }}</span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[10px] bg-emerald-50 text-emerald-800 border border-emerald-200/60 px-2 py-0.5 rounded font-bold">
                                    <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>شراء مؤكد</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- 9. Newsletter & Instant Discount Strip -->
    <section class="py-10 bg-gradient-to-r from-[#AC9653] to-[#BDAB75] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-right">
                
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-white">احصل على كود خصم 10% فوري على طلبك القادم</h3>
                    <p class="text-xs text-white/90">سجل بريدك الإلكتروني ليصلك أحدث العروض وكوبونات الخصم الأسبوعية.</p>
                </div>

                <form onsubmit="event.preventDefault(); toastr.success('تم اشتراكك بنجاح! كود الخصم هو TAWHEED10');" class="flex items-center w-full md:w-auto max-w-md gap-2">
                    <input type="email" 
                           placeholder="أدخل بريدك الإلكتروني..." 
                           required
                           class="px-4 py-2.5 bg-white text-gray-800 placeholder-gray-400 text-xs rounded-lg outline-none flex-1 border border-transparent focus:border-white">
                    <button type="submit" class="px-6 py-2.5 bg-[#CBA367] hover:bg-[#b88f55] text-white text-xs font-bold rounded-lg transition-colors shrink-0 shadow-xs">
                        اشتراك
                    </button>
                </form>

            </div>
        </div>
    </section>

</div>
@endsection
