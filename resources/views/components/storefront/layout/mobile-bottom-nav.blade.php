{{-- resources/views/components/storefront/layout/mobile-bottom-nav.blade.php --}}
@php
    $cartCount = 0;
    try {
        $cartService = app(\App\Services\CartService::class);
        $cartCount = $cartService->getCount();
    } catch (\Throwable $e) {
        $cart = session('tawhid_cart', []);
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['quantity'] ?? 0);
        }
    }
@endphp

<div class="fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-gray-200/80 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] lg:hidden select-none"
     x-data="{
         cartCount: {{ (int)$cartCount }},
         bump: false
     }"
     @cart-updated.window="
         if ($event.detail) {
             cartCount = ($event.detail.count ?? $event.detail.cart_count ?? ($event.detail.totals ? $event.detail.totals.count : cartCount));
         }
         bump = true; 
         setTimeout(() => bump = false, 700);
     "
     dir="rtl">
    <div class="grid grid-cols-5 h-14 max-w-md mx-auto items-center text-center">
        
        <!-- 1. Home Link -->
        <a href="{{ route('site.home') }}" 
           class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('site.home') ? 'text-[#AC9653]' : 'text-gray-500 hover:text-[#AC9653]' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('site.home') ? 'stroke-[2.4]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-bold mt-0.5">الرئيسية</span>
        </a>

        <!-- 2. Store / All Clothing -->
        <a href="{{ route('shop') }}" 
           class="flex flex-col items-center justify-center py-1 transition-colors {{ request()->routeIs('shop') && !request('deals_only') ? 'text-[#AC9653]' : 'text-gray-500 hover:text-[#AC9653]' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('shop') && !request('deals_only') ? 'stroke-[2.4]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="text-[10px] font-bold mt-0.5">المتجر</span>
        </a>

        <!-- 3. Deals Only Highlight -->
        <a href="{{ route('shop', ['deals_only' => 1]) }}" 
           class="flex flex-col items-center justify-center py-1 transition-colors {{ request('deals_only') ? 'text-[#CBA367]' : 'text-gray-500 hover:text-[#CBA367]' }}">
            <svg class="w-5 h-5 {{ request('deals_only') ? 'stroke-[2.4] text-[#CBA367]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span class="text-[10px] font-bold mt-0.5 {{ request('deals_only') ? 'text-[#CBA367]' : '' }}">العروض</span>
        </a>

        <!-- 4. Cart Drawer Trigger with Real-Time Badge -->
        <button type="button" 
                @click="$dispatch('open-cart-drawer')"
                class="flex flex-col items-center justify-center py-1 text-gray-500 hover:text-[#AC9653] transition-colors cursor-pointer relative">
            <div class="relative">
                <svg class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="absolute -top-1.5 -right-2 bg-[#CBA367] text-white text-[9.5px] font-black w-4 h-4 rounded-full flex items-center justify-center shadow-xs transition-transform duration-300"
                      :class="bump ? 'scale-125 bg-red-500' : ''"
                      x-text="cartCount">
                    {{ $cartCount }}
                </span>
            </div>
            <span class="text-[10px] font-bold mt-0.5">السلة</span>
        </button>

        <!-- 5. Mobile Menu Drawer Trigger -->
        <button type="button" 
                @click="$dispatch('open-mobile-menu')"
                class="flex flex-col items-center justify-center py-1 text-gray-500 hover:text-[#AC9653] transition-colors cursor-pointer">
            <svg class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-[10px] font-bold mt-0.5">الأقسام</span>
        </button>

    </div>
</div>
