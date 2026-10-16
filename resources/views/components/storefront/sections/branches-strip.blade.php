{{-- resources/views/components/storefront/sections/branches-strip.blade.php --}}
@props(['branches' => collect()])

@if($branches->isNotEmpty())
<section class="py-10 sm:py-14 bg-[#FBF6EA] border-b border-[#EADFC8]">
    <div class="container mx-auto px-4" dir="rtl">
        <!-- Section Header -->
        <div class="text-center max-w-xl mx-auto mb-8 space-y-2">
            <span class="text-xs font-bold text-[#A9552F] tracking-wide block">شبكة فروعنا وبوتيكاتنا</span>
            <h2 class="text-xl sm:text-3xl font-serif font-black text-[#2B2118]">فروع {{ settings('site_name', 'أزياء لافيستا') }} في خدمتكم</h2>
            <p class="text-xs text-[#8F7B63]">يمكنك زيارة معارضنا للمعاينة والقياس المباشر أو الطلب أونلاين مع خيار الاستلام المجاني من الفرع.</p>
        </div>

        <!-- Branches Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($branches as $branch)
            <div class="bg-[#FDFBF7] rounded-2xl border border-[#EADFC8] p-5 shadow-xs hover:shadow-md hover:border-[#CDB78F] transition-all flex flex-col justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] text-[#382416] border border-[#EADFC8] flex items-center justify-center font-bold text-lg mb-3">
                        <svg class="w-5 h-5 text-[#382416]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="font-serif font-bold text-sm text-[#2B2118] mb-1">{{ $branch->name }}</h3>
                    <p class="text-xs text-[#8F7B63] leading-relaxed mb-3">{{ $branch->address }}</p>
                </div>

                <div class="pt-3 border-t border-[#EADFC8] space-y-2 text-xs">
                    @if($branch->working_hours && is_array($branch->working_hours))
                    <div class="text-[11px] text-[#6B5642] flex items-center gap-1.5">
                        <span>⏰</span>
                        <span>{{ $branch->working_hours['from'] ?? '10:00 ص' }} - {{ $branch->working_hours['to'] ?? '11:00 م' }}</span>
                    </div>
                    @endif

                    <div class="flex items-center gap-2 pt-1">
                        @if($branch->phone)
                        <a href="tel:{{ $branch->phone }}" class="flex-1 py-1.5 px-2 rounded-xl bg-[#F3E9D2] hover:bg-[#EADFC8] text-[#382416] text-center font-bold text-[11px] border border-[#EADFC8]" dir="ltr">
                            {{ $branch->phone }}
                        </a>
                        @endif
                        @if($branch->google_maps_url)
                        <a href="{{ $branch->google_maps_url }}" target="_blank" class="p-1.5 rounded-xl bg-[#F3E9D2] text-[#382416] hover:bg-[#EADFC8] border border-[#EADFC8]" title="الاتجاهات على الخريطة">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
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
