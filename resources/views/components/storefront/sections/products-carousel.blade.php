{{-- resources/views/components/storefront/sections/products-carousel.blade.php --}}
@props([
    'title' => 'الموديلات المميزة',
    'subtitle' => 'اختر من تشكيلتنا الراقية',
    'products' => collect(),
    'viewAllRoute' => null,
    'badge' => '⭐'
])

@if($products->isNotEmpty())
<section class="py-8 sm:py-12 bg-[#FBF6EA] border-b border-[#EADFC8]">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6" dir="rtl">
            <div class="flex items-center gap-3">
                <span class="text-xl sm:text-2xl">{{ $badge }}</span>
                <div>
                    <span class="text-xs font-bold text-[#A9552F] block">{{ $subtitle }}</span>
                    <h2 class="text-lg sm:text-2xl font-serif font-black text-[#2B2118]">{{ $title }}</h2>
                </div>
            </div>
            @if($viewAllRoute)
                <a href="{{ $viewAllRoute }}" class="text-xs font-bold text-[#382416] hover:text-[#A9552F] transition-colors flex items-center gap-1">
                    <span>عرض المزيد</span>
                    <span class="rotate-180">←</span>
                </a>
            @endif
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-5" dir="rtl">
            @foreach($products as $product)
                <x-storefront.ui.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif
