{{-- resources/views/components/storefront/layout/mobile-menu.blade.php --}}
@php
    $storePhone = settings('store_phone', '01000000000');
    $storeWhatsApp = settings('store_whatsapp', '201000000000');
    $siteLogo = get_site_logo();
    $siteName = settings('site_name', 'ألبان وسوبرماركت التوحيد');
    $navCategories = \Illuminate\Support\Facades\Cache::remember('nav_categories_cache', 3600, function () {
        return \App\Models\Category::select('id', 'name', 'slug')->take(12)->get();
    });
@endphp

<div x-data="{ open: false, catOpen: false }" 
     @open-mobile-menu.window="open = true" 
     @keydown.escape.window="open = false" 
     class="relative z-50 lg:hidden"
     dir="rtl">
    
    <!-- Backdrop Overlay -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50"></div>

    <!-- Off-canvas Drawer Panel -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-[320px] sm:max-w-xs w-full bg-white shadow-2xl flex flex-col z-50 overflow-hidden">
        
        <!-- Header of Drawer -->
        <div class="p-4 bg-gradient-to-r from-red-700 via-red-600 to-orange-500 text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-2.5 min-w-0">
                @if($siteLogo)
                    <div class="w-10 h-10 rounded-2xl bg-white p-1 flex items-center justify-center shadow-md shrink-0 overflow-hidden">
                        <img src="{{ $siteLogo }}" alt="{{ $siteName }}" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg font-black shrink-0 border border-white/30">
                        🥛
                    </div>
                @endif
                <div class="min-w-0">
                    <h2 class="font-black text-sm text-white truncate leading-tight">{{ $siteName }}</h2>
                    <p class="text-[10px] text-orange-100 font-bold mt-0.5">العامرية • 4 فروع • توصيل سريع</p>
                </div>
            </div>
            <button @click="open = false" type="button" class="w-8 h-8 rounded-xl bg-white/15 hover:bg-white/25 text-white flex items-center justify-center transition-colors shrink-0 cursor-pointer" aria-label="إغلاق">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Search in Mobile Menu -->
        <div class="p-3 border-b border-orange-100 bg-canvas-base/60">
            <form action="{{ route('site.products') }}" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       placeholder="ابحث عن ألبان، جبن، لحوم، بقالة..." 
                       class="w-full bg-white border border-orange-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 rounded-xl py-2 pr-9 pl-3 text-xs text-gray-900 font-bold placeholder-gray-400 outline-none transition-all shadow-2xs" />
                <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-600 transition-colors" aria-label="بحث">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Scrollable Navigation Area -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4 admin-scrollbar">
            
            <!-- Primary Navigation Links -->
            <div class="space-y-1">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider px-2 mb-1 block">التصفح الرئيسي</span>
                
                {{-- 1. Home --}}
                <a href="{{ route('site.home') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('site.home') ? 'bg-red-50 text-red-700 border border-red-200 font-black' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}">
                    <span class="text-base">🏠</span>
                    <span>الرئيسية</span>
                </a>

                {{-- 2. Weekly Offers Magazine (Highlighted) --}}
                <a href="{{ route('offers.index') }}" @click="open = false" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('offers.*') ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-md shadow-red-500/20' : 'bg-red-50/70 text-red-700 border border-red-200/80 hover:bg-red-100/70' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">📰</span>
                        <span class="font-black">مجلة العروض والتخفيضات</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black {{ request()->routeIs('offers.*') ? 'bg-white text-red-600' : 'bg-red-600 text-white animate-pulse' }}">
                        جديد 🔥
                    </span>
                </a>

                {{-- 3. All Products Catalog --}}
                <a href="{{ route('site.products') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('site.products') ? 'bg-red-50 text-red-700 border border-red-200 font-black' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}">
                    <span class="text-base">🛍️</span>
                    <span>كل المنتجات والأسعار</span>
                </a>

                {{-- 4. Shop / Filter --}}
                <a href="{{ route('shop') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('shop') || request()->routeIs('product.shop') ? 'bg-red-50 text-red-700 border border-red-200 font-black' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}">
                    <span class="text-base">🛒</span>
                    <span>المتجر والتصنيفات</span>
                </a>

                {{-- 5. Branches (4 branches) --}}
                <a href="{{ Route::has('branches.index') ? route('branches.index') : url('/branches') }}" @click="open = false" 
                   class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('branches*') ? 'bg-red-50 text-red-700 border border-red-200 font-black' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}">
                    <div class="flex items-center gap-3">
                        <span class="text-base">📍</span>
                        <span>فروعنا بالعامرية</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-800 text-[10px] font-black">4 فروع</span>
                </a>

                {{-- 6. Track Order --}}
                <a href="{{ route('orders.track') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('orders.track') ? 'bg-red-50 text-red-700 border border-red-200 font-black' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}">
                    <span class="text-base">🛵</span>
                    <span>تتبع حالة طلبك</span>
                </a>
            </div>

            <!-- Categories Accordion Dropdown -->
            <div class="pt-2 border-t border-orange-100">
                <button @click="catOpen = !catOpen" type="button" 
                        class="w-full flex items-center justify-between p-2 rounded-xl bg-orange-50/50 hover:bg-orange-50 text-xs font-black text-orange-950 transition-colors cursor-pointer border border-orange-100">
                    <span class="flex items-center gap-2">
                        <span>🏷️</span>
                        <span>أقسام السوبرماركت</span>
                    </span>
                    <svg class="w-4 h-4 text-orange-600 transition-transform duration-200" :class="{'rotate-180': catOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div x-show="catOpen" x-collapse x-cloak class="mt-2 space-y-1 pr-2 border-r-2 border-orange-200">
                    @foreach($navCategories as $cat)
                        <a href="{{ route('category.product', $cat->id) }}" @click="open = false" 
                           class="flex items-center justify-between px-3 py-1.5 rounded-lg text-xs font-bold text-gray-700 hover:text-red-600 hover:bg-orange-50 transition-colors">
                            <span>• {{ $cat->name }}</span>
                            <span class="text-orange-400 text-xs">←</span>
                        </a>
                    @endforeach
                    <a href="{{ route('site.products') }}" @click="open = false" class="block px-3 py-1.5 text-xs font-black text-red-600 hover:underline">
                        عرض جميع الأقسام والمنتجات ←
                    </a>
                </div>
            </div>

            <!-- Store Information & Contact Links -->
            <div class="pt-2 border-t border-orange-100 space-y-1">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider px-2 mb-1 block">معلومات وخدمة العملاء</span>

                {{-- Contact Us --}}
                <a href="{{ route('contact') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('contact') ? 'bg-red-50 text-red-700 border border-red-200 font-black' : 'text-gray-700 hover:bg-orange-50 hover:text-red-700' }}">
                    <span class="text-base">📞</span>
                    <span class="font-black text-gray-900">تواصل معنا والدعم الفني</span>
                </a>

                {{-- About Us --}}
                <a href="{{ Route::has('about') ? route('about') : url('/about') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:text-red-700 hover:bg-orange-50 transition-colors">
                    <span class="text-sm">ℹ️</span>
                    <span>عن ألبان التوحيد</span>
                </a>

                {{-- Careers --}}
                <a href="{{ Route::has('careers.index') ? route('careers.index') : url('/careers') }}" @click="open = false" 
                   class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:text-red-700 hover:bg-orange-50 transition-colors">
                    <span class="text-sm">💼</span>
                    <span>وظائف التوحيد (انضم لفريقنا)</span>
                </a>
            </div>
        </div>

        <!-- Bottom Actions (WhatsApp + Hotline) -->
        <div class="p-3 bg-canvas-base border-t border-orange-100 space-y-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeWhatsApp) }}?text={{ urlencode('مرحباً ألبان وسوبرماركت التوحيد، أود الاستفسار وطلب أوردر.') }}" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#20bd5a] text-white font-black py-2.5 px-4 rounded-xl text-xs transition-all shadow-md shadow-emerald-600/20 active:scale-95 cursor-pointer">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                </svg>
                <span>طلب أوردر فوري عبر واتساب</span>
            </a>

            <a href="tel:{{ $storePhone }}" class="w-full flex items-center justify-center gap-2 border border-orange-200 bg-white text-gray-900 hover:bg-orange-50 font-bold py-2 px-4 rounded-xl text-xs transition-colors shadow-2xs" dir="ltr">
                <span class="font-black">{{ $storePhone }}</span>
                <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </a>
        </div>
    </div>
</div>
