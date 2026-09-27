@props(['product'])

@php
 $displayPrice = format_currency($product->final_price, 'USD');
 $productUrl = route('product.details', $product->slug ?? $product->id);
@endphp

<div class="group relative flex flex-col justify-between h-full bg-white hover:bg-[#FAF8F5] border border-transparent hover:border-[#EADBCC] rounded-2xl p-2.5 sm:p-3 text-center transition-all duration-500 ease-out hover:-translate-y-1.5 hover:shadow-xl select-none" data-product-id="{{ $product->id }}">
 
 {{-- Product Image Container: Marketchino 1:1 ratio with gentle smooth zoom on hover --}}
 <div class="relative w-full aspect-square bg-[#F8F9FA] group-hover:bg-white rounded-2xl overflow-hidden mb-2.5 flex items-center justify-center p-1.5 border border-transparent group-hover:border-[#EADBCC]/60 transition-all duration-500">
 <a href="{{ $productUrl }}" class="block w-full h-full flex items-center justify-center overflow-hidden rounded-xl">
 <img src="{{ $product->image_url ?? asset('assets/site/img/product/product-1.jpg') }}" 
 alt="{{ $product->name }}" 
 loading="lazy" 
 class="w-full h-full object-cover rounded-xl transition-transform duration-700 ease-out group-hover:scale-110">
 </a>

 {{-- Floating Badges (Start Edge) --}}
 <div class="absolute top-2.5 start-2.5 z-10 flex flex-col gap-1 items-start pointer-events-none">
 @if($product->has_discount)
 <span class="bg-rose-600 text-white font-bold text-[10px] sm:text-xs px-2 py-0.5 rounded-full shadow-sm animate-pulse">
 -{{ $product->discount_percentage }}%
 </span>
 @endif
 @if($product->featured)
 <span class="bg-[#C5A059] text-white font-extrabold text-[9px] sm:text-[10px] px-2 py-0.5 rounded-full shadow-sm">
 {{ __('مميز') }}
 </span>
 @endif
 </div>

 {{-- Floating Wishlist Button (End Edge) --}}
 @if(settings('wishlist_enabled', true))
 @php
 $isWishlisted = auth()->check() && auth()->user()->wishlistItems()->where('product_id', $product->id)->exists();
 @endphp
 <button type="button" 
 onclick="event.stopPropagation(); event.preventDefault(); window.toggleWishlist(event, {{ $product->id }}, this);"
 class="btn-wishlist-toggle absolute top-2.5 end-2.5 z-10 w-8 h-8 rounded-full bg-white/90 border border-gray-100 hover:border-[#C5A059] {{ $isWishlisted ? 'text-rose-600' : 'text-gray-400' }} hover:text-red-500 flex items-center justify-center transition-all duration-300 shadow-sm backdrop-blur-sm hover:scale-110 active:scale-95" 
 data-id="{{ $product->id }}" 
 title="{{ $isWishlisted ? __('إزالة من المفضلة') : __('إضافة للمفضلة') }}">
 <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
 <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
 </svg>
 </button>
 @endif
 </div>

 {{-- Details Block (Minimalist & Centered) --}}
 <div class="flex flex-col flex-grow items-center justify-between px-1">
 {{-- Product Title (Exact Marketchino Typography) --}}
 <a href="{{ $productUrl }}" class="hover:text-[#C5A059] transition-colors w-full mb-1">
 <h3 class="font-sans font-medium text-xs sm:text-sm text-[#18181B] line-clamp-1 leading-normal" title="{{ $product->name }}">
 {{ $product->name }}
 </h3>
 </a>

 {{-- Price Block (Direct centered price display) --}}
 <div class="my-auto py-1">
 <div class="flex items-center justify-center gap-1.5 flex-wrap">
 <span class="font-bold text-xs sm:text-sm text-[#18181B] font-sans tabular-nums">
 {{ $displayPrice }}
 </span>
 @if($product->has_discount)
 <span class="text-[10px] text-gray-400 line-through tabular-nums">
 {{ format_currency($product->price, 'USD') }}
 </span>
 @endif
 </div>
 </div>
 </div>

 {{-- Primary CTA: Marketchino Rounded Capsule Button with Smooth Hover Color Transition --}}
 <div class="mt-2 w-full">
 @if($product->available_stock > 0)
 <button type="button" 
 onclick="event.stopPropagation(); event.preventDefault(); window.addToCart({{ $product->id }}, this);"
 class="js-add-to-cart w-full py-2.5 rounded-full bg-[#C5A059] hover:bg-[#18181B] text-white text-xs font-bold transition-colors duration-300 shadow-xs hover:shadow-md active:scale-95 flex items-center justify-center gap-1.5 cursor-pointer" 
 data-id="{{ $product->id }}">
 <span>{{ __('أضف إلى السلة') }}</span>
 </button>
 @else
 <button type="button" 
 class="w-full py-2.5 rounded-full bg-gray-100 text-gray-400 border border-gray-200 text-xs font-medium cursor-not-allowed" 
 disabled>
 {{ __('نفدت الكمية') }}
 </button>
 @endif
 </div>
</div>
