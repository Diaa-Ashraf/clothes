@props(['bundle'])

@php
$displayPrice = format_currency($bundle->bundle_price, 'USD');
$usdPrice = format_currency($bundle->bundle_price, 'USD');
$discountPercent = (int)$bundle->discount_percent;
$hasSavings = (float)$bundle->original_total > (float)$bundle->bundle_price;
$savingsAmount = max(0, (float)$bundle->original_total - (float)$bundle->bundle_price);

// Build WhatsApp Payload for Bundles
$storePhone = preg_replace('/[^0-9]/', '', settings('whatsapp_number', '963999999999'));
$waText = "طلب عرض مجمع من Regalest Store \n"
. "▪ العرض: " . $bundle->name . "\n"
. "▪ السعر الإجمالي: " . $usdPrice . "\n"
. "▪ يحتوي على: " . $bundle->products->pluck('name')->implode(' + ');
$waUrl = "https://wa.me/{$storePhone}?text=" . urlencode($waText);
@endphp

{{-- Compact Grid E-Commerce Bundle Card (Perfect for 2 Cards per Row) --}}
<div class="group relative bg-white rounded-3xl border-2 border-[#EADBCC]/90 hover:border-[#C5A059] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between h-full">

 {{-- Card Header: Title, Badge & Savings --}}
 <div class="bg-gradient-to-r from-[#18181B] via-[#27272A] to-[#18181B] text-white p-4 sm:p-5 flex items-center justify-between gap-2 border-b border-[#C5A059]/30">
 <div class="flex items-center gap-2 min-w-0">
 <span class="w-6 h-6 rounded-full bg-[#C5A059] text-white flex items-center justify-center text-xs font-black shrink-0"></span>
 <div class="min-w-0">
 <h3 class="font-bold text-sm sm:text-base text-white tracking-wide truncate">{{ $bundle->name }}</h3>
 <span class="text-[10px] sm:text-[11px] text-gray-300 block truncate">{{ $bundle->description ?? __('عرض خاص يجمع أرقى المقتنيات بسعر مميز') }}</span>
 </div>
 </div>

 <div class="flex items-center gap-1.5 shrink-0">
 @if($discountPercent > 0)
 <span class="bg-rose-600 text-white text-[11px] font-black px-2.5 py-0.5 rounded-full shadow-xs">
 -{{ $discountPercent }}%
 </span>
 @endif
 </div>
 </div>

 {{-- Product Combination Gallery --}}
 <div class="p-4 sm:p-5 bg-[#FAF8F5]/60 flex-1 flex flex-col justify-center">
 <div class="flex items-center justify-center gap-2 sm:gap-3 flex-wrap">
 @foreach($bundle->products as $index => $product)
 {{-- Single Product Thumbnail Card --}}
 <div class="flex-1 min-w-[100px] max-w-[140px] bg-white rounded-2xl p-2.5 border border-gray-200/90 shadow-2xs hover:shadow-sm transition-all text-center flex flex-col items-center">
 <div class="w-full aspect-square rounded-xl bg-[#F8F9FA] p-1.5 flex items-center justify-center overflow-hidden mb-1.5 border border-gray-100">
 <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}"
 alt="{{ $product->name }}"
 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
 </div>
 <span class="text-[11px] font-bold text-[#18181B] line-clamp-1 w-full" title="{{ $product->name }}">
 {{ $product->name }}
 </span>
 <span class="text-[10px] text-gray-400 font-medium tabular-nums mt-0.5">
 {{ format_currency($product->price, 'USD') }}
 </span>
 </div>

 @if(!$loop->last)
 <div class="shrink-0 w-6 h-6 rounded-full bg-white border border-gray-200 shadow-2xs flex items-center justify-center text-[#C5A059] font-black text-xs select-none">
 +
 </div>
 @endif
 @endforeach
 </div>

 @if($hasSavings)
 <div class="mt-3 text-center">
 <span class="inline-block px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold">
 {{ __('توفير إجمالي:') }} {{ format_currency($savingsAmount, 'USD') }}
 </span>
 </div>
 @endif
 </div>

 {{-- Pricing & CTA Bottom Footer --}}
 <div class="p-4 sm:p-5 bg-white border-t border-gray-100 space-y-3">
 <div class="flex items-baseline justify-between gap-2">
 <div>
 <span class="text-[10px] text-gray-400 block font-medium">{{ __('سعر الباقة الإجمالي:') }}</span>
 <div class="flex items-baseline gap-2">
 <span class="text-xl sm:text-2xl font-black text-[#18181B] tabular-nums font-sans">
 {{ $displayPrice }}
 </span>
 @if($hasSavings)
 <span class="text-xs text-gray-400 line-through tabular-nums">
 {{ format_currency($bundle->original_total, 'USD') }}
 </span>
 @endif
 </div>
 </div>
 </div>

 {{-- Action Buttons --}}
 <div class="flex items-center gap-2">
 <button type="button"
 onclick="event.stopPropagation(); event.preventDefault(); window.addBundleToCart({{ $bundle->id }}, this);"
 class="flex-1 js-add-bundle-to-cart py-2.5 px-3 rounded-full bg-[#C5A059] hover:bg-[#18181B] text-white font-bold text-xs shadow-xs hover:shadow-md transition-all duration-300 flex items-center justify-center gap-1.5 active:scale-95 cursor-pointer"
 data-bundle-id="{{ $bundle->id }}">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
 </svg>
 <span>{{ __('أضف الباقة للسلة') }}</span>
 </button>

 <a href="{{ $waUrl }}"
 target="_blank"
 @click.stop
 class="w-9 h-9 shrink-0 flex items-center justify-center bg-emerald-50 hover:bg-[#25D366] text-[#25D366] hover:text-white border border-emerald-200 rounded-full transition-all duration-300 shadow-2xs hover:scale-105"
 title="{{ __('طلب عبر واتساب') }}">
 <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
 <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
 </svg>
 </a>
 </div>
 </div>

</div>