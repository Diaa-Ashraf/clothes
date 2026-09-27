{{-- resources/views/components/storefront/sections/hero-slider.blade.php --}}
@props(['banners' => collect()])

<section class="relative overflow-hidden bg-canvas-base py-3 sm:py-5">
    <div class="container mx-auto px-4">
        <div class="swiper hero-swiper rounded-3xl overflow-hidden shadow-xl border border-orange-100" style="height: 440px; max-height: 480px;">
            <div class="swiper-wrapper h-full">
                @forelse($banners as $banner)
                <div class="swiper-slide relative h-full w-full bg-gradient-to-r from-red-900 via-red-600 to-orange-500 flex items-center overflow-hidden" style="height: 100%;">
                    @if($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none scale-100">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-l from-black/85 via-black/50 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/20"></div>
                    <div class="relative z-10 container mx-auto px-6 sm:px-12 py-8 max-w-2xl text-white space-y-3.5 text-right" dir="rtl">
                        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-black bg-gradient-to-r from-amber-400 to-orange-500 text-gray-900 shadow-lg">
                            <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                            <span>ألبان وسوبرماركت التوحيد بالعامرية</span>
                        </span>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black leading-tight tracking-tight drop-shadow-md">
                            {{ $banner->title }}
                        </h2>
                        @if($banner->description)
                        <p class="text-xs sm:text-sm md:text-base text-white/95 leading-relaxed font-medium drop-shadow-sm line-clamp-2">
                            {{ $banner->description }}
                        </p>
                        @endif
                        <div class="pt-2 flex flex-wrap items-center gap-3">
                            <a href="{{ $banner->url ?: route('site.products') }}" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-amber-400 to-orange-500 hover:from-amber-500 hover:to-orange-600 text-gray-950 font-black text-xs sm:text-sm transition-all shadow-lg hover:shadow-orange-500/50 hover:scale-105 active:scale-95 flex items-center gap-2 cursor-pointer">
                                <span>تسوق الآن</span>
                                <span class="rotate-180">←</span>
                            </a>
                            <a href="{{ route('offers.index') }}" class="px-7 py-3 rounded-2xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-white border border-white/40 font-bold text-xs sm:text-sm transition-all shadow-sm cursor-pointer">
                                مجلة العروض
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Default Supermarket Hero Banner 1 -->
                <div class="swiper-slide relative h-full w-full bg-gradient-to-r from-red-800 via-red-600 to-orange-500 flex items-center overflow-hidden" style="height: 100%;">
                    <div class="relative z-10 container mx-auto px-6 sm:px-12 py-8 max-w-2xl text-white space-y-3.5 text-right" dir="rtl">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-black bg-amber-400 text-gray-900 shadow-md">
                            <span>منتجات بلدية 100% طازجة يومياً</span>
                        </span>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black leading-tight tracking-tight">
                            ألبان وسوبرماركت التوحيد بالعامرية
                        </h2>
                        <p class="text-xs sm:text-sm md:text-base text-white/90 leading-relaxed font-medium">
                            قشطة، حليب بلدي طبيعي، جبن أبيض وقريش ورومي فاخر، وكل احتياجات بيتك من السوبرماركت بأعلى جودة وتوصيل فوري لباب البيت.
                        </p>
                        <div class="pt-2 flex flex-wrap items-center gap-3">
                            <a href="{{ route('site.products') }}" class="px-7 py-3 rounded-2xl bg-amber-400 hover:bg-amber-500 text-gray-950 font-black text-xs sm:text-sm transition-all shadow-lg">
                                تصفح كل المنتجات ←
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
        new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.hero-swiper .swiper-pagination',
                clickable: true,
            },
        });
    });
</script>
@endpush