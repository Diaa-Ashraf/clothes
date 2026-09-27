{{-- resources/views/components/storefront/ui/branch-card.blade.php --}}
@props(['branch'])

@php
 $deliveryZones = is_array($branch->delivery_zones) ? $branch->delivery_zones : (json_decode($branch->delivery_zones ?? '[]', true) ?? []);
 $formattedOpening = $branch->opening_time ? date('g:i A', strtotime($branch->opening_time)) : ($branch->working_hours['opening'] ?? '8:00 AM');
 $formattedClosing = $branch->closing_time ? date('g:i A', strtotime($branch->closing_time)) : ($branch->working_hours['closing'] ?? '12:00 AM');
 $cleanPhone = preg_replace('/[^0-9+]/', '', $branch->phone ?? '');
 $rawWhatsapp = $branch->whatsapp ?? $branch->whatsapp_phone ?? $branch->phone ?? '';
 $cleanWhatsapp = preg_replace('/[^0-9]/', '', $rawWhatsapp);
 if ($cleanWhatsapp && !str_starts_with($cleanWhatsapp, '20') && !str_starts_with($cleanWhatsapp, '+20')) {
 $cleanWhatsapp = '20' . ltrim($cleanWhatsapp, '0');
 }
@endphp

<div class="bg-white rounded-3xl border border-cream hover:border-primary-400 p-6 shadow-sm hover:shadow-card-hover transition-all duration-300 flex flex-col justify-between space-y-6 group">
 
 <!-- Header: Name & Status -->
 <div class="space-y-3">
 <div class="flex items-start justify-between gap-3">
 <div class="flex items-center gap-3">
 <div class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-800 border border-primary-100 flex items-center justify-center text-xl font-bold group-hover:scale-105 transition-transform shadow-inner">
 
 </div>
 <div>
 <h3 class="text-base sm:text-lg font-black text-charcoal-heading group-hover:text-primary-800 transition-colors">
 {{ $branch->name }}
 </h3>
 <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 font-semibold mt-0.5">
 <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
 مفتوح الآن لاستقبالكم
 </span>
 </div>
 </div>

 <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-primary-50 text-primary-900 border border-primary-200">
 العامرية
 </span>
 </div>

 <!-- Address -->
 <div class="flex items-start gap-2.5 pt-2 text-xs text-charcoal-muted leading-relaxed">
 <span class="text-primary-700 text-sm mt-0.5"></span>
 <span>{{ $branch->address }}</span>
 </div>

 <!-- Working Hours -->
 <div class="flex items-center gap-2.5 text-xs text-charcoal-muted">
 <span class="text-amber-600 text-sm">⏰</span>
 <span>مواعيد العمل: يومياً من {{ $formattedOpening }} حتى {{ $formattedClosing }}</span>
 </div>

 <!-- Delivery Zones Pills -->
 @if(!empty($deliveryZones))
 <div class="pt-2 space-y-1.5 border-t border-gray-100">
 <span class="text-[11px] font-bold text-charcoal-heading block">المناطق المخدومة للتوصيل:</span>
 <div class="flex flex-wrap gap-1.5">
 @foreach($deliveryZones as $zone)
 <span class="inline-block px-2 py-0.5 rounded-lg bg-gray-100 text-charcoal text-[10px] font-medium border border-gray-200">
 {{ $zone }}
 </span>
 @endforeach
 </div>
 </div>
 @endif
 </div>

 <!-- Actions (Call / WhatsApp / Maps) -->
 <div class="pt-4 border-t border-cream flex flex-wrap items-center gap-2">
 @if($branch->phone)
 <a href="tel:{{ $cleanPhone }}" 
 class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl bg-gray-100 hover:bg-gray-200 text-charcoal text-xs font-bold text-center transition-colors flex items-center justify-center gap-1.5">
 <span></span>
 <span>اتصال هاتفياً</span>
 </a>
 @endif

 @if(!empty($rawWhatsapp))
 <a href="https://wa.me/{{ $cleanWhatsapp }}?text={{ urlencode('السلام عليكم، بتواصل بخصوص الطلب من فرع ' . $branch->name) }}" 
 target="_blank" 
 rel="noopener noreferrer"
 class="flex-1 min-w-[120px] py-2.5 px-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold text-center transition-all shadow-xs flex items-center justify-center gap-1.5">
 <span></span>
 <span>واتساب الفرع</span>
 </a>
 @endif

 @if($branch->google_maps_url)
 <a href="{{ $branch->google_maps_url }}" 
 target="_blank" 
 rel="noopener noreferrer"
 class="w-10 h-10 rounded-xl bg-primary-50 hover:bg-primary-100 text-primary-800 border border-primary-200 flex items-center justify-center text-sm transition-colors"
 title="موقع الفرع على الخريطة">
 
 </a>
 @endif
 </div>

</div>
