{{-- resources/views/components/storefront/sections/bundles-section.blade.php --}}
@props(['bundles' => collect()])

@if($bundles->isNotEmpty())
<section class="py-8 sm:py-12 bg-white border-b border-cream">
 <div class="container mx-auto px-4">
 <!-- Section Header -->
 <div class="flex items-center justify-between mb-6">
 <div class="flex items-center gap-2 sm:gap-3">
 <span class="text-2xl"></span>
 <div>
 <span class="text-xs font-bold text-accent-700 block">توفير حقيقي للعائلات</span>
 <h2 class="text-lg sm:text-2xl font-black text-charcoal-heading">عروض التوحيد المجمعة (Bundles)</h2>
 </div>
 </div>
 </div>

 <!-- Bundles Grid -->
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
 @foreach($bundles as $bundle)
 <div class="bg-canvas-base border border-cream rounded-2xl p-5 flex flex-col justify-between hover:shadow-card-hover transition-all">
 <div>
 <div class="flex items-start justify-between gap-3 mb-3">
 <h3 class="font-bold text-base text-charcoal-heading">{{ $bundle->name }}</h3>
 @if($bundle->discount_percent > 0)
 <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-600 text-white shrink-0">
 وفر {{ $bundle->discount_percent }}%
 </span>
 @endif
 </div>
 <p class="text-xs text-charcoal-muted mb-4 line-clamp-2">{{ $bundle->description }}</p>

 <!-- Items in Bundle -->
 @if($bundle->products->isNotEmpty())
 <div class="space-y-1.5 mb-4 p-3 rounded-xl bg-white border border-cream text-xs">
 <span class="block text-[11px] font-bold text-primary-900 mb-1">يحتوي هذا العرض على:</span>
 @foreach($bundle->products as $bp)
 <div class="flex items-center justify-between text-[#71717A] text-[11px]">
 <span>• {{ $bp->name }}</span>
 <span class="font-bold">×{{ $bp->pivot->quantity ?? 1 }}</span>
 </div>
 @endforeach
 </div>
 @endif
 </div>

 <!-- Price & Action -->
 <div class="pt-3 border-t border-cream flex items-center justify-between">
 <div>
 <span class="text-base font-black text-primary-800 tabular-nums">
 {{ format_currency($bundle->bundle_price) }}
 </span>
 @if($bundle->original_total > $bundle->bundle_price)
 <span class="text-xs text-charcoal-muted line-through tabular-nums mr-1">
 {{ format_currency($bundle->original_total) }}
 </span>
 @endif
 </div>

 <form action="{{ route('bundle.add-to-cart') }}" method="POST" 
 onsubmit="event.preventDefault(); 
 fetch(this.action, {
 method: 'POST',
 headers: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json'},
 body: new FormData(this)
 })
 .then(res => res.json())
 .then(data => {
 toastr.success(data.message || 'تمت إضافة العرض المجمع للسلة');
 window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count } }));
 })
 .catch(() => toastr.error('حدث خطأ'));">
 @csrf
 <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
 <button type="submit" class="px-4 py-2 rounded-xl bg-primary-800 hover:bg-primary-900 text-white font-bold text-xs transition-colors shadow-xs">
 أضف العرض 
 </button>
 </form>
 </div>
 </div>
 @endforeach
 </div>
 </div>
</section>
@endif
