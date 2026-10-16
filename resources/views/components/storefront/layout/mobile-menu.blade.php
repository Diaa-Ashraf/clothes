{{-- resources/views/components/storefront/layout/mobile-menu.blade.php --}}
@php
    $categories = \App\Models\Category::whereNull('parent_id')
        ->where('is_active', true)
        ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
        ->orderBy('sort_order')
        ->get();
@endphp

<div x-data="{ open: false }" 
     @open-mobile-menu.window="open = true" 
     @keydown.escape.window="open = false" 
     class="relative z-50 lg:hidden"
     dir="rtl">
    
    <!-- Backdrop -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs z-50"></div>

    <!-- Side Panel -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed inset-y-0 right-0 max-w-[320px] w-full bg-white shadow-2xl flex flex-col z-50 overflow-hidden border-s border-[#BDAB75]/30">
        
        <!-- Header -->
        <div class="p-4 bg-[#AC9653] text-white flex items-center justify-between shadow-md">
            <div class="flex items-center gap-2.5">
                @if(get_site_logo())
                    <div class="h-9 max-w-[120px] bg-white/10 rounded-lg p-1 flex items-center justify-center shrink-0">
                        <img src="{{ get_site_logo() }}" alt="{{ settings('site_name', 'أزياء لافيستا') }}" class="max-h-full max-w-full object-contain">
                    </div>
                @else
                    <span class="w-8 h-8 rounded-lg bg-white text-[#AC9653] font-black text-sm flex items-center justify-center shadow-xs">
                        {{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
                    </span>
                @endif
                <div>
                    <h2 class="font-bold text-base text-white leading-tight">{{ settings('site_name', 'أزياء لافيستا') }}</h2>
                    <p class="text-[9px] text-[#F3EFE6] font-medium tracking-wider uppercase">{{ settings('site_tagline', 'LAVISTA FASHION') }}</p>
                </div>
            </div>
            <button @click="open = false" type="button" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-sm cursor-pointer" aria-label="إغلاق">
                ✕
            </button>
        </div>

        <!-- Quick Search -->
        <div class="p-3 border-b border-gray-100 bg-gray-50">
            <form action="{{ route('shop') }}" method="GET" class="relative">
                <input type="text" 
                       name="search" 
                       placeholder="ابحث عن قميص، فستان، جاكيت..." 
                       class="w-full bg-white border border-gray-300 focus:border-[#AC9653] rounded-lg py-2 pr-9 pl-3 text-xs text-gray-800 placeholder-gray-400 outline-none" />
                <button type="submit" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[#AC9653]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>

        <!-- Menu Navigation Links -->
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            
            <!-- Core Links -->
            <div class="space-y-1 text-right">
                <span class="text-[10px] font-bold text-[#AC9653] uppercase tracking-wider px-2 mb-1 block">تصفح الأقسام</span>
                
                <a href="{{ route('site.home') }}" @click="open = false" 
                   class="flex items-center justify-between px-3 py-2 text-xs font-bold text-gray-800 hover:bg-[#AC9653]/10 rounded-lg">
                    <span>الرئيسية</span>
                    <span class="text-gray-400">›</span>
                </a>

                <a href="{{ route('shop') }}" @click="open = false" 
                   class="flex items-center justify-between px-3 py-2 text-xs font-bold text-gray-800 hover:bg-[#AC9653]/10 rounded-lg">
                    <span>كافة المنتجات (المتجر)</span>
                    <span class="text-gray-400">›</span>
                </a>

                @foreach($categories as $cat)
                    <div x-data="{ expanded: false }" class="border-b border-gray-50 last:border-0 py-1">
                        <div class="flex items-center justify-between px-3 py-1.5">
                            <a href="{{ route('shop', ['category_id' => $cat->id]) }}" @click="open = false" class="text-xs font-bold text-gray-700 hover:text-[#AC9653]">
                                {{ $cat->name }}
                            </a>
                            @if($cat->children->isNotEmpty())
                                <button type="button" @click="expanded = !expanded" class="p-1 text-gray-400 hover:text-[#AC9653]">
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        @if($cat->children->isNotEmpty())
                            <div x-show="expanded" x-cloak class="pr-5 py-1 space-y-1">
                                @foreach($cat->children as $sub)
                                    <a href="{{ route('shop', ['category_id' => $sub->id]) }}" @click="open = false" class="block py-1 text-[11px] text-gray-500 hover:text-[#CBA367]">
                                        • {{ $sub->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach

                <!-- Deals Link -->
                <a href="{{ route('shop', ['deals_only' => 1]) }}" @click="open = false" 
                   class="flex items-center justify-between px-3 py-2 text-xs font-bold bg-[#CBA367] text-white rounded-lg mt-2">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>أقوى العروض والتخفيضات</span>
                    </span>
                    <span class="text-[10px] bg-white/20 px-1.5 py-0.5 rounded font-bold">خصومات</span>
                </a>
            </div>

            <!-- Egyptian Support & Contact -->
            <div class="pt-4 border-t border-gray-200 space-y-2 text-right">
                <span class="text-[10px] font-bold text-gray-400 block px-2">خدمة العملاء والشحن</span>
                <div class="p-3 bg-gray-50 rounded-lg text-xs space-y-1.5">
                    <div class="font-bold text-gray-800 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#AC9653]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"/>
                        </svg>
                        <span>خدمة العملاء: 01000000000</span>
                    </div>
                    <div class="text-[11px] text-gray-600 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#AC9653]"></span>
                        <span>شحن لكافة المحافظات مع المعاينة قبل الاستلام</span>
                    </div>
                    <div class="text-[11px] text-gray-600 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#CBA367]"></span>
                        <span>دفع عند الاستلام أو فودافون كاش وإنستاباي</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('branches.index') }}" @click="open = false" class="text-xs font-semibold text-gray-600 hover:text-[#AC9653]">
                        فروعنا في مصر
                    </a>
                    <a href="{{ route('orders.track') }}" @click="open = false" class="text-xs font-semibold text-gray-600 hover:text-[#AC9653]">
                        تتبع الطلب
                    </a>
                    <a href="{{ route('contact') }}" @click="open = false" class="text-xs font-semibold text-gray-600 hover:text-[#AC9653]">
                        اتصل بنا
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>
