{{-- resources/views/components/storefront/sections/products-carousel.blade.php --}}
@props([
    'title' => 'المنتجات المميزة',
    'subtitle' => 'اختر من تشكيلتنا المختارة',
    'products' => collect(),
    'viewAllRoute' => null,
    'badge' => '⭐'
])

@if($products->isNotEmpty())
<section class="py-8 sm:py-12 bg-canvas-base border-b border-cream">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2 sm:gap-3">
                <span class="text-xl sm:text-2xl">{{ $badge }}</span>
                <div>
                    <span class="text-xs font-bold text-accent-700 block">{{ $subtitle }}</span>
                    <h2 class="text-lg sm:text-2xl font-black text-charcoal-heading">{{ $title }}</h2>
                </div>
            </div>
            @if($viewAllRoute)
                <a href="{{ $viewAllRoute }}" class="text-xs font-bold text-primary-800 hover:text-accent-600 transition-colors flex items-center gap-1">
                    <span>عرض المزيد</span>
                    <span>←</span>
                </a>
            @endif
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-5">
            @foreach($products as $product)
                <x-storefront.ui.product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>
@endif
