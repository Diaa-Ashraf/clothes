{{-- resources/views/components/storefront/layout/header.blade.php --}}
@php
    $cartCount = 0;
    $cartTotal = 0;
    try {
        $cartService = app(\App\Services\CartService::class);
        $cartCount = $cartService->getCount();
        $cartTotal = $cartService->getSubtotal();
    } catch (\Throwable $e) {
        $cart = session('tawhid_cart', []);
        $cartCount = 0;
        $cartTotal = 0.0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['quantity'] ?? 0);
            $cartTotal += (float)($item['total'] ?? 0);
        }
    }

    $navCategories = \App\Models\Category::query()
        ->select(['id', 'name', 'slug', 'parent_id', 'sort_order', 'is_active', 'image'])
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->with(['children' => fn($q) => $q->select(['id', 'name', 'slug', 'parent_id', 'sort_order', 'is_active'])->where('is_active', true)->orderBy('sort_order')->orderBy('name')])
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get();

    $menCategory = $navCategories->first(fn($c) => str_contains($c->slug, 'men') || str_contains($c->name, 'رجال'));
    $womenCategory = $navCategories->first(fn($c) => str_contains($c->slug, 'women') || str_contains($c->name, 'نسا'));
    $kidsCategory = $navCategories->first(fn($c) => str_contains($c->slug, 'kid') || str_contains($c->name, 'أطفال') || str_contains($c->name, 'اطفال'));

    $featuredCategoryIds = collect([$menCategory?->id, $womenCategory?->id, $kidsCategory?->id])->filter()->toArray();
    $otherCategories = $navCategories->whereNotIn('id', $featuredCategoryIds);
@endphp

