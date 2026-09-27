{{-- resources/views/components/storefront/sections/deals-section.blade.php --}}
@props(['deals' => collect()])

@if($deals->isNotEmpty())
<section class="py-8 sm:py-12 bg-primary-50/40 border-b border-cream">
 <div class="container mx-auto px-4">
 <!-- Section Header -->
 <div class="flex items-center justify-between mb-6">
 <div class="flex items-center gap-2 sm:gap-3">
 <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
 
 </div>
 <div>
 <span class="text-xs font-bold text-rose-600 block">عروض التوفير الكبرى</span>
 <h2 class="text-lg sm:text-2xl font-black text-charcoal-heading">عروض اليوم بأسعار خاصة</h2>
 </div>
 </div>
 <a href="{{ route('product.shop') }}" class="text-xs font-bold text-primary-800 hover:text-rose-600 transition-colors flex items-center gap-1">
 <span>كل العروض</span>
 <span>←</span>
 </a>
 </div>

 <!-- Deals Grid -->
 <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-5">
 @foreach($deals as $deal)
 @if($deal->product)
 <x-storefront.ui.product-card :product="$deal->product" />
 @endif
 @endforeach
 </div>
 </div>
</section>
@endif
