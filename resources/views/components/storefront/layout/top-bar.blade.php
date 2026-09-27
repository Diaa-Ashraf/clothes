{{-- resources/views/components/storefront/layout/top-bar.blade.php --}}
@php
    $storePhone = settings('store_phone', '01000000000');
    $storeWhatsApp = settings('store_whatsapp', '201000000000');
    $freeDeliveryThreshold = settings('free_delivery_threshold', 300);
    $announcementEnabled = settings('announcement_bar_enabled', '1') == '1';
    $announcementText = settings('announcement_bar_text', 'التوصيل السريع متاح لجميع مناطق العامرية وقرى مريوط والإسكندرية');
    $announcementLink = settings('announcement_bar_link', '');
    $workingHours = settings('working_hours', 'يومياً: من 8:00 صباحاً حتى 2:00 بعد منتصف الليل');
@endphp

@if($announcementEnabled)
<div class="bg-primary-900 text-white text-xs border-b border-primary-800/80 py-2 px-4 transition-colors">
    <div class="container mx-auto flex flex-wrap items-center justify-between gap-2">
        <!-- Delivery Announcement -->
        <div class="flex items-center gap-2 font-medium">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-accent-500/20 text-accent-400 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
            </span>
            
            @if(!empty($announcementLink))
            <a href="{{ $announcementLink }}" class="hover:text-accent-300 transition-colors underline decoration-accent-400/50">
                <span>{{ $announcementText }}</span>
            </a>
            @else
            <span>
                {{ $announcementText }}
            </span>
            @endif

            @if($freeDeliveryThreshold)
            <span class="hidden md:inline-block text-white/50">|</span>
            <span class="hidden md:inline-block text-white/80">
                توصيل مجاني للطلبات فوق <span class="text-accent-300 font-bold">{{ $freeDeliveryThreshold }} ج.م</span>
            </span>
            @endif
        </div>

        <!-- Contact & Working Hours -->
        <div class="flex items-center gap-4 text-white/85 text-[11px] sm:text-xs">
            <div class="hidden lg:flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ $workingHours }}</span>
            </div>

            <div class="flex items-center gap-3">
                <a href="tel:{{ $storePhone }}" class="hover:text-accent-300 transition-colors flex items-center gap-1" dir="ltr">
                    <span>{{ $storePhone }}</span>
                    <svg class="w-3.5 h-3.5 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </a>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $storeWhatsApp) }}" target="_blank" rel="noopener noreferrer" class="text-emerald-400 hover:text-emerald-300 transition-colors flex items-center gap-1">
                    <span class="hidden sm:inline">طلب واتساب</span>
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endif
