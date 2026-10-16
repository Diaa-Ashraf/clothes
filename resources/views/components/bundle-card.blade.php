@props(['bundle'])

@php
$displayPrice = format_currency($bundle->bundle_price);
$discountPercent = (int)$bundle->discount_percent;
$hasSavings = (float)$bundle->original_total > (float)$bundle->bundle_price;
$savingsAmount = max(0, (float)$bundle->original_total - (float)$bundle->bundle_price);

// Build WhatsApp Payload for Bundles
$storePhone = preg_replace('/[^0-9]/', '', settings('whatsapp_number', '01000000000'));
$waText = "طلب طقم وتنسيقة أزياء كاملة من " . settings('site_name', 'أزياء لافيستا') . " \n"
. "▪ الطقم: " . $bundle->name . "\n"
. "▪ السعر الإجمالي: " . $displayPrice . "\n"
. "▪ يتضمن: " . $bundle->products->pluck('name')->implode(' + ');
$waUrl = "https://wa.me/{$storePhone}?text=" . urlencode($waText);
@endphp

<div class="group relative bg-white rounded-3xl border border-[#EADFC8] hover:border-[#CDB78F] shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col justify-between h-full p-5">

    <div class="space-y-4">
        {{-- 1. Bundle Visual / Main Photo --}}
        @if($bundle->image_url)
            <div class="relative w-full h-44 sm:h-48 rounded-2xl overflow-hidden bg-[#FBF6EA]/50 border border-[#EADFC8] shadow-inner group-hover:scale-[1.02] transition-transform duration-300">
                <img src="{{ $bundle->image_url }}" 
                     alt="{{ $bundle->name }}" 
                     class="w-full h-full object-cover object-center">
                
                @if($discountPercent > 0)
                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-black bg-[#A9552F] text-white shadow-md">
                        وفر {{ $discountPercent }}%
                    </span>
                @endif
            </div>
        @else
            {{-- Multi-product Collage --}}
            <div class="relative w-full rounded-2xl bg-[#FBF6EA]/50 border border-[#EADFC8] p-3 shadow-inner">
                <div class="flex items-center justify-center gap-2 flex-wrap py-2">
                    @foreach($bundle->products->take(4) as $product)
                        <div class="w-14 h-14 rounded-xl bg-white p-1 border border-[#EADFC8] shadow-2xs flex items-center justify-center overflow-hidden">
                            <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover rounded-lg">
                        </div>
                        @if(!$loop->last)
                            <span class="text-[#8F7B63] font-black text-sm select-none">+</span>
                        @endif
                    @endforeach
                </div>
                @if($discountPercent > 0)
                    <span class="absolute top-2 right-2 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-[#A9552F] text-white shadow-sm">
                        وفر {{ $discountPercent }}%
                    </span>
                @endif
            </div>
        @endif

        {{-- 2. Title & Description --}}
        <div>
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-black text-base sm:text-lg text-[#2B2118] group-hover:text-[#A9552F] transition-colors leading-snug">
                    {{ $bundle->name }}
                </h3>
                @if(!$bundle->image_url && $discountPercent > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-[#A9552F] text-white shrink-0">
                        وفر {{ $discountPercent }}%
                    </span>
                @endif
            </div>
            @if($bundle->description)
                <p class="text-xs text-[#8F7B63] font-medium mt-1.5 line-clamp-2 leading-relaxed">{{ $bundle->description }}</p>
            @endif
        </div>

        {{-- 3. Items inside Bundle --}}
        @if($bundle->products->isNotEmpty())
        <div class="p-3.5 rounded-2xl bg-[#FBF6EA]/60 border border-[#EADFC8] space-y-2">
            <span class="block text-[11px] font-black text-[#2B2118] flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#A9552F]"></span>
                <span>قطع وتفاصيل هذا الطقم:</span>
            </span>
            <div class="space-y-1.5 divide-y divide-[#EADFC8]/60">
                @foreach($bundle->products as $bp)
                <div class="flex items-center justify-between text-xs pt-1.5 first:pt-0">
                    <div class="flex items-center gap-2 min-w-0">
                        @if($bp->image_url)
                            <img src="{{ $bp->image_url }}" alt="" class="w-5 h-5 rounded-md object-cover border border-[#EADFC8] shrink-0">
                        @endif
                        <span class="text-[#2B2118] font-bold truncate max-w-[180px]">{{ $bp->name }}</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-md bg-[#F3E9D2] border border-[#EADFC8] font-black text-[11px] text-[#6B5642] shrink-0 shadow-2xs">
                        {{ $bp->pivot->quantity ?? 1 }}x
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- 4. Pricing & Action --}}
    <div class="pt-4 mt-4 border-t border-[#EADFC8] flex items-center justify-between gap-3">
        <div>
            <span class="text-[10px] text-[#8F7B63] font-bold block">سعر الطقم كاملاً:</span>
            <div class="flex items-baseline gap-2">
                <span class="text-lg sm:text-xl font-black text-[#A9552F] tabular-nums">
                    {{ $displayPrice }}
                </span>
                @if($hasSavings)
                <span class="text-xs text-[#8F7B63] line-through tabular-nums">
                    {{ format_currency($bundle->original_total) }}
                </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="event.stopPropagation(); event.preventDefault(); window.addBundleToCart ? window.addBundleToCart({{ $bundle->id }}, this) : fetch('{{ route('bundle.add-to-cart') }}', {method: 'POST', headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json'}, body: JSON.stringify({bundle_id: {{ $bundle->id }}})}).then(r=>r.json()).then(d=>{toastr.success(d.message); window.dispatchEvent(new CustomEvent('cart-updated', {detail:{count:d.cart_count}}));});"
                    class="px-4 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] font-bold text-xs shadow-md active:scale-95 transition-all cursor-pointer flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-[#FBF6EA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>أضف الطقم</span>
            </button>

            <a href="{{ $waUrl }}" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white hover:bg-[#25D366] text-[#25D366] hover:text-white border border-[#EADFC8] transition-all shadow-2xs" title="طلب بالواتساب">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
                </svg>
            </a>
        </div>
    </div>
</div>