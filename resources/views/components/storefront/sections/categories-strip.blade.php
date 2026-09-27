{{-- resources/views/components/storefront/sections/categories-strip.blade.php --}}
@props(['categories' => collect()])

<section class="py-6 sm:py-8 bg-white border-y border-cream">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-5">
            <div>
                <span class="text-xs font-bold text-accent-700 uppercase tracking-wider block">تسوق حسب القسم</span>
                <h2 class="text-lg sm:text-2xl font-black text-charcoal-heading">أقسام ألبان وسوبرماركت التوحيد</h2>
            </div>
            <a href="{{ route('site.products') }}" class="text-xs font-bold text-primary-800 hover:text-accent-600 transition-colors flex items-center gap-1">
                <span>عرض الكل</span>
                <span>←</span>
            </a>
        </div>

        <!-- Categories Scrollable Strip -->
        <div class="flex items-center gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2">
            @foreach($categories as $category)
                <x-storefront.ui.category-card :category="$category" />
            @endforeach
        </div>
    </div>
</section>
