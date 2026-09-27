{{-- resources/views/components/storefront/sections/branches-strip.blade.php --}}
@props(['branches' => collect()])

@if($branches->isNotEmpty())
<section class="py-10 sm:py-14 bg-canvas-base border-b border-cream">
 <div class="container mx-auto px-4">
 <!-- Section Header -->
 <div class="text-center max-w-xl mx-auto mb-8 space-y-2">
 <span class="text-xs font-bold text-accent-700 uppercase tracking-wider block">فروعنا بالعامرية والإسكندرية</span>
 <h2 class="text-xl sm:text-3xl font-black text-charcoal-heading">4 فروع في خدمتك يومياً</h2>
 <p class="text-xs text-charcoal-muted">نوفر لك إمكانية الشراء المباشر أو الطلب أونلاين والاستلام مجاناً من أقرب فرع لك.</p>
 </div>

 <!-- Branches Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
 @foreach($branches as $branch)
 <div class="bg-white rounded-2xl border border-cream p-5 shadow-sm hover:shadow-card-hover hover:border-primary-400 transition-all flex flex-col justify-between">
 <div>
 <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-800 border border-primary-200 flex items-center justify-center font-bold text-lg mb-3">
 
 </div>
 <h3 class="font-bold text-sm text-charcoal-heading mb-1">{{ $branch->name }}</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed mb-3"> {{ $branch->address }}</p>
 </div>

 <div class="pt-3 border-t border-cream space-y-2 text-xs">
 @if($branch->working_hours && is_array($branch->working_hours))
 <div class="text-[11px] text-charcoal-secondary flex items-center gap-1.5">
 <span>⏰</span>
 <span>{{ $branch->working_hours['from'] ?? '08:00 ص' }} - {{ $branch->working_hours['to'] ?? '02:00 ص' }}</span>
 </div>
 @endif

 <div class="flex items-center gap-2 pt-1">
 @if($branch->phone)
 <a href="tel:{{ $branch->phone }}" class="flex-1 py-1.5 px-2 rounded-lg bg-canvas-base hover:bg-canvas-subtle text-charcoal text-center font-bold text-[11px] border border-cream" dir="ltr">
 {{ $branch->phone }}
 </a>
 @endif
 @if($branch->google_maps_url)
 <a href="{{ $branch->google_maps_url }}" target="_blank" class="p-1.5 rounded-lg bg-primary-50 text-primary-800 hover:bg-primary-100 border border-primary-200" title="الاتجاهات">
 
 </a>
 @endif
 </div>
 </div>
 </div>
 @endforeach
 </div>
 </div>
</section>
@endif
