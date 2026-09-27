@php
$storePhone = preg_replace('/[^0-9]/', '', settings('whatsapp_number', '963999999999'));
$waConciergeUrl = "https://wa.me/{$storePhone}?text=" . urlencode("مرحباً بك، أود الاستفسار عن المقتنيات والهدايا المتوفرة لدى المتجر.");
@endphp

<div x-data="{
 mobileOpen: false,
 mobileSearchOpen: false,
 cartDrawerOpen: false,
 categoriesMenuOpen: false,
 isScrolled: false,
 cart: { count: 0, items: [], subtotal_usd: 0, formatted_subtotal_usd: '0.00 ج.م' },
 wishlistCount: {{ auth()->check() ? auth()->user()->wishlistItems()->count() : 0 }},
 searchQuery: '',
 searchResults: [],
 searchLoading: false,
 fetchCart() {
 fetch('{{ route('cart.payload') }}')
 .then(r => r.json())
 .then(data => {
 this.cart = data;
 })
 .catch(() => {});
 },
 performSearch() {
 if (this.searchQuery.trim().length < 2) {
 this.searchResults = [];
 return;
 }
 this.searchLoading = true;
 fetch('{{ route('search.ajax') }}?q=' + encodeURIComponent(this.searchQuery))
 .then(r => r.json())
 .then(data => {
 this.searchResults = data.results || [];
 this.searchLoading = false;
 })
 .catch(() => {
 this.searchLoading = false;
 });
 },
 init() {
 this.fetchCart();
 window.addEventListener('scroll', () => {
 this.isScrolled = window.scrollY > 40;
 });
 window.addEventListener('cart-updated', (e) => {
 if (e.detail && e.detail.items) {
 this.cart = e.detail;
 } else {
 this.fetchCart();
 }
 });
 window.addEventListener('wishlist-updated', (e) => {
 if (typeof e.detail !== 'undefined') {
 this.wishlistCount = e.detail;
 }
 });
 window.addEventListener('open-mini-cart', () => {
 this.cartDrawerOpen = true;
 });
 }
 }"
 :class="isScrolled ? 'shadow-2xl bg-[#C5A059]/95 backdrop-blur-md border-b border-[#B38E44] py-0' : 'bg-[#C5A059] shadow-md'"
 class="w-full sticky top-0 z-50 text-[#18181B] transition-all duration-300 ease-in-out">

 {{-- Layer A: Top Utility Marquee Bar (Collapses smoothly on scroll) --}}
 <div x-show="!isScrolled"
 x-collapse
 x-transition:enter="transition ease-out duration-300"
 x-transition:leave="transition ease-in duration-200"
 class="bg-[#18181B] text-gray-200 text-[10px] sm:text-[11px] py-1.5 px-3 sm:px-8 border-b border-[#C5A059]/30 overflow-hidden">
 <div class="w-full flex items-center justify-between gap-2 sm:gap-4">
 {{-- Marquee Ticker --}}
 <div class="flex-1 overflow-hidden whitespace-nowrap">
 <div class="inline-block animate-marquee hover:pause">
 <span class="mx-3 sm:mx-6 text-[#EADBCC] font-bold">{{ __('أسرع قبل نفاد الكمية! أفضل العروض بانتظارك بضغطة واحدة!') }}</span>
 <span class="mx-3 sm:mx-6 text-gray-300">{{ __('تسوق الآن واستمتع بتجربة مختلفة تماماً من الهدايا والإكسسوارات الفاخرة!') }}</span>
 <span class="mx-3 sm:mx-6 text-[#EADBCC] font-bold">{{ __('شحن سريع وموثوق لكافة المحافظات مع الدفع عند الاستلام') }}</span>
 </div>
 </div>

 {{-- Currency & Language Controls --}}
 <div class="flex items-center gap-2 sm:gap-3 shrink-0">
 <span class="text-[9px] sm:text-[10px] font-bold text-[#18181B] bg-[#EADBCC] rounded-full px-2 py-0.5">USD ($)</span>

 <div class="flex items-center gap-1 text-[9px] sm:text-[10px]">
 @if(app()->getLocale() === 'ar')
 <a href="{{ route('change.language', 'en') }}" class="text-gray-200 hover:text-[#EADBCC] px-1 font-semibold">EN</a>
 @else
 <a href="{{ route('change.language', 'ar') }}" class="text-gray-200 hover:text-[#EADBCC] px-1 font-semibold">العربية</a>
 @endif
 </div>
 </div>
 </div>
 </div>

 {{-- Layer B: Primary Header Action Bar (Full-Width Responsive Layout) --}}
 <div :class="isScrolled ? 'py-1.5 sm:py-2.5' : 'py-2 sm:py-3.5'" class="w-full px-2 sm:px-6 md:px-8 flex items-center justify-between gap-1 sm:gap-4 transition-all duration-300">

 {{-- Start Group: Mobile Menu Button & Brand Logo --}}
 <div class="flex items-center gap-1 sm:gap-3 shrink-0 min-w-0">
 {{-- Mobile Hamburger --}}
 <button @click="mobileOpen = !mobileOpen" type="button" class="lg:hidden p-1 sm:p-1.5 text-[#18181B] hover:text-white shrink-0 focus:outline-none" aria-label="Toggle navigation menu">
 <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
 </svg>
 </button>

 {{-- Brand Logo (Right side in RTL) --}}
 @php
 $headerLogo = get_site_logo();
 @endphp
 <a href="{{ route('site.home') }}" class="flex items-center gap-2 group shrink-0">
 @if($headerLogo)
 <img src="{{ $headerLogo }}" alt="{{ settings('site_name', 'REGALEST') }}" class="h-6 sm:h-9 md:h-10 max-w-[42px] sm:max-w-[55px] object-contain drop-shadow-sm rounded-lg">
 @endif
 <div class="flex flex-col text-start">
 <span class="font-royal text-xs sm:text-lg md:text-2xl font-extrabold tracking-wider text-[#18181B] group-hover:text-white transition-colors leading-none truncate max-w-[110px] sm:max-w-none">
 {{ settings('site_name', 'REGALEST') }}
 </span>
 <span class="text-[6px] sm:text-[9px] uppercase tracking-widest text-[#18181B]/80 font-bold hidden xs:inline">
 {{ settings('site_slogan', 'PREMIUM GIFTS') }}
 </span>
 </div>
 </a>
 </div>

 {{-- Center Search Form (Desktop & Tablet: Inline pill bar) --}}
 <div class="hidden sm:block flex-1 max-w-lg md:max-w-2xl relative mx-2 sm:mx-4" @click.outside="searchResults = []">
 <form action="{{ route('product.shop') }}" method="GET" class="relative w-full flex items-center">
 <input type="text"
 name="search"
 x-model="searchQuery"
 @input.debounce.300ms="performSearch()"
 placeholder="{{ __('أنا أبحث عن...') }}"
 class="w-full ps-4 pe-20 py-2 sm:py-2.5 text-xs rounded-full bg-white text-[#18181B] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#18181B] border-0 transition-all shadow-sm">

 <button type="submit" class="absolute end-1 inset-y-1 px-4 rounded-full bg-[#18181B] hover:bg-black text-white font-bold text-xs transition-colors flex items-center justify-center cursor-pointer">
 <span>{{ __('بحث') }}</span>
 </button>
 </form>

 {{-- Live Search Dropdown for Desktop --}}
 <div x-show="searchResults.length > 0"
 x-transition
 class="absolute start-0 end-0 mt-2 bg-white text-[#18181B] rounded-2xl shadow-2xl border border-gray-100 p-2 z-50 max-h-96 overflow-y-auto"
 style="display: none;">
 <div class="px-3 py-1.5 text-[11px] font-bold text-gray-400 border-b border-gray-100">{{ __('نتائج البحث المباشر') }}</div>
 <template x-for="item in searchResults" :key="item.id">
 <a :href="item.url" class="flex items-center gap-3 p-2 hover:bg-[#F8F9FA] rounded-xl transition-colors">
 <img :src="item.image_url" class="w-11 h-11 object-contain rounded-lg bg-gray-50 border p-1 shrink-0">
 <div class="flex-1 min-w-0 text-start">
 <h4 class="text-xs font-semibold text-[#18181B] truncate" x-text="item.name"></h4>
 <div class="flex items-center gap-2 mt-0.5">
 <span class="text-xs font-bold text-[#C5A059]" x-text="item.formatted_price_usd"></span>
 </div>
 </div>
 </a>
 </template>
 </div>
 </div>

 {{-- Action Hub: Mobile Search Toggle, User Profile/Login, Wishlist, Cart --}}
 <div class="flex items-center gap-0.5 sm:gap-2 md:gap-4 text-[#18181B] shrink-0">
 
 {{-- Mobile Search Trigger Button --}}
 <button @click="mobileSearchOpen = !mobileSearchOpen" 
 type="button" 
 class="sm:hidden p-1 sm:p-2 text-[#18181B] hover:text-white transition-colors focus:outline-none rounded-lg"
 :class="mobileSearchOpen ? 'text-white bg-black/20' : ''"
 aria-label="{{ __('بحث') }}"
 title="{{ __('بحث') }}">
 <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
 </svg>
 </button>

 {{-- User Account Link --}}
 <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" 
 class="flex items-center gap-1.5 text-xs text-[#18181B] hover:text-white transition-colors p-1 sm:p-1.5 rounded-lg font-bold"
 title="{{ auth()->check() ? auth()->user()->name : __('تسجيل الدخول') }}">
 <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
 </svg>
 <span class="hidden md:inline font-bold max-w-[100px] truncate">{{ auth()->check() ? auth()->user()->name : __('تسجيل الدخول') }}</span>
 </a>

 {{-- Wishlist Button --}}
 @if(settings('wishlist_enabled', true))
 <a href="{{ route('wishlist.index') }}" 
 class="relative flex items-center justify-center p-1 sm:p-2 text-[#18181B] hover:text-white transition-colors rounded-lg" 
 title="{{ __('المفضلة') }}">
 <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
 </svg>
 <span x-show="wishlistCount > 0"
 x-text="wishlistCount"
 class="absolute -top-0.5 -end-0.5 w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-[#18181B] text-white text-[8px] sm:text-[9px] font-bold flex items-center justify-center shadow-xs"></span>
 </a>
 @endif

 {{-- Cart Button Trigger --}}
 <button @click="cartDrawerOpen = true" 
 type="button" 
 class="flex items-center gap-1 p-1 sm:px-3 sm:py-2 rounded-full bg-[#18181B] hover:bg-black text-white transition-all cursor-pointer shadow-sm">
 <div class="relative flex items-center justify-center">
 <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 text-[#C5A059]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
 </svg>
 <span x-show="cart.count > 0"
 x-text="cart.count"
 class="absolute -top-1 -end-1 w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-[#C5A059] text-[#18181B] text-[8px] sm:text-[9px] font-black flex items-center justify-center shadow-xs"></span>
 </div>
 <div class="hidden md:flex flex-col text-start text-[11px] leading-tight">
 <span class="text-gray-300 text-[9px] font-medium">{{ __('السلة') }}</span>
 <span class="font-bold text-[#EADBCC]" x-text="cart.formatted_subtotal_usd"></span>
 </div>
 </button>
 </div>
 </div>

 {{-- Layer Mobile Search Bar (Smoothly collapses / expands on small mobile screens) --}}
 <div x-show="mobileSearchOpen"
 x-collapse
 x-transition:enter="transition ease-out duration-200"
 x-transition:leave="transition ease-in duration-150"
 class="sm:hidden px-3 py-2.5 bg-[#B38E44] border-t border-[#18181B]/10 relative"
 @click.outside="searchResults = []; mobileSearchOpen = false"
 style="display: none;">
 <form action="{{ route('product.shop') }}" method="GET" class="relative w-full flex items-center">
 <input type="text"
 name="search"
 x-model="searchQuery"
 @input.debounce.300ms="performSearch()"
 placeholder="{{ __('أنا أبحث عن...') }}"
 class="w-full ps-3.5 pe-16 py-2 text-xs rounded-full bg-white text-[#18181B] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#18181B] border-0 transition-all shadow-inner">

 <button type="submit" class="absolute end-1 inset-y-1 px-3.5 rounded-full bg-[#18181B] hover:bg-black text-white font-bold text-xs transition-colors flex items-center justify-center cursor-pointer">
 <span>{{ __('بحث') }}</span>
 </button>
 </form>

 {{-- Live Search Dropdown for Mobile --}}
 <div x-show="searchResults.length > 0"
 x-transition
 class="absolute start-3 end-3 mt-2 bg-white text-[#18181B] rounded-2xl shadow-2xl border border-gray-100 p-2 z-50 max-h-72 overflow-y-auto"
 style="display: none;">
 <div class="px-3 py-1.5 text-[11px] font-bold text-gray-400 border-b border-gray-100">{{ __('نتائج البحث المباشر') }}</div>
 <template x-for="item in searchResults" :key="item.id">
 <a :href="item.url" class="flex items-center gap-3 p-2 hover:bg-[#F8F9FA] rounded-xl transition-colors">
 <img :src="item.image_url" class="w-10 h-10 object-contain rounded-lg bg-gray-50 border p-1 shrink-0">
 <div class="flex-1 min-w-0 text-start">
 <h4 class="text-xs font-semibold text-[#18181B] truncate" x-text="item.name"></h4>
 <div class="flex items-center gap-2 mt-0.5">
 <span class="text-xs font-bold text-[#C5A059]" x-text="item.formatted_price_usd"></span>
 </div>
 </div>
 </a>
 </template>
 </div>
 </div>

 {{-- Layer C: Navigation Strip with Gold Styling --}}
 <div class="bg-[#B38E44] border-t border-black/10 py-2 sm:py-2.5 px-3 sm:px-8 relative w-full">
 <div class="w-full flex items-center justify-between text-xs font-bold">

 <div class="flex items-center gap-3 sm:gap-6 w-full">

 {{-- Categories Hover & Click Menu in Satin Gold --}}
 <div class="relative group/menu py-0.5 shrink-0"
 @mouseenter="categoriesMenuOpen = true"
 @mouseleave="categoriesMenuOpen = false">
 <button type="button"
 @click="categoriesMenuOpen = !categoriesMenuOpen"
 class="flex items-center gap-1.5 sm:gap-2 text-[#18181B] hover:text-white transition-colors cursor-pointer select-none text-xs sm:text-xs">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
 </svg>
 <span class="font-black tracking-wide whitespace-nowrap">{{ __('جميع الأقسام') }}</span>
 <svg class="w-3.5 h-3.5 text-[#18181B] transition-transform duration-200 group-hover/menu:rotate-180" :class="categoriesMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
 </svg>
 </button>

 {{-- Flyout Dropdown with solid hover bridge --}}
 <div x-show="categoriesMenuOpen"
 x-transition:enter="transition ease-out duration-150"
 x-transition:enter-start="opacity-0 translate-y-1"
 x-transition:enter-end="opacity-100 translate-y-0"
 x-transition:leave="transition ease-in duration-100"
 x-transition:leave-start="opacity-100 translate-y-0"
 x-transition:leave-end="opacity-0 translate-y-1"
 @click.outside="categoriesMenuOpen = false"
 class="absolute start-0 top-full pt-1.5 w-64 z-[9999]"
 style="display: none;">
 <div class="bg-white text-[#18181B] rounded-2xl shadow-2xl border border-[#EADBCC] py-2 overflow-hidden">
 @if(isset($siteNavCategories) && $siteNavCategories->count() > 0)
 @foreach($siteNavCategories as $navCat)
 <a href="{{ route('category.product', $navCat->id) }}"
 class="flex items-center justify-between px-4 py-2.5 hover:bg-[#FAF8F5] hover:text-[#C5A059] transition-colors text-xs font-semibold border-b border-gray-50 last:border-0">
 <div class="flex items-center gap-2.5">
 @if($navCat->image_url)
 <img src="{{ $navCat->image_url }}" alt="{{ $navCat->name }}" class="w-6 h-6 rounded-full object-cover">
 @else
 <span class="text-sm"></span>
 @endif
 <span>{{ $navCat->name }}</span>
 </div>
 <span class="text-[#C5A059] text-[10px] font-bold">&larr;</span>
 </a>
 @endforeach
 @else
 <a href="{{ route('product.shop') }}" class="block px-4 py-2.5 text-xs text-gray-500 hover:bg-gray-50">
 {{ __('تصفح كل الأقسام') }} &larr;
 </a>
 @endif
 </div>
 </div>
 </div>

 <span class="text-black/20 shrink-0">|</span>

 {{-- Horizontal Scrollable Links for smooth navigation on all screen sizes --}}
 <div class="flex items-center gap-3 sm:gap-6 overflow-x-auto no-scrollbar py-0.5 whitespace-nowrap scroll-smooth flex-1 text-[11px] sm:text-xs">
 <a href="{{ route('product.shop') }}" class="text-[#18181B] hover:text-white transition-colors shrink-0 font-extrabold">
 {{ __('كافة المنتجات') }}
 </a>

 <a href="{{ route('product.shop', ['bundles_only' => 1]) }}" class="flex items-center gap-1 text-[#18181B] hover:text-white transition-colors shrink-0 font-extrabold">
 <span>{{ __('باقات موفّرة') }}</span>
 </a>

 <a href="{{ route('product.shop', ['discount_only' => 1]) }}" class="flex items-center gap-1.5 text-[#18181B] hover:text-white transition-colors shrink-0 font-extrabold">
 <span class="px-1.5 py-0.2 rounded-full bg-rose-600 text-white text-[8px] sm:text-[9px] font-extrabold shadow-xs">{{ __('تخفيضات') }}</span>
 <span>{{ __('عروض مميزة') }}</span>
 </a>

 <a href="{{ route('product.shop', ['featured' => 1]) }}" class="flex items-center gap-1.5 text-[#18181B] hover:text-white transition-colors shrink-0 font-extrabold">
 <span class="px-1.5 py-0.2 rounded-full bg-[#18181B] text-[#C5A059] text-[8px] sm:text-[9px] font-extrabold shadow-xs">⭐</span>
 <span>{{ __('مختارات مميزة') }}</span>
 </a>

 <a href="{{ route('contact') }}" class="text-[#18181B] hover:text-white transition-colors shrink-0 font-extrabold">
 {{ __('تواصل معنا') }}
 </a>
 </div>
 </div>

 
 </div>
 </div>

 {{-- Mobile Menu Drawer (Side Overlay) --}}
 <div x-show="mobileOpen"
 class="fixed inset-0 z-50 overflow-hidden lg:hidden"
 style="display: none;">

 <div x-show="mobileOpen"
 x-transition:enter="ease-out duration-300"
 x-transition:enter-start="opacity-0"
 x-transition:enter-end="opacity-100"
 x-transition:leave="ease-in duration-200"
 x-transition:leave-start="opacity-100"
 x-transition:leave-end="opacity-0"
 @click="mobileOpen = false"
 class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

 <div class="fixed inset-y-0 start-0 max-w-full flex pr-10">
 <div x-show="mobileOpen"
 x-transition:enter="transform transition ease-in-out duration-300"
 x-transition:enter-start="-translate-x-full rtl:translate-x-full"
 x-transition:enter-end="translate-x-0"
 x-transition:leave="transform transition ease-in-out duration-200"
 x-transition:leave-start="translate-x-0"
 x-transition:leave-end="-translate-x-full rtl:translate-x-full"
 class="w-screen max-w-xs bg-[#18181B] text-white shadow-2xl flex flex-col border-e border-white/10">

 <div class="p-4 border-b border-white/10 flex items-center justify-between bg-black/40">
 <a href="{{ route('site.home') }}" class="flex items-center gap-2">
 <span class="text-xl"></span>
 <span class="font-royal text-base font-extrabold text-white">{{ settings('site_name', 'REGALEST') }}</span>
 </a>
 <button @click="mobileOpen = false" class="p-1 rounded-full text-gray-400 hover:text-white transition-colors">
 
 </button>
 </div>

 <div class="flex-1 overflow-y-auto p-4 space-y-4 text-xs font-semibold">
 <div class="space-y-1">
 <span class="text-[10px] text-gray-400 uppercase tracking-widest block px-2 mb-1">{{ __('التنقل الرئيسي') }}</span>
 <a href="{{ route('site.home') }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-white">
 <span> {{ __('الرئيسية') }}</span>
 </a>
 <a href="{{ route('product.shop') }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-white">
 <span> {{ __('كافة المنتجات') }}</span>
 </a>
 <a href="{{ route('product.shop', ['bundles_only' => 1]) }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-[#C5A059]">
 <span> {{ __('باقات موفّرة') }}</span>
 <span class="px-1.5 py-0.5 rounded-full bg-[#C5A059] text-white text-[9px] font-bold">{{ __('توفير') }}</span>
 </a>
 <a href="{{ route('product.shop', ['discount_only' => 1]) }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-rose-400">
 <span> {{ __('عروض وتخفيضات') }}</span>
 <span class="px-1.5 py-0.5 rounded-full bg-rose-600 text-white text-[9px] font-bold">{{ __('خصومات') }}</span>
 </a>
 <a href="{{ route('product.shop', ['featured' => 1]) }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-amber-300">
 <span>⭐ {{ __('مختارات مميزة') }}</span>
 </a>
 <a href="{{ route('wishlist.index') }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-white">
 <span> {{ __('قائمة المفضلة') }}</span>
 </a>
 <a href="{{ route('contact') }}" @click="mobileOpen = false" class="flex items-center justify-between p-2.5 rounded-xl hover:bg-white/10 text-white">
 <span> {{ __('خدمة العملاء والتواصل') }}</span>
 </a>
 </div>

 @if(isset($siteNavCategories) && $siteNavCategories->count() > 0)
 <div class="pt-2 border-t border-white/10 space-y-1">
 <span class="text-[10px] text-gray-400 uppercase tracking-widest block px-2 mb-1">{{ __('تصفح حسب الأقسام') }}</span>
 @foreach($siteNavCategories as $mCat)
 <a href="{{ route('category.product', $mCat->id) }}" @click="mobileOpen = false" class="flex items-center justify-between p-2 rounded-lg hover:bg-white/10 text-gray-300 hover:text-[#C5A059] text-xs">
 <div class="flex items-center gap-2">
 @if($mCat->image_url)
 <img src="{{ $mCat->image_url }}" alt="{{ $mCat->name }}" class="w-5 h-5 rounded-full object-cover">
 @else
 <span></span>
 @endif
 <span>{{ $mCat->name }}</span>
 </div>
 <span class="text-[#C5A059] text-[10px]">&larr;</span>
 </a>
 @endforeach
 </div>
 @endif

 <div class="pt-2 border-t border-white/10 space-y-2">
 <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" class="w-full py-2 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-center block text-xs font-bold text-white">
 {{ auth()->check() ? auth()->user()->name : __('تسجيل الدخول / الحساب') }}
 </a>
 </div>
 </div>
 </div>
 </div>
 </div>

 {{-- Slide-Over Mini-Cart Drawer --}}
 <div x-show="cartDrawerOpen"
 class="fixed inset-0 z-50 overflow-hidden"
 style="display: none;">

 <div x-show="cartDrawerOpen"
 x-transition:enter="ease-out duration-300"
 x-transition:enter-start="opacity-0"
 x-transition:enter-end="opacity-100"
 x-transition:leave="ease-in duration-200"
 x-transition:leave-start="opacity-100"
 x-transition:leave-end="opacity-0"
 @click="cartDrawerOpen = false"
 class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

 <div class="fixed inset-y-0 end-0 max-w-full flex pl-10">
 <div x-show="cartDrawerOpen"
 x-transition:enter="transform transition ease-in-out duration-300"
 x-transition:enter-start="translate-x-full rtl:-translate-x-full"
 x-transition:enter-end="translate-x-0"
 x-transition:leave="transform transition ease-in-out duration-200"
 x-transition:leave-start="translate-x-0"
 x-transition:leave-end="translate-x-full rtl:-translate-x-full"
 class="w-screen max-w-md bg-white text-[#18181B] shadow-2xl flex flex-col">

 <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-[#F8F9FA]">
 <div class="flex items-center gap-2">
 <span class="font-royal font-bold text-base text-[#18181B]">{{ __('سلة المقتنيات') }}</span>
 <span class="text-xs px-2 py-0.5 rounded-full bg-[#C5A059] text-white font-bold" x-text="cart.count"></span>
 </div>
 <button @click="cartDrawerOpen = false" class="p-1 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-200 transition-colors">
 
 </button>
 </div>

 <div class="flex-1 overflow-y-auto p-4 space-y-3">
 <template x-if="!cart.items || cart.items.length === 0">
 <div class="py-12 text-center text-gray-400 space-y-3">
 <span class="text-4xl block"></span>
 <p class="text-sm font-medium">{{ __('السلة فارغة حالياً') }}</p>
 <a href="{{ route('product.shop') }}" @click="cartDrawerOpen = false" class="inline-block mt-2 px-5 py-2 rounded-full bg-[#18181B] text-white text-xs font-bold">
 {{ __('استكشف المتجر') }}
 </a>
 </div>
 </template>

 <template x-for="item in cart.items" :key="item.key">
 <div class="flex items-center gap-3 p-2.5 rounded-xl border border-gray-100 bg-[#F8F9FA]/50">
 <img :src="item.image" class="w-14 h-14 object-contain rounded-lg bg-white border p-1 shrink-0">
 <div class="flex-1 min-w-0 text-start">
 <h4 class="text-xs font-semibold text-[#18181B] truncate" x-text="item.name"></h4>
 <div class="text-[11px] text-gray-500 mt-0.5">
 <span class="font-bold text-[#18181B]" x-text="item.formatted_price_usd"></span>
 <span class="text-xs font-semibold mx-1">× <span x-text="item.quantity"></span></span>
 </div>
 </div>
 </div>
 </template>
 </div>

 <template x-if="cart.items && cart.items.length > 0">
 <div class="p-4 border-t border-gray-100 bg-[#F8F9FA] space-y-3">
 <div class="space-y-1 text-xs">
 <div class="flex items-center justify-between text-[#18181B] font-bold text-sm">
 <span>{{ __('المجموع الفرعي:') }}</span>
 <span x-text="cart.formatted_subtotal_usd" class="tabular-nums"></span>
 </div>
 </div>

 <a href="{{ route('checkout.index') }}" class="w-full py-3 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white font-bold text-xs flex items-center justify-center gap-2 shadow-sm transition-all">
 <span> {{ __('متابعة إتمام الطلب') }} &larr;</span>
 </a>

 <a href="{{ route('cart') }}" class="w-full py-2.5 rounded-full bg-white border border-[#EADBCC] hover:bg-[#FAF8F5] text-[#18181B] font-semibold text-xs flex items-center justify-center gap-1 transition-all">
 <span>{{ __('عرض وتعديل محتويات السلة') }}</span>
 </a>
 </div>
 </template>
 </div>
 </div>
 </div>
</div>