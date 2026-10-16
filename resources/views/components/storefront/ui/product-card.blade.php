{{-- resources/views/components/storefront/ui/product-card.blade.php --}}
@props(['product'])

@php
    $finalPrice = $product->final_price ?? $product->discount_price ?? $product->price;
    $hasDiscount = ($product->discount_price && $product->discount_price < $product->price) || ($product->has_discount ?? false);
    $discountPercent = $product->discount_percentage ?? ($hasDiscount && $product->price > 0 
        ? round((($product->price - $finalPrice) / $product->price) * 100) 
        : 0);
    $isInStock = ($product->is_available && ($product->stock_quantity > 0 || $product->quantity > 0));
    $imageUrl = $product->image_url ?: asset('assets/site/img/product-placeholder.png');
    $variants = $product->relationLoaded('variants') ? $product->variants : $product->variants()->with('attributeValues.attribute')->get();
    if ($variants->isNotEmpty() && !$variants->first()->relationLoaded('attributeValues')) {
        $variants->load('attributeValues.attribute');
    }
    $hasVariants = $variants->isNotEmpty();

    $availableSizes = $hasVariants ? $variants->flatMap(function($v) {
        return $v->attributeValues->filter(fn($av) => ($av->attribute->code ?? '') === 'size');
    })->unique('id')->sortBy('sort_order') : collect();

    $availableColors = $hasVariants ? $variants->flatMap(function($v) {
        return $v->attributeValues->filter(fn($av) => ($av->attribute->code ?? '') === 'color');
    })->unique('id')->sortBy('sort_order') : collect();

    $firstInStockVariant = $hasVariants ? ($variants->firstWhere('stock_quantity', '>', 0) ?? $variants->first()) : null;
    $initialVariantId = $firstInStockVariant ? $firstInStockVariant->id : 'null';
    $initialVariantPrice = $firstInStockVariant ? (float)($firstInStockVariant->effective_price ?? $finalPrice) : (float)$finalPrice;
    $initialVariantLabel = $firstInStockVariant ? ($firstInStockVariant->attributeValues->pluck('value')->implode(' • ') ?: 'قياسي') : '';
@endphp

