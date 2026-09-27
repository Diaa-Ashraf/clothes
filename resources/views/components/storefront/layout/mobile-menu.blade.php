{{-- resources/views/components/storefront/layout/mobile-menu.blade.php --}}
@php
    $storePhone = settings('store_phone', '01000000000');
    $storeWhatsApp = settings('store_whatsapp', '201000000000');
    $navCategories = \Illuminate\Support\Facades\Cache::remember('nav_categories_cache', 3600, function () {
        return \App\Models\Category::select('id', 'name', 'slug')->take(10)->get();
    });
@endphp

<div x-data="{ open: false }" 
     @open-mobile-menu.window="open = true" 
     @keydown.escape.window="open = false" 
     class="relative z-50 lg:hidden">
    
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
         class="fixed inset-0 bg-charcoal-heading/60 backdrop-blur-sm"></div>

    <!-- Off-canvas Drawer Panel -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-2xl flex flex-col z-50 overflow-y-auto">
        
        <!-- Header of Drawer -->
        <div class="p-4 bg-primary-800 text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center font-black">
                    ت
                </div>
                <div>
                    <h2 class="font-bold text-sm">ألبان وسوبرماركت التوحيد</h2>
                    <p class="text-[10px] text-white/70">العامرية • الإسكندرية</p>
                </div>
            </div>
            <button @click="open = false" type="button" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white" aria-label="إغلاق">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Search in Mobile Menu -->
        <div class="p-4 border-b border-cream bg-canvas-base/50">
            <form action="{{ route('site.products') }}" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       placeholder="ابحث عن منتج..." 
                       class="w-full bg-white border border-cream focus:border-primary-600 rounded-lg py-2 pr-9 pl-3 text-xs outline-none" />
                <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-charcoal-muted" aria-label="بحث">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Links List -->
        <div class="flex-1 px-4 py-3 divide-y divide-cream/60 text-sm">
            <div class="py-2 space-y-1">
                <a href="{{ route('site.home') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-charcoal hover:bg-primary-50 hover:text-primary-800 font-semibold">
                    <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>الرئيسية</span>
                </a>

                <a href="{{ route('site.products') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-charcoal hover:bg-primary-50 hover:text-primary-800 font-semibold">
                    <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span>كل المنتجات</span>
                </a>

                <a href="{{ route('product.shop') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-charcoal hover:bg-primary-50 hover:text-primary-800 font-semibold">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>عروض التوحيد</span>
                </a>

                <a href="{{ Route::has('branches.index') ? route('branches.index') : url('/branches') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-charcoal hover:bg-primary-50 hover:text-primary-800 font-semibold">
                    <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>فروعنا بالعامرية</span>
                </a>
            </div>

            <!-- Categories Accordion / List -->
            <div class="py-3" x-data="{ catOpen: true }">
                <button @click="catOpen = !catOpen" type="button" class="w-full flex items-center justify-between text-xs font-bold text-charcoal-muted uppercase tracking-wider px-2 mb-2">
                    <span>أقسام المتجر</span>
                    <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': catOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="catOpen" class="space-y-1 pr-2">
                    @foreach($navCategories as $cat)
                        <a href="{{ route('category.product', $cat->id) }}" @click="open = false" class="block px-3 py-1.5 text-xs text-charcoal-secondary hover:text-primary-800 hover:bg-canvas-subtle rounded-md">
                            • {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Company Pages & Careers -->
            <div class="py-2 space-y-1">
                <a href="{{ Route::has('about') ? route('about') : url('/about') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-charcoal-secondary hover:text-primary-800">
                    <span>عن ألبان التوحيد</span>
                </a>
                <a href="{{ Route::has('careers.index') ? route('careers.index') : url('/careers') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-charcoal-secondary hover:text-primary-800">
                    <span>وظائف التوحيد (انضم لفريقنا)</span>
                </a>
                <a href="{{ route('contact') }}" @click="open = false" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-xs font-medium text-charcoal-secondary hover:text-primary-800">
                    <span>تواصل معنا</span>
                </a>
            </div>
        </div>

        <!-- Bottom Actions (WhatsApp + Phone Hotline) -->
        <div class="p-4 bg-canvas-base border-t border-cream space-y-2">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeWhatsApp) }}" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-colors shadow">
                <span>طلب أوردر عبر واتساب</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                </svg>
            </a>

            <a href="tel:{{ $storePhone }}" class="w-full flex items-center justify-center gap-2 border border-cream bg-white text-charcoal hover:bg-canvas-subtle font-bold py-2.5 px-4 rounded-xl text-xs transition-colors" dir="ltr">
                <span>{{ $storePhone }}</span>
                <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            </a>
        </div>
    </div>
</div>
