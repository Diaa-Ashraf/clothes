{{-- resources/views/components/storefront/sections/hero-slider.blade.php --}}
@props(['banners' => collect()])

<section class="relative overflow-hidden bg-[#FBF6EA] py-4 sm:py-6">
    <div class="container mx-auto px-4">
        <div class="swiper hero-swiper rounded-3xl overflow-hidden shadow-lg border border-[#EADFC8]" style="height: 460px; max-height: 500px;">
            <div class="swiper-wrapper h-full">
                @forelse($banners as $banner)
                <div class="swiper-slide relative h-full w-full bg-[#2B2118] flex items-center overflow-hidden" style="height: 100%;">
                    @if($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none scale-100">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-l from-[#2B2118]/90 via-[#2B2118]/60 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#2B2118]/70 via-transparent to-[#2B2118]/30"></div>
                    
                    <div class="relative z-10 container mx-auto px-6 sm:px-14 py-8 max-w-2xl text-[#FBF6EA] space-y-4 text-right" dir="rtl">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-[#A9552F]"></span>
                            <span>{{ settings('site_name', 'أزياء لافيستا') }} | تشكيلة الموسم</span>
                        </span>
                        
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-serif font-black leading-tight tracking-tight drop-shadow-sm text-[#FBF6EA]">
                            {{ $banner->title }}
                        </h2>
                        
                        @if($banner->description)
                        <p class="text-xs sm:text-sm md:text-base text-[#F3E9D2]/90 leading-relaxed font-normal line-clamp-2">
                            {{ $banner->description }}
                        </p>
                        @endif
                        
                        <div class="pt-3 flex flex-wrap items-center gap-3">
                            <a href="{{ $banner->url ?: route('site.products') }}" class="px-8 py-3.5 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs sm:text-sm transition-all shadow-md hover:shadow-lg hover:scale-102 active:scale-98 flex items-center gap-2 cursor-pointer border border-[#CDB78F]/30">
                                <span>تسوق التشكيلة الآن</span>
                                <span class="rotate-180">←</span>
                            </a>
                            <a href="{{ route('offers.index') }}" class="px-7 py-3.5 rounded-2xl bg-white/15 hover:bg-white/25 backdrop-blur-md text-[#FBF6EA] border border-white/30 font-bold text-xs sm:text-sm transition-all shadow-xs cursor-pointer">
                                العروض والتخفيضات
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Default Fashion Hero Banner -->
                <div class="swiper-slide relative h-full w-full bg-[#2B2118] flex items-center overflow-hidden" style="height: 100%;">
                    <div class="absolute inset-0 bg-gradient-to-l from-[#2B2118]/95 via-[#2B2118]/70 to-[#382416]/50"></div>
                    <div class="relative z-10 container mx-auto px-6 sm:px-14 py-8 max-w-2xl text-[#FBF6EA] space-y-4 text-right" dir="rtl">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] shadow-sm">
                            <span>خامات قطنية وفخامة استثنائية</span>
                        </span>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-serif font-black leading-tight tracking-tight text-[#FBF6EA]">
                            مجموعة {{ settings('site_name', 'أزياء لافيستا') }} الجديدة
                        </h2>
                        <p class="text-xs sm:text-sm md:text-base text-[#F3E9D2]/90 leading-relaxed font-normal">
                            اكتشف أحدث صيحات الموضة للأزياء الرجالية والنسائية وأطقم الأطفال الراقية، بخامات طبيعية مختارة وأعلى دقة في التفصيل والراحة.
                        </p>
                        <div class="pt-3 flex flex-wrap items-center gap-3">
                            <a href="{{ route('site.products') }}" class="px-8 py-3.5 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs sm:text-sm transition-all shadow-md border border-[#CDB78F]/40 flex items-center gap-2">
                                <span>تصفح الكتالوج الكامل</span>
                                <span class="rotate-180">←</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Swiper Controls -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.hero-swiper', {
                loop: true,
                autoplay: {
                    delay: 5500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.hero-swiper .swiper-pagination',
                    clickable: true,
                },
            });
        }
    });
</script>
@endpush