<div class="group relative bg-white rounded-xl border border-gray-200 card-lift shadow-xs flex flex-col h-full overflow-hidden" 
     x-data="{ 
         adding: false,
         variantModalOpen: false,
         selectedVariantId: {{ $initialVariantId }},
         selectedVariantPrice: {{ $initialVariantPrice }},
         selectedVariantLabel: '{{ addslashes($initialVariantLabel) }}',

         selectVariant(id, price, label) {
             this.selectedVariantId = id;
             this.selectedVariantPrice = price;
             this.selectedVariantLabel = label;
         },

         quickAddToCart() {
             @if($hasVariants)
                 this.variantModalOpen = true;
             @else
                 this.submitAdd(null);
             @endif
         },

         submitAdd(variantId) {
             this.adding = true;
             fetch('{{ route('AddToCart', $product->id) }}', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                     'Accept': 'application/json'
                 },
                 body: JSON.stringify({
                     quantity: 1,
                     variant_id: variantId
                 })
             })
             .then(res => res.json())
             .then(data => {
                 this.adding = false;
                 this.variantModalOpen = false;
                 if (data.success || data.status) {
                     toastr.success(data.message || 'تمت إضافة القطعة إلى سلة مشترياتك بنجاح');
                     window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count ?? data.count } }));
                 } else {
                     toastr.warning(data.message || 'تعذر الإضافة');
                 }
             })
             .catch(err => {
                 this.adding = false;
                 toastr.error('حدث خطأ أثناء الإضافة للسلة');
             });
         }
     }">
    
    <!-- Badges (Discount, Stock, Featured) -->
    <div class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 z-10 flex flex-col gap-1 items-start">
        @if($hasDiscount && $discountPercent > 0)
            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-md text-[9.5px] sm:text-[10.5px] font-black bg-[#CBA367] text-white shadow-xs shimmer-badge">
                وفر {{ $discountPercent }}%
            </span>
        @endif
        @if(!$isInStock)
            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-bold bg-gray-900/80 text-white backdrop-blur-xs">
                نفد مؤقتاً
            </span>
        @elseif($product->featured)
            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-bold bg-[#AC9653] text-white shadow-xs">
                الأكثر طلباً
            </span>
        @endif
    </div>

    <!-- Wishlist Button -->
    <button type="button" 
            onclick="window.toggleWishlist({{ $product->id }}, this)"
            class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 z-10 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white/90 hover:bg-white text-gray-500 hover:text-red-500 shadow-sm flex items-center justify-center transition-all cursor-pointer border border-gray-100"
            title="إضافة للمفضلة">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform active:scale-125" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
    </button>

    <!-- Product Image with Link -->
    <a href="{{ route('product.details', $product->id) }}" class="relative block pt-[118%] overflow-hidden bg-gray-50 border-b border-gray-100">
        @if($product->image_url)
            <img src="{{ $product->image_url }}" 
                 alt="{{ $product->name }}" 
                 loading="lazy"
                 class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500 ease-out" />
        @else
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gray-200 text-gray-500 flex items-center justify-center absolute inset-0 m-auto">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        @endif

        @if($product->brand)
            <span class="absolute bottom-1.5 right-1.5 sm:bottom-2 sm:right-2 px-1.5 sm:px-2 py-0.5 rounded bg-white/90 text-gray-800 text-[9px] sm:text-[10px] font-bold shadow-2xs backdrop-blur-xs">
                {{ $product->brand->name }}
            </span>
        @endif
    </a>

    <!-- Card Content -->
    <div class="p-2.5 sm:p-4 flex flex-col flex-grow justify-between gap-2 sm:gap-3 text-right" dir="rtl">
        <div>
            <!-- Category Tag -->
            @if($product->category)
                <a href="{{ route('shop', ['category_id' => $product->category->id]) }}" 
                   class="text-[10px] sm:text-[11px] font-semibold text-[#AC9653] hover:underline inline-block mb-0.5 sm:mb-1">
                    {{ $product->category->name }}
                </a>
            @endif

            <!-- Product Name -->
            <h3 class="font-bold text-xs sm:text-sm text-gray-900 group-hover:text-[#AC9653] transition-colors line-clamp-2 leading-tight sm:leading-snug min-h-[2.1rem] sm:min-h-[2.5rem]">
                <a href="{{ route('product.details', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Available Sizes & Color Swatches Preview -->
            @if($availableSizes->isNotEmpty() || $availableColors->isNotEmpty())
                <div class="flex items-center justify-between gap-1.5 mt-2 pt-2 border-t border-gray-100 text-right">
                    <!-- Sizes list -->
                    @if($availableSizes->isNotEmpty())
                        <div class="flex items-center gap-1 flex-wrap">
                            @foreach($availableSizes->take(3) as $sz)
                                <span class="px-1.5 py-0.5 text-[8.5px] sm:text-[10px] font-bold rounded bg-[#FAF8F5] text-[#2E2416] border border-[#BDAB75]/40" title="مقاس {{ $sz->value }}">
                                    {{ $sz->value }}
                                </span>
                            @endforeach
                            @if($availableSizes->count() > 3)
                                <span class="text-[8.5px] sm:text-[9px] text-[#AC9653] font-bold">+{{ $availableSizes->count() - 3 }}</span>
                            @endif
                        </div>
                    @else
                        <div></div>
                    @endif

                    <!-- Colors swatches -->
                    @if($availableColors->isNotEmpty())
                        <div class="flex items-center gap-1 shrink-0">
                            @foreach($availableColors->take(2) as $col)
                                <span class="w-3 h-3 sm:w-3.5 sm:h-3.5 rounded-full border border-gray-300 shadow-2xs inline-block" 
                                      style="background-color: {{ $col->color_code ?: '#2E2416' }}" 
                                      title="{{ $col->value }}"></span>
                            @endforeach
                            @if($availableColors->count() > 2)
                                <span class="text-[8.5px] sm:text-[9px] text-gray-400 font-bold">+{{ $availableColors->count() - 2 }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <!-- Pricing Row in EGP (ج.م) -->
            <div class="flex items-baseline justify-between mb-2 sm:mb-3 pt-1.5 sm:pt-2 border-t border-gray-100">
                <div class="flex items-baseline gap-1.5 sm:gap-2">
                    <span class="text-sm sm:text-lg font-black text-gray-900 tabular-nums">
                        {{ format_currency($finalPrice) }}
                    </span>
                    @if($hasDiscount)
                        <span class="text-[10px] sm:text-xs text-gray-400 line-through tabular-nums">
                            {{ format_currency($product->price) }}
                        </span>
                    @endif
                </div>
                @if($product->gender)
                    <span class="text-[9px] sm:text-[10px] text-gray-500 font-semibold bg-gray-100 px-1.5 sm:px-2 py-0.5 rounded">
                        {{ $product->gender == 'men' ? 'رجالي' : ($product->gender == 'women' ? 'نسائي' : ($product->gender == 'kids' ? 'أطفال' : 'للجنسين')) }}
                    </span>
                @endif
            </div>

            <!-- Add to Cart Action Button in Rich Camel #CBA367 -->
            @if($isInStock)
                <button type="button" 
                        @click="quickAddToCart()"
                        :disabled="adding"
                        class="w-full py-2 sm:py-2.5 px-2 rounded-lg bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 text-white text-[11px] sm:text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg x-show="!adding" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <svg x-show="adding" x-cloak class="w-3.5 h-3.5 sm:w-4 sm:h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="adding ? 'جاري الإضافة...' : 'أضف للسلة'">
                        أضف للسلة
                    </span>
                </button>
            @else
                <button type="button" disabled class="w-full py-2 sm:py-2.5 px-2 rounded-lg bg-gray-100 text-gray-400 text-[10px] sm:text-xs font-bold cursor-not-allowed text-center">
                    نفد مؤقتاً
                </button>
            @endif
        </div>
    </div>

    <!-- Quick Size/Variant Selection Modal -->
    @if($hasVariants)
        <template x-teleport="body">
            <div x-show="variantModalOpen" 
                 x-cloak 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs select-none"
                 @click.self="variantModalOpen = false"
                 @keydown.escape.window="variantModalOpen = false">
                
                <div x-show="variantModalOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-2xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-gray-200 space-y-4 text-right" 
                     dir="rtl"
                     @click.stop>
                    
                    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-[#FAF8F5] text-[#AC9653] flex items-center justify-center border border-[#BDAB75]/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 leading-tight">اختر المقاس واللون المناسب</h4>
                                <p class="text-[11px] text-gray-400 mt-0.5 line-clamp-1">{{ $product->name }}</p>
                            </div>
                        </div>
                        <button type="button" 
                                @click="variantModalOpen = false" 
                                class="w-7 h-7 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center text-xs transition-colors cursor-pointer">
                            ✕
                        </button>
                    </div>

                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($variants as $variant)
                            @php
                                $vPrice = $variant->effective_price ?? $finalPrice;
                                $vLabel = $variant->attributeValues->pluck('value')->implode(' • ');
                                $isAvailable = $variant->stock_quantity > 0;
                            @endphp
                            <label @click="if({{ $isAvailable ? 'true' : 'false' }}) selectVariant({{ $variant->id }}, {{ (float)$vPrice }}, '{{ addslashes($vLabel ?: 'قياسي') }}')"
                                   class="flex items-center justify-between p-3 rounded-xl border transition-all {{ $isAvailable ? 'cursor-pointer hover:border-[#AC9653]' : 'opacity-60 cursor-not-allowed bg-gray-50' }}"
                                   :class="selectedVariantId == {{ $variant->id }} ? 'border-[#AC9653] bg-[#AC9653]/10 ring-1 ring-[#AC9653]' : 'border-gray-200 bg-white'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" 
                                           name="variant_select_{{ $product->id }}" 
                                           value="{{ $variant->id }}" 
                                           x-model="selectedVariantId"
                                           {{ !$isAvailable ? 'disabled' : '' }}
                                           class="accent-[#AC9653] w-4 h-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-900">
                                            {{ $vLabel ?: 'مقاس قياسي' }}
                                        </span>
                                        <span class="block text-[10px] {{ $isAvailable ? 'text-gray-400' : 'text-red-500 font-semibold' }}">
                                            {{ $isAvailable ? 'متوفر بالمخزن (' . $variant->stock_quantity . ' قطع)' : 'نفد مؤقتاً من المخزن' }}
                                        </span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-gray-900 tabular-nums">
                                    {{ format_currency($vPrice) }}
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="block text-[10px] text-gray-400 font-medium">السعر</span>
                            <span class="text-sm sm:text-base font-black text-gray-900 tabular-nums" x-text="selectedVariantPrice + ' ج.م'">
                                {{ format_currency($finalPrice) }}
                            </span>
                        </div>
                        <button type="button" 
                                @click="submitAdd(selectedVariantId)" 
                                :disabled="adding || !selectedVariantId"
                                class="px-5 sm:px-6 py-2.5 rounded-lg bg-[#CBA367] hover:bg-[#b88f55] active:scale-95 disabled:opacity-50 text-white text-xs font-bold shadow-xs cursor-pointer transition-all flex items-center gap-1.5">
                            <svg x-show="!adding" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <svg x-show="adding" x-cloak class="w-3.5 h-3.5 sm:w-4 sm:h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span x-text="adding ? 'جاري الإضافة...' : 'تأكيد الإضافة'">
                                تأكيد الإضافة
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </template>
    @endif

</div>
