{{-- resources/views/components/storefront/sections/categories-strip.blade.php --}}
@props(['categories' => collect()])

<section class="py-6 sm:py-8 bg-[#FBF6EA] border-y border-[#EADFC8]">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-5" dir="rtl">
            <div>
                <span class="text-xs font-bold text-[#A9552F] tracking-wide block">تشكيلاتنا المختارة</span>
                <h2 class="text-lg sm:text-2xl font-serif font-black text-[#2B2118]">تسوق حسب تصنيف الأزياء</h2>
            </div>
            <a href="{{ route('site.products') }}" class="text-xs font-bold text-[#382416] hover:text-[#A9552F] transition-colors flex items-center gap-1">
                <span>عرض كافة الأقسام</span>
                <span class="rotate-180">←</span>
            </a>
        </div>

        <!-- Categories Scrollable Strip -->
        <div class="flex items-center gap-3 sm:gap-4 overflow-x-auto no-scrollbar pb-2" dir="rtl">
            @foreach($categories as $category)
                <x-storefront.ui.category-card :category="$category" />
            @endforeach
        </div>
    </div>
</section>