<header class="sticky top-0 z-40 bg-white shadow-sm select-none border-b border-[#BDAB75]/30 transition-all duration-300" 
        x-data="{ 
            activeMenu: null,
            cartBump: false,
            searchFocused: false,
            cartCount: {{ (int)$cartCount }},
            cartTotal: '{{ format_currency($cartTotal) }}'
        }"
        @cart-updated.window="
            if ($event.detail) {
                cartCount = ($event.detail.count ?? $event.detail.cart_count ?? ($event.detail.totals ? $event.detail.totals.count : cartCount));
                if ($event.detail.totals && $event.detail.totals.formatted_subtotal) {
                    cartTotal = $event.detail.totals.formatted_subtotal;
                }
            }
            cartBump = true;
            setTimeout(() => cartBump = false, 700);
        "
        dir="rtl">

    <!-- 1. Top Utility Strip (Warm Egyptian Bronze & Delivery Assurance - Dynamic from Settings) -->
    @if(settings('topbar_enabled', '1') == '1')
    <div class="bg-[#2E2416] text-white py-1.5 px-3 sm:px-6 lg:px-8 text-xs border-b border-[#AC9653]/30">
        <div class="max-w-7xl 2xl:max-w-[1440px] mx-auto">
            
            <!-- Desktop / Large Viewport (>= lg) -->
            <div class="hidden lg:flex items-center justify-between gap-4">
                <!-- Right: Egyptian Phone & Service -->
                <div class="flex items-center gap-2.5 text-xs shrink-0">
                    @php
                        $topPhone = settings('topbar_phone') ?: settings('store_phone', '01000000000');
                    @endphp
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $topPhone) }}" class="flex items-center gap-1.5 text-white/95 hover:text-[#CBA367] transition-colors group">
                        <span class="w-2 h-2 rounded-full bg-[#CBA367] animate-ping"></span>
                        <svg class="w-3.5 h-3.5 text-[#CBA367] group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/>
                        </svg>
                        <span>خدمة العملاء: <strong class="text-white font-bold tracking-wider" dir="ltr">{{ $topPhone }}</strong></span>
                    </a>
                    <span class="text-white/25">|</span>
                    <span class="text-white/80">{{ settings('topbar_payment_note', 'دفع عند الاستلام • فودافون كاش • إنستاباي') }}</span>
                </div>

                <!-- Center: Trust & Inspection Assurance -->
                <div class="text-xs font-medium text-white/95 text-center flex items-center gap-2">
                    <span class="text-[#CBA367] animate-pulse">✦</span>
                    @if(settings('announcement_bar_link'))
                        <a href="{{ settings('announcement_bar_link') }}" class="hover:text-[#CBA367] transition-colors">
                            {{ settings('topbar_announcement', 'شحن سريع لكافة محافظات مصر • معاينة وقياس قبل الدفع • استبدال مجاني 14 يوماً') }}
                        </a>
                    @else
                        <span>{{ settings('topbar_announcement', 'شحن سريع لكافة محافظات مصر • معاينة وقياس قبل الدفع • استبدال مجاني 14 يوماً') }}</span>
                    @endif
                </div>

                <!-- Left: Quick Links & EGP Currency -->
                <div class="flex items-center gap-3 text-[11px] shrink-0">
                    <a href="{{ route('orders.track') }}" class="text-white/80 hover:text-[#CBA367] transition-colors">تتبع طلبك</a>
                    <span class="text-white/25">|</span>
                    <a href="{{ route('branches.index') }}" class="text-white/80 hover:text-[#CBA367] transition-colors">فروعنا</a>
                    <span class="text-white/25">|</span>
                    <span class="inline-flex items-center gap-1.5 bg-[#AC9653]/35 px-2.5 py-0.5 rounded-full text-white font-bold text-[10px] border border-[#BDAB75]/30">
                        <span>{{ settings('default_currency', 'EGP') }}</span>
                        <span>{{ settings('currency_symbol', 'ج.م') }}</span>
                    </span>
                </div>
            </div>

            <!-- Mobile & Tablet Viewport (< lg) -->
            <div class="lg:hidden flex items-center justify-between text-[10px] sm:text-[11px] gap-2 overflow-hidden w-full">
                @php
                    $mobilePhone = settings('topbar_phone') ?: settings('store_phone', '01000000000');
                @endphp
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $mobilePhone) }}" class="flex items-center gap-1 text-white/95 hover:text-[#CBA367] transition-colors shrink-0">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#CBA367] animate-ping"></span>
                    <svg class="w-3 h-3 text-[#CBA367]" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/>
                    </svg>
                    <span class="font-bold tracking-wider" dir="ltr">{{ $mobilePhone }}</span>
                </a>

                <div class="flex-1 min-w-0 text-center text-white/90 truncate font-medium text-[9.5px] sm:text-[11px]">
                    <span class="text-[#CBA367]">✦</span>
                    <span class="truncate">{{ settings('topbar_announcement_mobile', settings('topbar_announcement', 'معاينة وقياس قبل الدفع • شحن لكافة المحافظات')) }}</span>
                </div>

                <span class="hidden xs:inline-block shrink-0 text-[9.5px] bg-[#AC9653]/40 px-2 py-0.5 rounded-full text-white font-bold border border-[#BDAB75]/30">
                    {{ settings('currency_symbol', 'ج.م') }}
                </span>
            </div>

        </div>
    </div>
    @endif

    <!-- 2. Main Middle Bar: Brand Logo, Animated Search, Quick Actions -->
    <div class="max-w-7xl 2xl:max-w-[1440px] mx-auto px-2.5 sm:px-6 lg:px-8 xl:px-10 py-2 sm:py-3 w-full overflow-hidden">
        <div class="flex items-center justify-between gap-1.5 sm:gap-4 lg:gap-8 w-full">
            
            <!-- Mobile Toggle + Store Brand Logo -->
            <div class="flex items-center gap-1.5 sm:gap-3 min-w-0 flex-1">
                <button type="button" 
                        @click="$dispatch('open-mobile-menu')"
                        class="lg:hidden p-1.5 sm:p-2 text-[#2E2416] hover:bg-gray-100 rounded-lg sm:rounded-xl transition-colors cursor-pointer active:scale-95 shrink-0"
                        aria-label="القائمة">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('site.home') }}" class="flex items-center gap-1.5 sm:gap-3 group min-w-0">
                    @if(get_site_logo())
                        <div class="h-8 sm:h-12 max-w-[100px] xs:max-w-[130px] sm:max-w-[160px] flex items-center justify-center shrink-0">
                            <img src="{{ get_site_logo() }}" alt="{{ settings('site_name', 'أزياء لافيستا') }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300">
                        </div>
                    @else
                        <div class="w-7 h-7 xs:w-8 xs:h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-gradient-to-tr from-[#AC9653] to-[#CBA367] flex items-center justify-center text-white font-black text-xs xs:text-sm sm:text-xl shadow-xs group-hover:scale-105 transition-all duration-300 shrink-0">
                            {{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
                        </div>
                    @endif
                    <div class="flex flex-col text-right min-w-0">
                        <span class="text-xs xs:text-sm sm:text-xl lg:text-2xl font-black text-[#2E2416] tracking-tight group-hover:text-[#AC9653] transition-colors leading-tight truncate">
                            {{ settings('site_name', 'أزياء لافيستا') }}
                        </span>
                        <span class="hidden xs:block text-[7px] sm:text-[9.5px] font-bold text-[#AC9653] tracking-widest uppercase -mt-0.5 group-hover:text-[#CBA367] transition-colors truncate">
                            {{ settings('site_tagline', 'LAVISTA FASHION') }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- Central Search Bar with Category Selector (Desktop >= lg) -->
            <div class="hidden lg:flex flex-1 max-w-xl xl:max-w-2xl 2xl:max-w-3xl mx-4 xl:mx-8">
                <form action="{{ route('shop') }}" method="GET" 
                      class="w-full flex items-center rounded-xl bg-white border border-[#BDAB75]/50 shadow-2xs hover:border-[#AC9653] focus-within:border-[#AC9653] focus-within:ring-2 focus-within:ring-[#CBA367]/30 transition-all duration-300 overflow-hidden">
                    
                    <!-- Department Selector Dropdown -->
                    <div class="relative bg-gray-50/80 border-l border-gray-200">
                        <select name="category_id" 
                                class="bg-transparent text-gray-700 text-xs font-bold pl-7 pr-3 py-2.5 outline-none cursor-pointer max-w-[145px] truncate appearance-none">
                            <option value="">كافة الأقسام</option>
                            @foreach($navCategories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute left-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Search Text Input -->
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="ابحث عن قميص، فستان، بنطلون، جاكيت، ترنج أطفال، حذاء..."
                           class="flex-1 px-4 py-2.5 text-xs text-gray-800 placeholder-gray-400 outline-none bg-transparent">

                    <!-- Search Button with Micro-Animation -->
                    <button type="submit" 
                            class="bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 text-white px-5 py-2.5 font-bold text-xs flex items-center gap-1.5 transition-all cursor-pointer shrink-0 shadow-xs">
                        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>بحث</span>
                    </button>
                </form>
            </div>

            <!-- User Quick Actions (Account, Wishlist, Animated Cart) -->
            <div class="flex items-center gap-1 sm:gap-2.5 shrink-0">
                
                <!-- Account / Login Button (Hidden on mobile < sm, accessible in bottom nav & slide-over menu) -->
                @auth
                    <a href="{{ route('account.profile') }}" 
                       class="hidden sm:flex h-9 sm:h-10 px-2 sm:px-3 rounded-xl border border-gray-200/80 hover:border-[#AC9653] hover:bg-[#AC9653]/10 text-gray-700 transition-all items-center justify-center sm:justify-start gap-2 group shrink-0"
                       title="حسابي">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-[#AC9653] text-white flex items-center justify-center font-bold text-[10px] sm:text-xs">
                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="hidden xl:flex flex-col text-right">
                            <span class="text-[9px] text-gray-400">مرحباً بك</span>
                            <span class="text-xs font-bold text-gray-800 group-hover:text-[#AC9653] transition-colors leading-none">{{ Str::limit(auth()->user()->name, 10) }}</span>
                        </div>
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="hidden sm:flex h-9 sm:h-10 px-2 sm:px-3 rounded-xl border border-gray-200/80 hover:border-[#AC9653] hover:bg-gray-50 text-gray-700 transition-all items-center justify-center sm:justify-start gap-1.5 group shrink-0"
                       title="تسجيل الدخول">
                        <svg class="w-4 h-4 text-gray-600 group-hover:text-[#AC9653] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-xs font-bold text-gray-700 group-hover:text-[#AC9653] transition-colors hidden sm:inline">دخول</span>
                    </a>
                @endauth

                <!-- Wishlist Button with Heart Animation -->
                <a href="{{ route('wishlist.index') }}" 
                   class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl border border-gray-200/80 hover:border-red-300 text-gray-600 hover:text-red-500 hover:bg-red-50/60 transition-all flex items-center justify-center group active:scale-95 shrink-0"
                   title="قائمة الرغبات">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </a>

                <!-- Cart Button with Animated Bounce Badge -->
                <button type="button" 
                        @click.prevent="$dispatch('open-cart-drawer')"
                        class="h-8 sm:h-10 px-2 sm:px-3.5 bg-gradient-to-r from-[#AC9653] to-[#CBA367] hover:brightness-105 active:scale-95 text-white rounded-lg sm:rounded-xl shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-1.5 sm:gap-2 cursor-pointer group shrink-0"
                        title="سلة التسوق">
                    <div class="relative flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute -top-1.5 -right-1.5 bg-[#2E2416] text-[#F3EFE6] text-[9px] sm:text-[10px] font-black min-w-3.5 sm:min-w-4 h-3.5 sm:h-4 px-1 rounded-full flex items-center justify-center shadow-xs transition-transform duration-300" 
                              :class="cartBump ? 'scale-125 bg-red-600' : ''"
                              x-text="cartCount">
                            {{ $cartCount }}
                        </span>
                    </div>
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-[9px] text-white/80 leading-none">السلة</span>
                        <span class="text-xs font-black text-white leading-tight" x-text="cartTotal">
                            {{ format_currency($cartTotal) }}
                        </span>
                    </div>
                </button>

            </div>

        </div>

        <!-- Mobile & Tablet Search Bar + Horizontal Category Pills (< lg) -->
        <div class="mt-2 lg:hidden space-y-2 w-full overflow-hidden">
            <!-- Search Input -->
            <form action="{{ route('shop') }}" method="GET" 
                  class="w-full flex items-center rounded-xl bg-gray-50/90 hover:bg-white border border-[#BDAB75]/50 focus-within:border-[#AC9653] focus-within:bg-white focus-within:ring-2 focus-within:ring-[#CBA367]/25 transition-all duration-200 shadow-2xs overflow-hidden">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="ابحث عن قميص، فستان، بنطلون، جاكيت..."
                       class="flex-1 py-1.5 sm:py-2.5 px-3 text-xs text-gray-800 placeholder-gray-400 outline-none bg-transparent min-w-0">
                @if(request('search'))
                    <a href="{{ route('shop') }}" class="p-1.5 text-gray-400 hover:text-gray-600 text-xs">✕</a>
                @endif
                <button type="submit" 
                        class="h-7 sm:h-9 px-3 sm:px-4 bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 text-white text-xs font-bold rounded-lg m-1 flex items-center gap-1 justify-center transition-all shrink-0 cursor-pointer shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>بحث</span>
                </button>
            </form>

            <!-- Horizontal Scrollable Category Pills -->
            <div class="relative w-full overflow-hidden">
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-0.5 whitespace-nowrap text-xs scroll-smooth px-0.5">
                    <a href="{{ route('shop', ['deals_only' => 1]) }}" 
                       class="px-2.5 sm:px-3 py-1 sm:py-1.5 bg-gradient-to-r from-[#CBA367] to-[#AC9653] text-white font-bold rounded-full flex items-center gap-1 shadow-2xs shrink-0 text-[10px] sm:text-[11px] active:scale-95 transition-transform">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>عروض اليوم</span>
                    </a>
                    <a href="{{ route('shop') }}" 
                       class="px-2.5 sm:px-3 py-1 sm:py-1.5 {{ !request('category_id') && !request('deals_only') ? 'bg-[#2E2416] text-white' : 'bg-gray-100 text-gray-800 hover:bg-[#AC9653]/15' }} font-bold rounded-full shrink-0 transition-colors text-[10px] sm:text-[11px]">
                        كافة الملابس
                    </a>
                    @foreach($navCategories as $cat)
                        <a href="{{ route('shop', ['category_id' => $cat->id]) }}" 
                           class="px-2.5 sm:px-3 py-1 sm:py-1.5 {{ request('category_id') == $cat->id ? 'bg-[#AC9653] text-white' : 'bg-gray-100 text-gray-800 hover:bg-[#AC9653]/15' }} font-bold rounded-full shrink-0 transition-colors text-[10px] sm:text-[11px]">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Desktop Category Navigation Bar (Rich Golden #AC9653 with Interactive Dropdowns - Hidden on Mobile) -->
    <nav class="hidden lg:block bg-[#AC9653] text-white border-t border-[#BDAB75]/40 shadow-xs relative z-30" 
         @mouseleave="activeMenu = null">
        <div class="max-w-7xl 2xl:max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-10">
            <div class="flex items-center justify-between h-12">
                
                <!-- Right Side: Departments & Main Clothing Links -->
                <div class="flex items-center h-full gap-1.5 xl:gap-2.5">
                    
                    <!-- All Categories Mega Trigger -->
                    <div class="relative h-full flex items-center"
                         @mouseenter="activeMenu = 'all'"
                         @mouseleave="activeMenu = null">
                        <button type="button" 
                                @click="activeMenu = (activeMenu === 'all' ? null : 'all')"
                                class="flex items-center gap-2 bg-[#8C7838] hover:bg-[#7D6A31] active:scale-95 text-white px-3.5 py-2 rounded-lg font-bold text-xs transition-all shrink-0 shadow-2xs cursor-pointer">
                            <svg class="w-4 h-4 text-[#F3EFE6]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                            </svg>
                            <span>جميع الأقسام</span>
                            <svg class="w-3 h-3 text-white/80 transition-transform duration-200" :class="activeMenu === 'all' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- All Categories Mega Dropdown Panel with Hover Bridge -->
                        <div x-show="activeMenu === 'all'" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200 transform"
                             x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150 transform"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                             class="absolute right-0 top-full pt-1.5 z-50 w-[480px] lg:w-[560px] xl:w-[620px]">
                            
                            <div class="dropdown-glass relative rounded-2xl p-5 text-right shadow-2xl border border-[#BDAB75]/40">
                                <!-- Top Caret Indicator -->
                                <div class="absolute -top-1.5 right-8 w-3 h-3 bg-white rotate-45 border-t border-r border-[#BDAB75]/40"></div>
                                
                                <div class="grid grid-cols-2 gap-3.5 max-h-[72vh] overflow-y-auto no-scrollbar relative z-10 pr-1">
                                    @foreach($navCategories as $cat)
                                        <div class="p-2.5 rounded-xl hover:bg-gray-50/90 transition-colors border border-transparent hover:border-[#BDAB75]/30">
                                            <a href="{{ route('shop', ['category_id' => $cat->id]) }}" class="flex items-center justify-between text-xs font-bold text-gray-900 hover:text-[#AC9653] group">
                                                <span class="group-hover:translate-x-0.5 transition-transform">{{ $cat->name }}</span>
                                                <span class="text-xs text-[#AC9653]">‹</span>
                                            </a>
                                            @if($cat->children->isNotEmpty())
                                                <div class="flex flex-wrap gap-1 mt-2">
                                                    @foreach($cat->children->take(4) as $sub)
                                                        <a href="{{ route('shop', ['category_id' => $sub->id]) }}" class="text-[10px] text-gray-600 hover:text-[#CBA367] bg-gray-100 hover:bg-[#CBA367]/15 px-2 py-0.5 rounded-md transition-colors font-medium">
                                                            {{ $sub->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                                    <a href="{{ route('shop') }}" class="font-bold text-[#AC9653] hover:underline flex items-center gap-1">
                                        <span>تصفح كافة معروضات المتجر</span>
                                        <span>‹</span>
                                    </a>
                                    <span class="text-[10px] text-gray-400 font-medium">خامات قطنية مصرية 100%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Direct Nav Link: الرئيسية -->
                    <a href="{{ route('site.home') }}" class="nav-link-indicator h-full flex items-center px-2.5 xl:px-3 text-xs font-bold text-white hover:text-[#F3EFE6] transition-colors shrink-0">
                        الرئيسية
                    </a>

                    <!-- Direct Nav Link: كافة الملابس -->
                    <a href="{{ route('shop') }}" class="nav-link-indicator h-full flex items-center px-2.5 xl:px-3 text-xs font-bold text-white hover:text-[#F3EFE6] transition-colors shrink-0">
                        كافة الملابس
                    </a>

                    <!-- Dropdown: ملابس رجالية (ديناميكي من الداتا بيز) -->
                    @if($menCategory)
                    <div class="relative h-full flex items-center" 
                         @mouseenter="activeMenu = 'men'"
                         @mouseleave="activeMenu = null">
                        <a href="{{ route('shop', ['category_id' => $menCategory->id]) }}" 
                           class="nav-link-indicator h-full flex items-center gap-1 px-2.5 xl:px-3 text-xs font-bold text-white hover:text-[#F3EFE6] transition-colors shrink-0">
                            <span>{{ $menCategory->name }}</span>
                            @if($menCategory->children->isNotEmpty())
                                <svg class="w-3 h-3 text-white/80 transition-transform duration-200" :class="activeMenu === 'men' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        </a>

                        @if($menCategory->children->isNotEmpty())
                        <!-- Men Dropdown Panel with Hover Bridge -->
                        <div x-show="activeMenu === 'men'" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200 transform"
                             x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150 transform"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                             class="absolute right-0 top-full pt-1.5 z-50 w-96">
                            
                            <div class="dropdown-glass relative rounded-2xl p-4 text-right grid grid-cols-2 gap-4 shadow-2xl border border-[#BDAB75]/40">
                                <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white rotate-45 border-t border-r border-[#BDAB75]/40"></div>
                                
                                <div class="space-y-1.5 relative z-10">
                                    <span class="block text-[11px] font-black text-[#AC9653] border-b border-gray-100 pb-1">أقسام {{ $menCategory->name }}</span>
                                    @foreach($menCategory->children as $sub)
                                        <a href="{{ route('shop', ['category_id' => $sub->id]) }}" class="block text-xs font-semibold text-gray-700 hover:text-[#AC9653] hover:translate-x-1 py-1 transition-all">
                                            {{ $sub->name }}
                                        </a>
                                    @endforeach
                                </div>
                                
                                <div class="bg-gradient-to-br from-[#AC9653]/15 to-[#CBA367]/25 p-3.5 rounded-xl flex flex-col justify-between text-center border border-[#AC9653]/20 relative z-10">
                                    <div>
                                        <span class="text-[10px] font-black text-[#AC9653] uppercase tracking-wider">تشكيلة الموسم الجديد</span>
                                        <h5 class="text-xs font-black text-gray-900 mt-1">كولكشن {{ $menCategory->name }}</h5>
                                        <p class="text-[10px] text-gray-500 mt-1">خامات قطنية وصوف تركي فاخر</p>
                                    </div>
                                    <a href="{{ route('shop', ['category_id' => $menCategory->id]) }}" class="mt-3 py-1.5 px-3 bg-[#AC9653] hover:bg-[#8D793C] active:scale-95 text-white text-[11px] font-bold rounded-lg shadow-xs transition-all">
                                        تصفح الكل
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Dropdown: أزياء نسائية (ديناميكي من الداتا بيز) -->
                    @if($womenCategory)
                    <div class="relative h-full flex items-center" 
                         @mouseenter="activeMenu = 'women'"
                         @mouseleave="activeMenu = null">
                        <a href="{{ route('shop', ['category_id' => $womenCategory->id]) }}" 
                           class="nav-link-indicator h-full flex items-center gap-1 px-2.5 xl:px-3 text-xs font-bold text-white hover:text-[#F3EFE6] transition-colors shrink-0">
                            <span>{{ $womenCategory->name }}</span>
                            @if($womenCategory->children->isNotEmpty())
                                <svg class="w-3 h-3 text-white/80 transition-transform duration-200" :class="activeMenu === 'women' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        </a>

                        @if($womenCategory->children->isNotEmpty())
                        <!-- Women Dropdown Panel with Hover Bridge -->
                        <div x-show="activeMenu === 'women'" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200 transform"
                             x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150 transform"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                             class="absolute right-0 top-full pt-1.5 z-50 w-96">
                            
                            <div class="dropdown-glass relative rounded-2xl p-4 text-right grid grid-cols-2 gap-4 shadow-2xl border border-[#BDAB75]/40">
                                <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white rotate-45 border-t border-r border-[#BDAB75]/40"></div>
                                
                                <div class="space-y-1.5 relative z-10">
                                    <span class="block text-[11px] font-black text-[#AC9653] border-b border-gray-100 pb-1">أقسام {{ $womenCategory->name }}</span>
                                    @foreach($womenCategory->children as $sub)
                                        <a href="{{ route('shop', ['category_id' => $sub->id]) }}" class="block text-xs font-semibold text-gray-700 hover:text-[#AC9653] hover:translate-x-1 py-1 transition-all">
                                            {{ $sub->name }}
                                        </a>
                                    @endforeach
                                </div>
                                
                                <div class="bg-gradient-to-br from-[#CBA367]/15 to-[#AC9653]/25 p-3.5 rounded-xl flex flex-col justify-between text-center border border-[#CBA367]/25 relative z-10">
                                    <div>
                                        <span class="text-[10px] font-black text-[#CBA367] uppercase tracking-wider">إطلالة متألقة</span>
                                        <h5 class="text-xs font-black text-gray-900 mt-1">كولكشن {{ $womenCategory->name }}</h5>
                                        <p class="text-[10px] text-gray-500 mt-1">أناقة وألوان عصرية وتفاصيل فاخرة</p>
                                    </div>
                                    <a href="{{ route('shop', ['category_id' => $womenCategory->id]) }}" class="mt-3 py-1.5 px-3 bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 text-white text-[11px] font-bold rounded-lg shadow-xs transition-all">
                                        تصفحي الكل
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Dropdown: أطفال ومواليد (ديناميكي من الداتا بيز) -->
                    @if($kidsCategory)
                    <div class="relative h-full flex items-center" 
                         @mouseenter="activeMenu = 'kids'"
                         @mouseleave="activeMenu = null">
                        <a href="{{ route('shop', ['category_id' => $kidsCategory->id]) }}" 
                           class="nav-link-indicator h-full flex items-center gap-1 px-2.5 xl:px-3 text-xs font-bold text-white hover:text-[#F3EFE6] transition-colors shrink-0">
                            <span>{{ $kidsCategory->name }}</span>
                            @if($kidsCategory->children->isNotEmpty())
                                <svg class="w-3 h-3 text-white/80 transition-transform duration-200" :class="activeMenu === 'kids' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            @endif
                        </a>

                        @if($kidsCategory->children->isNotEmpty())
                        <!-- Kids Dropdown Panel with Hover Bridge -->
                        <div x-show="activeMenu === 'kids'" 
                             x-cloak
                             x-transition:enter="transition ease-out duration-200 transform"
                             x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-150 transform"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                             class="absolute right-0 top-full pt-1.5 z-50 w-72">
                            
                            <div class="dropdown-glass relative rounded-2xl p-4 text-right space-y-2.5 shadow-2xl border border-[#BDAB75]/40">
                                <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white rotate-45 border-t border-r border-[#BDAB75]/40"></div>
                                
                                <span class="block text-[11px] font-black text-[#AC9653] border-b border-gray-100 pb-1">أقسام {{ $kidsCategory->name }}</span>
                                @foreach($kidsCategory->children as $sub)
                                    <a href="{{ route('shop', ['category_id' => $sub->id]) }}" class="block text-xs font-semibold text-gray-700 hover:text-[#AC9653] hover:translate-x-1 py-1 transition-all">
                                        {{ $sub->name }}
                                    </a>
                                @endforeach
                                <div class="pt-2 border-t border-gray-100">
                                    <a href="{{ route('shop', ['category_id' => $kidsCategory->id]) }}" class="block text-center py-2 bg-[#AC9653] hover:bg-[#8D793C] active:scale-95 text-white text-[11px] font-bold rounded-lg transition-all shadow-xs">
                                        كافة أزياء الأطفال
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- بقية الأقسام الرئيسية (ديناميكية 100% من قاعدة البيانات) -->
                    @foreach($otherCategories as $otherCat)
                        <div class="relative h-full flex items-center"
                             @mouseenter="activeMenu = 'cat_{{ $otherCat->id }}'"
                             @mouseleave="activeMenu = null">
                            <a href="{{ route('shop', ['category_id' => $otherCat->id]) }}" class="nav-link-indicator h-full flex items-center gap-1 px-2.5 xl:px-3 text-xs font-bold text-white hover:text-[#F3EFE6] transition-colors shrink-0">
                                <span>{{ $otherCat->name }}</span>
                                @if($otherCat->children->isNotEmpty())
                                    <svg class="w-3 h-3 text-white/80 transition-transform duration-200" :class="activeMenu === 'cat_{{ $otherCat->id }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </a>

                            @if($otherCat->children->isNotEmpty())
                            <div x-show="activeMenu === 'cat_{{ $otherCat->id }}'" 
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200 transform"
                                 x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150 transform"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                                 class="absolute right-0 top-full pt-1.5 z-50 w-64">
                                <div class="dropdown-glass relative rounded-2xl p-4 text-right space-y-2 shadow-2xl border border-[#BDAB75]/40">
                                    <div class="absolute -top-1.5 right-6 w-3 h-3 bg-white rotate-45 border-t border-r border-[#BDAB75]/40"></div>
                                    <span class="block text-[11px] font-black text-[#AC9653] border-b border-gray-100 pb-1">أقسام {{ $otherCat->name }}</span>
                                    @foreach($otherCat->children as $sub)
                                        <a href="{{ route('shop', ['category_id' => $sub->id]) }}" class="block text-xs font-semibold text-gray-700 hover:text-[#AC9653] hover:translate-x-1 py-1 transition-all">
                                            {{ $sub->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Flash Sale & Deals Highlight with Animated Pulse Glow & Shimmer (No emoji) -->
                    <div class="h-full flex items-center pr-1 shrink-0">
                        <a href="{{ route('shop', ['deals_only' => 1]) }}" 
                           class="animate-pulse-glow shimmer-badge px-3 py-1.5 text-xs font-black bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 text-white rounded-lg transition-all flex items-center gap-1.5 shrink-0 shadow-xs">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>عروض اليوم</span>
                        </a>
                    </div>

                </div>

                <!-- Left Side: Branches & Contact with Balanced Placement -->
                <div class="flex items-center h-full gap-2.5 sm:gap-4 text-xs font-bold text-white/95 shrink-0">
                    <a href="{{ route('branches.index') }}" class="nav-link-indicator h-full flex items-center hover:text-white transition-colors py-1">
                        <span>فروعنا</span>
                        <span class="hidden 2xl:inline mr-1">في مصر</span>
                    </a>
                    <span class="opacity-40">|</span>
                    <a href="{{ route('contact') }}" class="nav-link-indicator h-full flex items-center hover:text-white transition-colors py-1">
                        اتصل بنا
                    </a>
                </div>

            </div>
        </div>
    </nav>

</header>
