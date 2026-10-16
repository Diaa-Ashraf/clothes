{{-- resources/views/components/storefront/layout/whatsapp-float.blade.php --}}
@php
    $isEnabled = settings('whatsapp_floating_button', '1') == '1';
    $storeWhatsApp = settings('store_whatsapp', '201000000000');
    $cleanNumber = preg_replace('/[^0-9]/', '', $storeWhatsApp);
    $defaultMessage = urlencode('مرحباً ' . settings('site_name', 'أزياء لافيستا') . '، أود الاستفسار عن الموديلات وتوافق المقاسات');
@endphp

@if($isEnabled)
<div class="fixed bottom-18 sm:bottom-20 lg:bottom-6 left-2.5 sm:left-6 z-40 flex items-center gap-2 group" dir="ltr">
    <!-- Tooltip label -->
    <a href="https://wa.me/{{ $cleanNumber }}?text={{ $defaultMessage }}" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="hidden lg:flex items-center gap-2 bg-white text-[#2B2118] shadow-xl px-3.5 py-2 rounded-full border border-[#EADFC8] text-xs font-bold opacity-90 group-hover:opacity-100 transition-all transform group-hover:-translate-x-1"
       dir="rtl">
        <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
        <span>تواصل مع منسق الأزياء</span>
    </a>

    <!-- Floating Icon Button -->
    <a href="https://wa.me/{{ $cleanNumber }}?text={{ $defaultMessage }}" 
       target="_blank" 
       rel="noopener noreferrer" 
       class="w-10 h-10 sm:w-13 sm:h-13 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-emerald-500/40 transition-all duration-300 transform group-hover:scale-110 focus:outline-none focus:ring-4 focus:ring-emerald-300"
       aria-label="تواصل معنا عبر واتساب">
        <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z" />
        </svg>
    </a>
</div>
@endif
