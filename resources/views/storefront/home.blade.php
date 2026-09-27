{{-- resources/views/storefront/home.blade.php --}}
@extends('layouts.storefront')

@section('title', 'الرئيسية | ألبان وسوبرماركت التوحيد بالعامرية')

@section('content')
<div class="space-y-2 sm:space-y-4">
    
    <!-- 1. Hero Banners Slider -->
    <x-storefront.sections.hero-slider :banners="$banners" />

    <!-- 2. Interactive Flash Coupons Strip -->
    <section class="py-2.5 bg-gradient-to-r from-red-600 via-orange-500 to-amber-500 text-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-right">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-black text-xs sm:text-sm">كوبون خصم الافتتاح: استخدم كود <span class="bg-white text-red-600 font-mono font-black px-2 py-0.5 rounded-md mx-1 select-all">TAWHEED10</span></span>
                        <span class="text-[11px] text-white/90 hidden md:inline">واحصل على 10% خصم فوري على أول طلبية!</span>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('site.products') }}" class="px-4 py-1.5 rounded-full bg-white text-red-600 hover:bg-amber-100 font-black text-xs shadow-sm transition-transform hover:scale-105 flex items-center gap-1.5">
                        <span>استخدم الكوبون الآن</span>
                        <svg class="w-3.5 h-3.5 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Value Propositions / Highlights Strip -->
    <section class="py-5 bg-white border-y border-orange-100 shadow-xs">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 text-center">
                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-red-50/60 to-orange-50/40 border border-red-100 flex flex-col items-center gap-2 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-red-100/80 text-red-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-900">ألبان فلاحي 100% طازجة</h3>
                    <p class="text-[11px] text-gray-500 font-medium">حليب، قشطة، وسمن بلدي يومياً من مزارعنا</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-red-50/60 to-orange-50/40 border border-red-100 flex flex-col items-center gap-2 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-orange-100/80 text-orange-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-900">توصيل سريع بالعامرية</h3>
                    <p class="text-[11px] text-gray-500 font-medium">نوصلك في وقت قياسي بأعلى درجات التبريد</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-red-50/60 to-orange-50/40 border border-red-100 flex flex-col items-center gap-2 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-amber-100/80 text-amber-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-900">دفع كاش أو إنستاباي</h3>
                    <p class="text-[11px] text-gray-500 font-medium">طرق دفع فورية وآمنة تناسب الجميع</p>
                </div>

                <div class="p-3.5 rounded-2xl bg-gradient-to-br from-red-50/60 to-orange-50/40 border border-red-100 flex flex-col items-center gap-2 hover:shadow-md transition-all">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100/80 text-emerald-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-900">4 فروع في خدمتك</h3>
                    <p class="text-[11px] text-gray-500 font-medium">استلم طلبك مجاناً وفوراً من أقرب فرع</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Categories Strip -->
    <x-storefront.sections.categories-strip :categories="$categories" />

    <!-- 4. Today's Deals Section -->
    <x-storefront.sections.deals-section :deals="$deals" />

    <!-- 5. Featured Dairy & Groceries -->
    <x-storefront.sections.products-carousel 
        title="منتجات التوحيد الأكثر طلباً" 
        subtitle="الأعلى تقييماً وإقبالاً" 
        :products="$featuredProducts" 
        :viewAllRoute="route('site.products')" 
        badge="⭐" />

    <!-- 6. Bundles & Value Packs Section -->
    <x-storefront.sections.bundles-section :bundles="$bundles" />

    <!-- 7. Latest Fresh Arrivals -->
    <x-storefront.sections.products-carousel 
        title="وصل حديثاً للمتجر" 
        subtitle="طازج يومياً من مزارعنا" 
        :products="$latestProducts" 
        :viewAllRoute="route('site.products')" 
        badge="🆕" />

    <!-- 8. Ameriya Branches Strip -->
    <x-storefront.sections.branches-strip :branches="$branches" />

</div>
@endsection
