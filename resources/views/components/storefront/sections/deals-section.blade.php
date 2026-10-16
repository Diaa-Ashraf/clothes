{{-- resources/views/components/storefront/sections/deals-section.blade.php --}}
@props(['deals' => collect()])

@if($deals->isNotEmpty())
<section class="py-8 sm:py-12 bg-[#F3E9D2]/40 border-b border-[#EADFC8]">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex items-center justify-between mb-6" dir="rtl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#A9552F] text-[#FBF6EA] flex items-center justify-center font-bold text-lg shadow-sm">
                    %
                </div>
                <div>
                    <span class="text-xs font-bold text-[#A9552F] block">تخفيضات استثنائية</span>
                    <h2 class="text-lg sm:text-2xl font-serif font-black text-[#2B2118]">عروض الأزياء بأسعار خاصة اليوم</h2>
                </div>
            </div>
            <a href="{{ route('product.shop') }}" class="text-xs font-bold text-[#382416] hover:text-[#A9552F] transition-colors flex items-center gap-1">
                <span>تصفح كل التخفيضات</span>
                <span class="rotate-180">←</span>
            </a>
        </div>

        <!-- Deals Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-5" dir="rtl">
            @foreach($deals as $deal)
                @if($deal->product)
                    <x-storefront.ui.product-card :product="$deal->product" />
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
