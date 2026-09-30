{{-- resources/views/components/storefront/layout/header.blade.php --}}
@php
    $cartCount = 0;
    try {
        $cartCount = app(\App\Services\CartService::class)->getCount();
    } catch (\Throwable $e) {
        $cartCount = session('cart') ? count(session('cart')) : 0;
    }

    $navCategories = \Illuminate\Support\Facades\Cache::remember('nav_categories_cache', 3600, function () {
        return \App\Models\Category::select('id', 'name', 'slug')->take(8)->get();
    });
@endphp

<header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-cream" x-data="{ mobileSearchOpen: false }">
    <!-- Main Header Bar -->
    <div class="container mx-auto px-4 py-3 sm:py-4">
        <div class="flex items-center justify-between gap-4 md:gap-8">
            
            <!-- Mobile Menu Toggle Button -->
            <button type="button" 
                    @click="$dispatch('open-mobile-menu')"
                    class="lg:hidden p-2 -mr-2 text-charcoal-secondary hover:text-primary-700 hover:bg-canvas-subtle rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
                    aria-label="القائمة الرئيسية">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Brand Logo & Supermarket Name -->
            <div class="flex items-center gap-3">
                <a href="{{ route('site.home') }}" class="flex items-center gap-3 group">
                    @if(get_site_logo())
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-white p-1 flex items-center justify-center shadow-lg shadow-red-500/10 group-hover:scale-105 transition-all duration-300 border-2 border-orange-200 overflow-hidden shrink-0">
                            <img src="{{ get_site_logo() }}" alt="{{ settings('site_name', 'ألبان التوحيد') }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-gradient-to-tr from-red-700 via-red-600 to-orange-500 text-white flex items-center justify-center shadow-lg shadow-red-500/25 group-hover:scale-105 transition-all duration-300 border-2 border-orange-200 shrink-0">
                            <span class="text-2xl font-black font-sans">🥛</span>
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="text-xl sm:text-2xl font-black text-red-600 tracking-tight font-sans">
                                {{ settings('site_name', 'ألبان التوحيد') }}
                            </span>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-orange-500 text-white uppercase tracking-wider">ماركت</span>
                        </div>
                        <span class="text-[11px] text-gray-500 font-bold">
                            {{ settings('site_slogan', 'ماركت وألبان بلدية • 4 فروع بالعامرية') }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Instant Search Bar (Desktop) -->
            <div class="hidden md:flex flex-1 max-w-xl mx-4"
                 x-data="{
                    query: '{{ request('q', request('search')) }}',
                    results: [],
                    loading: false,
                    open: false,
                    fetchResults() {
                        if (this.query.trim().length < 2) {
                            this.results = [];
                            this.open = false;
                            return;
                        }
                        this.loading = true;
                        fetch('{{ route('search.autocomplete') }}?q=' + encodeURIComponent(this.query))
                            .then(res => res.json())
                            .then(data => {
                                this.results = data.results || [];
                                this.open = this.results.length > 0;
                                this.loading = false;
                            })
                            .catch(() => {
                                this.loading = false;
                            });
                    }
                 }"
                 @click.outside="open = false">
                <form action="{{ route('search.index') }}" method="GET" class="w-full relative">
                    <input type="text" 
                           name="q" 
                           x-model="query"
                           @input.debounce.300ms="fetchResults()"
                           @focus="if(results.length > 0) open = true"
                           placeholder="ابحث عن ألبان، جبن، بقالة، لحوم، منظفات..." 
                           autocomplete="off"
                           class="w-full bg-canvas-base border border-cream hover:border-primary-300 focus:border-primary-600 focus:ring-2 focus:ring-primary-100 rounded-full py-2.5 pr-11 pl-10 text-sm text-charcoal placeholder-charcoal-muted/70 transition-all outline-none" />
                    <button type="submit" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-charcoal-muted hover:text-primary-700 transition-colors" aria-label="بحث">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <div x-show="loading" x-cloak class="absolute left-3.5 top-1/2 -translate-y-1/2">
                        <svg class="w-4 h-4 animate-spin text-primary-700" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </div>

                    <!-- Live Autocomplete Dropdown -->
                    <div x-show="open" 
                         x-cloak 
                         x-transition 
                         class="absolute top-full right-0 left-0 mt-2 bg-white rounded-2xl shadow-xl border border-cream overflow-hidden z-50 divide-y divide-cream/60">
                        <template x-for="item in results" :key="item.id">
                            <a :href="item.url" class="flex items-center gap-3 p-3 hover:bg-canvas-subtle transition-colors group">
                                <img :src="item.image_url" :alt="item.name" class="w-11 h-11 object-contain rounded-lg bg-canvas-base p-1 border border-cream shrink-0">
                                <div class="flex-1 min-w-0 text-right">
                                    <div class="text-xs font-bold text-charcoal group-hover:text-primary-800 truncate" x-text="item.name"></div>
                                    <div class="text-[10px] text-charcoal-muted" x-text="item.category"></div>
                                </div>
                                <div class="text-xs font-black text-primary-800 tabular-nums shrink-0" x-text="item.price"></div>
                            </a>
                        </template>
                        <div class="p-2.5 bg-canvas-base/50 text-center">
                            <button type="submit" class="text-xs font-bold text-primary-800 hover:text-primary-900 transition-colors">
                                عرض جميع نتائج البحث لـ "<span x-text="query"></span>" ←
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- User Actions (Search on mobile, Wishlist, Cart, Profile) -->
            <div class="flex items-center gap-1.5 sm:gap-3">
                <!-- Mobile Search Toggle -->
                <button type="button" 
                        @click="mobileSearchOpen = !mobileSearchOpen"
                        class="md:hidden p-2 text-charcoal-secondary hover:text-primary-700 hover:bg-canvas-subtle rounded-full"
                        aria-label="بحث في المنتجات">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Wishlist -->
                <a href="{{ route('wishlist.index') }}" 
                   class="relative p-2 text-charcoal-secondary hover:text-primary-700 hover:bg-canvas-subtle rounded-full transition-colors"
                   title="قائمة المفضلة">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                <!-- Cart Action Button with Drawer Dispatch -->
                <button type="button" 
                        @click.prevent="$dispatch('open-cart-drawer')"
                        class="relative flex items-center gap-2 py-2 px-3 sm:px-3.5 bg-primary-50 hover:bg-primary-100 text-primary-800 rounded-full border border-primary-200 transition-all group cursor-pointer"
                        title="سلة المشتريات">
                    <div class="relative">
                        <svg class="w-5 h-5 text-primary-700 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span id="cart-badge-count" 
                              class="absolute -top-2 -right-2 bg-accent-600 text-white font-bold text-[10px] min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center shadow">
                            {{ $cartCount }}
                        </span>
                    </div>
                    <span class="hidden sm:inline font-bold text-xs">السلة</span>
                </button>

                <!-- User Account -->
                @auth
                    <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
                        <button type="button" 
                                @click="userMenuOpen = !userMenuOpen"
                                class="p-2 text-charcoal-secondary hover:text-primary-700 hover:bg-canvas-subtle rounded-full transition-colors flex items-center gap-1 cursor-pointer"
                                title="حسابي">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </button>

                        <div x-show="userMenuOpen" 
                             x-cloak 
                             x-transition 
                             class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-cream py-2 z-50 divide-y divide-cream/60 text-xs">
                            <div class="px-4 py-2">
                                <div class="font-bold text-charcoal truncate">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-charcoal-muted truncate">{{ auth()->user()->phone }}</div>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('account.profile') }}" class="block px-4 py-2 hover:bg-canvas-subtle text-charcoal hover:text-primary-800 font-semibold">
                                    الملف الشخصي
                                </a>
                                <a href="{{ route('account.addresses') }}" class="block px-4 py-2 hover:bg-canvas-subtle text-charcoal hover:text-primary-800 font-semibold">
                                    العناوين المحفوظة
                                </a>
                                <a href="{{ route('account.orders') }}" class="block px-4 py-2 hover:bg-canvas-subtle text-charcoal hover:text-primary-800 font-semibold">
                                    سجل طلباتي
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-primary-50 text-primary-800 font-bold">
                                        لوحة التحكم
                                    </a>
                                @endif
                            </div>
                            <div class="pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-right px-4 py-2 text-rose-600 hover:bg-rose-50 font-semibold cursor-pointer">
                                        تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-charcoal-secondary hover:text-primary-700 px-3 py-2 rounded-lg hover:bg-canvas-subtle transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        <span>دخول</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Search Dropdown Bar -->
        <div x-show="mobileSearchOpen" 
             x-cloak 
             x-transition 
             class="mt-3 md:hidden pt-2 border-t border-cream"
             x-data="{
                mobileQuery: '{{ request('q', request('search')) }}',
                mobileResults: [],
                mobileLoading: false,
                mobileDropdownOpen: false,
                fetchMobileResults() {
                    if (this.mobileQuery.trim().length < 2) {
                        this.mobileResults = [];
                        this.mobileDropdownOpen = false;
                        return;
                    }
                    this.mobileLoading = true;
                    fetch('{{ route('search.autocomplete') }}?q=' + encodeURIComponent(this.mobileQuery))
                        .then(res => res.json())
                        .then(data => {
                            this.mobileResults = data.results || [];
                            this.mobileDropdownOpen = this.mobileResults.length > 0;
                            this.mobileLoading = false;
                        })
                        .catch(() => {
                            this.mobileLoading = false;
                        });
                }
             }">
            <form action="{{ route('search.index') }}" method="GET" class="relative">
                <input type="text" 
                       name="q" 
                       x-model="mobileQuery"
                       @input.debounce.300ms="fetchMobileResults()"
                       placeholder="ابحث عن ألبان، جبن، بقالة، لحوم..." 
                       autocomplete="off"
                       class="w-full bg-canvas-base border border-cream focus:border-primary-600 focus:ring-2 focus:ring-primary-100 rounded-full py-2 pr-10 pl-9 text-xs text-charcoal outline-none" />
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-charcoal-muted" aria-label="بحث">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <div x-show="mobileLoading" x-cloak class="absolute left-3 top-1/2 -translate-y-1/2">
                    <svg class="w-3.5 h-3.5 animate-spin text-primary-700" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>

                <!-- Mobile Autocomplete Dropdown -->
                <div x-show="mobileDropdownOpen" 
                     x-cloak 
                     x-transition 
                     class="absolute top-full right-0 left-0 mt-2 bg-white rounded-xl shadow-xl border border-cream overflow-hidden z-50 divide-y divide-cream/60">
                    <template x-for="item in mobileResults" :key="item.id">
                        <a :href="item.url" class="flex items-center gap-2.5 p-2.5 hover:bg-canvas-subtle transition-colors">
                            <img :src="item.image_url" :alt="item.name" class="w-9 h-9 object-contain rounded-lg bg-canvas-base p-1 border border-cream shrink-0">
                            <div class="flex-1 min-w-0 text-right">
                                <div class="text-[11px] font-bold text-charcoal truncate" x-text="item.name"></div>
                                <div class="text-[9px] text-charcoal-muted" x-text="item.category"></div>
                            </div>
                            <div class="text-[11px] font-black text-primary-800 tabular-nums shrink-0" x-text="item.price"></div>
                        </a>
                    </template>
                    <div class="p-2 bg-canvas-base/50 text-center">
                        <button type="submit" class="text-[11px] font-bold text-primary-800 hover:text-primary-900">
                            عرض جميع النتائج لـ "<span x-text="mobileQuery"></span>" ←
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Navigation Menu (Desktop) -->
    <nav class="hidden lg:block border-t border-cream/70 bg-canvas-base/60 text-xs font-semibold">
        <div class="container mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-1">
                <!-- All Categories Dropdown Menu -->
                <div class="relative py-2.5" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" 
                            type="button" 
                            class="flex items-center gap-2 px-3.5 py-1.5 bg-primary-800 text-white rounded-lg hover:bg-primary-900 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>جميع الأقسام</span>
                        <svg class="w-3.5 h-3.5 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" 
                         x-cloak 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-cream py-2 z-50">
                        @foreach($navCategories as $cat)
                            <a href="{{ route('category.product', $cat->id) }}" 
                               class="flex items-center justify-between px-4 py-2 text-charcoal hover:bg-canvas-subtle hover:text-primary-800 transition-colors">
                                <span>{{ $cat->name }}</span>
                                <svg class="w-3.5 h-3.5 text-charcoal-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endforeach
                        <div class="border-t border-cream my-1"></div>
                        <a href="{{ route('site.products') }}" class="block px-4 py-2 text-center text-primary-700 font-bold hover:underline">
                            عرض كل الأقسام والمنتجات ←
                        </a>
                    </div>
                </div>

                <!-- Nav Links -->
                <a href="{{ route('site.home') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->routeIs('site.home') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    الرئيسية
                </a>

                <a href="{{ route('site.products') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->routeIs('site.products') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    كل المنتجات
                </a>

                <a href="{{ route('shop') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->routeIs('shop') || request()->routeIs('product.shop') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    المتجر
                </a>

                <a href="{{ route('offers.index') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->routeIs('offers.index') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-red-600 hover:text-red-700 font-bold' }} flex items-center gap-1">
                    <span>مجلة العروض</span>
                    <span class="px-1.5 py-0.2 rounded-full bg-red-600 text-white text-[9px] font-black animate-pulse">جديد</span>
                </a>

                <a href="{{ Route::has('branches.index') ? route('branches.index') : url('/branches') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->is('branches*') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    فروعنا (4 فروع)
                </a>

                <a href="{{ route('orders.track') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->routeIs('orders.track') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    تتبع طلبك
                </a>

                <a href="{{ Route::has('about') ? route('about') : url('/about') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->is('about*') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    عن التوحيد
                </a>

                <a href="{{ Route::has('careers.index') ? route('careers.index') : url('/careers') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->is('careers*') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    انضم لفريقنا
                </a>

                <a href="{{ route('contact') }}" 
                   class="px-3.5 py-3 transition-colors {{ request()->routeIs('contact') ? 'text-primary-800 border-b-2 border-primary-700 font-bold' : 'text-charcoal hover:text-primary-700' }}">
                    تواصل معنا
                </a>
            </div>

            <!-- Quick Hotline Callout -->
            <div class="flex items-center gap-2 text-primary-800 font-bold">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>خدمة التوصيل السريع بالعامرية</span>
            </div>
        </div>
    </nav>
</header>
