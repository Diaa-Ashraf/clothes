{{-- resources/views/components/storefront/sections/bundles-section.blade.php --}}
@props(['bundles' => collect()])

@if($bundles->isNotEmpty())
<section class="py-10 sm:py-14 bg-gradient-to-b from-[#FDFBF7] to-[#FBF6EA] border-b border-[#EADFC8]">
    <div class="container mx-auto px-4" dir="rtl">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-8">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] mb-2">
                    <svg class="w-3.5 h-3.5 text-[#CBA367]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span>تنسيقات متكاملة وتوفير ذكي</span>
                </span>
                <h2 class="text-xl sm:text-3xl font-serif font-black text-[#2B2118] tracking-tight">أطقم وتنسيقات الأزياء الكاملة (Bundles)</h2>
                <p class="text-xs sm:text-sm text-[#8F7B63] font-medium mt-1">تنسيقات ملابس متناسقة ومختارة بعناية لأناقة متكاملة بسعر موفر</p>
            </div>
            <a href="{{ route('site.products') }}" class="text-xs font-bold text-[#382416] hover:text-[#A9552F] flex items-center gap-1 group self-start sm:self-auto">
                <span>تصفح جميع المنتجات</span>
                <span class="rotate-180">←</span>
            </a>
        </div>

        <!-- Bundles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bundles as $bundle)
            <div class="bg-[#FDFBF7] border border-[#EADFC8] hover:border-[#CDB78F] rounded-3xl p-5 flex flex-col justify-between shadow-xs hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group overflow-hidden">
                <div class="space-y-4">
                    
                    {{-- 1. Bundle Visual / Image Header --}}
                    @if($bundle->image_url)
                        <div class="relative w-full h-48 rounded-2xl overflow-hidden bg-[#F3E9D2] border border-[#EADFC8] shadow-inner group-hover:scale-[1.01] transition-transform duration-300">
                            <img src="{{ $bundle->image_url }}" 
                                 alt="{{ $bundle->name }}" 
                                 class="w-full h-full object-cover object-center">
                            
                            @if($bundle->discount_percent > 0)
                                <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-bold bg-[#A9552F] text-[#FBF6EA] shadow-md">
                                    وفر {{ $bundle->discount_percent }}%
                                </span>
                            @endif
                        </div>
                    @else
                        {{-- Fallback Multi-product Visual Collage --}}
                        <div class="relative w-full rounded-2xl bg-[#F3E9D2]/50 border border-[#EADFC8] p-3 shadow-inner">
                            <div class="flex items-center justify-center gap-2 flex-wrap py-2">
                                @foreach($bundle->products->take(4) as $product)
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white p-1 border border-[#EADFC8] shadow-2xs flex items-center justify-center overflow-hidden">
                                        <img src="{{ $product->image_url ?? asset('assets/site/img/product-placeholder.png') }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover rounded-lg"
                                             title="{{ $product->name }}">
                                    </div>
                                    @if(!$loop->last)
                                        <span class="text-[#CDB78F] font-black text-sm select-none">+</span>
                                    @endif
                                @endforeach
                            </div>
                            @if($bundle->discount_percent > 0)
                                <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#A9552F] text-[#FBF6EA] shadow-xs">
                                    وفر {{ $bundle->discount_percent }}%
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- 2. Bundle Title & Description --}}
                    <div>
                        <h3 class="font-serif font-black text-base sm:text-lg text-[#2B2118] group-hover:text-[#A9552F] transition-colors line-clamp-1">
                            {{ $bundle->name }}
                        </h3>
                        @if($bundle->description)
                            <p class="text-xs text-[#8F7B63] font-medium mt-1 line-clamp-2 leading-relaxed">
                                {{ $bundle->description }}
                            </p>
                        @endif
                    </div>

                    {{-- 3. Products List Included in Bundle --}}
                    <div class="space-y-1.5 bg-[#F3E9D2]/30 rounded-2xl p-3 border border-[#EADFC8]">
                        <span class="text-[11px] font-bold text-[#382416] block mb-1">قطع الطقم المتضمنة:</span>
                        @foreach($bundle->products as $product)
                            <div class="flex items-center justify-between text-xs text-[#2B2118]">
                                <span class="font-medium line-clamp-1 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#A9552F]"></span>
                                    <span>{{ $product->name }}</span>
                                </span>
                                <span class="text-[11px] text-[#8F7B63] font-bold tabular-nums shrink-0">
                                    {{ format_currency($product->pivot->custom_price ?? $product->price) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 4. Pricing & Add to Cart Action --}}
                <div class="pt-4 mt-4 border-t border-[#EADFC8] flex items-center justify-between gap-3">
                    <div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-lg sm:text-xl font-black text-[#382416] tabular-nums">
                                {{ format_currency($bundle->price) }}
                            </span>
                            @if($bundle->original_price > $bundle->price)
                                <span class="text-xs text-[#8F7B63] line-through tabular-nums">
                                    {{ format_currency($bundle->original_price) }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] text-[#A9552F] font-bold block">
                            سعر الطقم بالكامل
                        </span>
                    </div>

                    <button type="button" 
                            onclick="window.addBundleToCart({{ $bundle->id }}, this)"
                            class="py-2.5 px-4 rounded-xl bg-[#382416] hover:bg-[#2B2118] active:scale-95 text-[#FBF6EA] font-bold text-xs shadow-xs hover:shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4 text-[#CDB78F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>أضف الطقم للسلة</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
