{{-- resources/views/components/storefront/sections/bundles-section.blade.php --}}
@props(['bundles' => collect()])

@if($bundles->isNotEmpty())
<section class="py-10 sm:py-14 bg-gradient-to-b from-white to-canvas-base border-b border-orange-100">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-8">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-red-50 text-red-700 border border-red-200 mb-2">
                    <span>✨</span>
                    <span>توفير حقيقي للعائلات</span>
                </span>
                <h2 class="text-xl sm:text-3xl font-black text-gray-900 tracking-tight">عروض التوحيد المجمعة (Bundles)</h2>
                <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">باقات توفيرية مجمعة مختارة بعناية لأفضل المنتجات بأسعار جملة مخفضة</p>
            </div>
            <a href="{{ route('site.products') }}" class="text-xs font-black text-red-600 hover:text-red-700 flex items-center gap-1 group self-start sm:self-auto">
                <span>تصفح جميع المنتجات</span>
                <span class="group-hover:translate-x-1 transition-transform rotate-180">←</span>
            </a>
        </div>

        <!-- Bundles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bundles as $bundle)
            <div class="bg-white border-2 border-orange-100/80 hover:border-red-500/50 rounded-3xl p-5 flex flex-col justify-between shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group overflow-hidden">
                <div class="space-y-4">
                    
                    {{-- 1. Bundle Visual / Image Header --}}
                    @if($bundle->image_url)
                        <div class="relative w-full h-48 rounded-2xl overflow-hidden bg-canvas-base border border-orange-100/60 shadow-inner group-hover:scale-[1.02] transition-transform duration-300">
                            <img src="{{ $bundle->image_url }}" 
                                 alt="{{ $bundle->name }}" 
                                 class="w-full h-full object-cover object-center">
                            
                            @if($bundle->discount_percent > 0)
                                <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-red-600 to-rose-500 text-white shadow-md shadow-red-500/30">
                                    وفر {{ $bundle->discount_percent }}%
                                </span>
                            @endif
                        </div>
                    @else
                        {{-- Fallback Multi-product Visual Collage when no main banner image --}}
                        <div class="relative w-full rounded-2xl bg-canvas-base border border-orange-100 p-3 shadow-inner">
                            <div class="flex items-center justify-center gap-2 flex-wrap py-2">
                                @foreach($bundle->products->take(4) as $product)
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white p-1 border border-orange-200/80 shadow-2xs flex items-center justify-center overflow-hidden">
                                        <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover rounded-lg"
                                             title="{{ $product->name }}">
                                    </div>
                                    @if(!$loop->last)
                                        <span class="text-orange-400 font-black text-sm select-none">+</span>
                                    @endif
                                @endforeach
                            </div>
                            @if($bundle->discount_percent > 0)
                                <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-gradient-to-r from-red-600 to-rose-500 text-white shadow-sm">
                                    وفر {{ $bundle->discount_percent }}%
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- 2. Bundle Title & Description --}}
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="font-black text-base sm:text-lg text-gray-900 group-hover:text-red-600 transition-colors leading-snug">
                                {{ $bundle->name }}
                            </h3>
                            @if(!$bundle->image_url && $bundle->discount_percent > 0)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-red-600 text-white shrink-0">
                                    وفر {{ $bundle->discount_percent }}%
                                </span>
                            @endif
                        </div>
                        @if($bundle->description)
                            <p class="text-xs text-gray-500 font-medium mt-1.5 line-clamp-2 leading-relaxed">{{ $bundle->description }}</p>
                        @endif
                    </div>

                    {{-- 3. Products List in Bundle --}}
                    @if($bundle->products->isNotEmpty())
                    <div class="p-3.5 rounded-2xl bg-canvas-base border border-orange-100/90 space-y-2">
                        <span class="block text-[11px] font-black text-orange-900 flex items-center gap-1.5">
                            <span>🛍️</span>
                            <span>محتويات هذه الباقة:</span>
                        </span>
                        <div class="space-y-1.5 divide-y divide-orange-100/60">
                            @foreach($bundle->products as $bp)
                            <div class="flex items-center justify-between text-xs pt-1.5 first:pt-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    @if($bp->image_url)
                                        <img src="{{ $bp->image_url }}" alt="" class="w-5 h-5 rounded-md object-cover border border-orange-100 shrink-0">
                                    @endif
                                    <span class="text-gray-800 font-bold truncate max-w-[200px]">{{ $bp->name }}</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md bg-white border border-orange-200 font-black text-[11px] text-red-600 shrink-0 shadow-2xs">
                                    {{ $bp->pivot->quantity ?? 1 }}x
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                {{-- 4. Price & Add To Cart CTA --}}
                <div class="pt-4 mt-4 border-t border-orange-100 flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold block">السعر الإجمالي للباقة:</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-lg sm:text-xl font-black text-red-600 tabular-nums">
                                {{ format_currency($bundle->bundle_price) }}
                            </span>
                            @if($bundle->original_total > $bundle->bundle_price)
                            <span class="text-xs text-gray-400 line-through tabular-nums">
                                {{ format_currency($bundle->original_total) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('bundle.add-to-cart') }}" method="POST" 
                          onsubmit="event.preventDefault(); 
                          const btn = this.querySelector('button');
                          const origText = btn.innerHTML;
                          btn.disabled = true;
                          btn.innerHTML = 'جاري الإضافة...';
                          fetch(this.action, {
                              method: 'POST',
                              headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json'},
                              body: new FormData(this)
                          })
                          .then(res => res.json())
                          .then(data => {
                              toastr.success(data.message || 'تمت إضافة العرض المجمع للسلة بنجاح');
                              window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
                              btn.disabled = false;
                              btn.innerHTML = '✓ تمت الإضافة';
                              setTimeout(() => { btn.innerHTML = origText; }, 2000);
                          })
                          .catch(() => {
                              toastr.error('حدث خطأ أثناء الإضافة');
                              btn.disabled = false;
                              btn.innerHTML = origText;
                          });">
                        @csrf
                        <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white font-black text-xs shadow-md shadow-red-500/20 hover:shadow-red-500/30 active:scale-95 transition-all cursor-pointer flex items-center gap-1.5">
                            <span>🛒</span>
                            <span>أضف العرض</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
