<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>@yield('title', settings('meta_title', settings('site_name', 'أزياء لافيستا') . ' | أرقى الملابس والتنسيقات'))</title>
 <meta name="description" content="{{ settings('meta_description', 'أزياء لافيستا — وجهتك الأولى لأرقى الملابس الجاهزة النسائية والرجالية والأطفال في مصر.') }}">
 <meta name="keywords" content="{{ settings('meta_keywords', 'أزياء لافيستا, ملابس رجالي, ملابس حريمي, أزياء أطفال, فساتين, بدل, قطن مصري') }}">
 <meta property="og:title" content="{{ settings('meta_title', settings('site_name', 'أزياء لافيستا')) }}">
 <meta property="og:description" content="{{ settings('meta_description', 'أزياء لافيستا') }}">
 <meta property="og:type" content="website">
 @if(get_og_image())
 <meta property="og:image" content="{{ get_og_image() }}">
 @elseif(get_site_logo())
 <meta property="og:image" content="{{ get_site_logo() }}">
 @endif

 @php
 $siteFavicon = get_site_favicon();
 @endphp
 @if($siteFavicon)
 <!-- Browser Tab Icon (Favicon) -->
 <link rel="icon" href="{{ $siteFavicon }}">
 <link rel="shortcut icon" href="{{ $siteFavicon }}">
 <link rel="apple-touch-icon" href="{{ $siteFavicon }}">
 @else
 <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22></text></svg>">
 @endif

 <!-- Fonts (Cinzel, Plus Jakarta Sans, Readex Pro, Alexandria) -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=Cinzel:wght@500;700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Readex+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

 <!-- Swiper & Toastr CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">



 <!-- Vite Assets (Tailwind & App Scripts) -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])

 <style>
 [x-cloak] {
 display: none !important;
 }

 svg {
 max-width: 100%;
 height: auto;
 vertical-align: middle;
 }

 .swiper {
 width: 100%;
 height: 100%;
 }
 </style>

 @stack('styles')
</head>

<body class="bg-[#F8F9FA] text-[#18181B] font-sans antialiased min-h-screen flex flex-col overflow-x-clip"
 x-data="{ showBackToTop: false }"
 @scroll.window="showBackToTop = (window.pageYOffset > 300)">

 @include('layouts.site-sections.header')

 <main class="flex-1 w-full">
 @yield('content')
 </main>

 @include('layouts.site-sections.footer')

 {{-- =========================================================================
 DUAL FLOATING TRIGGERS SYSTEM
 ========================================================================= --}}
 {{-- 1. Floating WhatsApp VIP Trigger (bottom-end: end-6 bottom-6) --}}
 @php
 $storePhone = preg_replace('/[^0-9]/', '', settings('whatsapp_number', '963999999999'));
 $waFloatingUrl = "https://wa.me/{$storePhone}?text=" . urlencode("مرحباً، أود الاستفسار والتواصل مع إدارة ريجاليست ستور ");
 @endphp
 <a href="{{ $waFloatingUrl }}"
 target="_blank"
 rel="noopener noreferrer"
 class="fixed end-6 bottom-6 z-40 w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-[#25D366] text-white shadow-xl hover:shadow-2xl flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 animate-pulse-subtle group"
 title="{{ __('تواصل عبر واتساب VIP') }}">
 <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
 <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z" />
 </svg>
 </a>

 {{-- 2. Floating Back-to-Top Button (bottom-start: start-6 bottom-6) --}}
 <button type="button"
 x-show="showBackToTop"
 x-transition:enter="transition ease-out duration-300"
 x-transition:enter-start="opacity-0 translate-y-4"
 x-transition:enter-end="opacity-100 translate-y-0"
 x-transition:leave="transition ease-in duration-200"
 x-transition:leave-start="opacity-100 translate-y-0"
 x-transition:leave-end="opacity-0 translate-y-4"
 @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
 class="fixed start-6 bottom-6 z-40 w-11 h-11 rounded-full bg-white border border-[#E5E7EB] hover:border-[#C5A059] text-[#18181B] hover:text-[#C5A059] shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-105 active:scale-95"
 style="display: none;"
 title="{{ __('إلى الأعلى') }}">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
 </svg>
 </button>

 <!-- Scripts: jQuery, Toastr, Swiper -->
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

 <script>
 $.ajaxSetup({
 headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 }
 });

 toastr.options = {
 "closeButton": true,
 "progressBar": true,
 "positionClass": "{{ app()->getLocale() == 'ar' ? 'toast-bottom-left' : 'toast-bottom-right' }}",
 "timeOut": "3000"
 };

 @if(session('success'))
 toastr.success("{{ session('success') }}");
 @endif
 @if(session('error'))
 toastr.error("{{ session('error') }}");
 @endif

 // Global Add To Cart with Alpine.js Mini-Cart Sync
 window.addToCart = function(productId, btnElement) {
 const btn = btnElement ? $(btnElement) : $(`.js-add-to-cart[data-id="${productId}"]`);
 const originalContent = btn.html();

 btn.prop('disabled', true).html('⏳...');

 $.post("{{ route('AddToCart', ':id') }}".replace(':id', productId), {
 quantity: 1
 }).done(function(res) {
 if (res.success) {
 toastr.success(res.message);
 // Fetch full fresh payload to notify Alpine mini-cart
 fetch('{{ route('cart.payload') }}')
 .then(r => r.json())
 .then(payload => {
 window.dispatchEvent(new CustomEvent('cart-updated', {
 detail: payload
 }));
 window.dispatchEvent(new CustomEvent('open-mini-cart'));
 })
 .catch(() => {});
 } else {
 toastr.error(res.message);
 }
 }).fail(function(xhr) {
 const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "{{ __('حدث خطأ أثناء إضافة المنتج.') }}";
 toastr.error(msg);
 }).always(function() {
 btn.prop('disabled', false).html(originalContent);
 });
 };

 // Backward compatibility
 window.quickAddToCart = function(productId) {
 window.addToCart(productId, null);
 };

 // Global Add Bundle To Cart with Alpine.js Mini-Cart Sync
 window.addBundleToCart = function(bundleId, btnElement) {
 const btn = btnElement ? $(btnElement) : $(`.js-add-bundle-to-cart[data-bundle-id="${bundleId}"]`);
 const originalContent = btn.html();

 btn.prop('disabled', true).html('⏳...');

 $.post("{{ route('bundle.add-to-cart') }}", {
 bundle_id: bundleId
 }).done(function(res) {
 if (res.success) {
 toastr.success(res.message);
 fetch('{{ route('cart.payload') }}')
 .then(r => r.json())
 .then(payload => {
 window.dispatchEvent(new CustomEvent('cart-updated', {
 detail: payload
 }));
 window.dispatchEvent(new CustomEvent('open-mini-cart'));
 })
 .catch(() => {});
 } else {
 toastr.error(res.message);
 }
 }).fail(function(xhr) {
 const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : "{{ __('حدث خطأ أثناء إضافة البكج.') }}";
 toastr.error(msg);
 }).always(function() {
 btn.prop('disabled', false).html(originalContent);
 });
 };

 // jQuery delegation fallback
 $(document).on('click', '.js-add-to-cart', function(e) {
 e.preventDefault();
 e.stopPropagation();
 const productId = $(this).data('id');
 window.addToCart(productId, this);
 });

 $(document).on('click', '.js-add-bundle-to-cart', function(e) {
 e.preventDefault();
 e.stopPropagation();
 const bundleId = $(this).data('bundle-id');
 window.addBundleToCart(bundleId, this);
 });

 // Global Wishlist Toggle Function
 window.toggleWishlist = function(e, productId, btnElement) {
 if (e) {
 e.preventDefault();
 e.stopPropagation();
 }
 const btn = btnElement ? $(btnElement) : $(`.btn-wishlist-toggle[data-id="${productId}"]`);
 const originalTransform = btn.css('transform');

 btn.css('transform', 'scale(1.25)');

 $.post("{{ route('wishlist.toggle', ':id') }}".replace(':id', productId))
 .done(function(res) {
 if (res.success) {
 toastr.success(res.message);
 if (res.in_wishlist) {
 $(`.btn-wishlist-toggle[data-id="${productId}"]`)
 .addClass('text-rose-600')
 .removeClass('text-gray-400')
 .attr('title', "{{ __('إزالة من المفضلة') }}");
 } else {
 $(`.btn-wishlist-toggle[data-id="${productId}"]`)
 .removeClass('text-rose-600')
 .addClass('text-gray-400')
 .attr('title', "{{ __('إضافة للمفضلة') }}");
 }
 // Dispatch global event for Alpine header badge sync
 window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: res.wishlist_count }));
 } else {
 toastr.error(res.message);
 }
 })
 .fail(function(xhr) {
 if (xhr.status === 401) {
 toastr.info("{{ __('يرجى تسجيل الدخول لحفظ المنتج في المفضلة.') }}");
 } else {
 toastr.error("{{ __('تعذر تحديث قائمة المفضلة.') }}");
 }
 })
 .always(function() {
 setTimeout(() => {
 btn.css('transform', originalTransform || '');
 }, 200);
 });
 };

 // Backward compatibility for old calls
 window.addToWishlist = function(productId) {
 window.toggleWishlist(null, productId, null);
 };

 // jQuery delegation fallback
 $(document).on('click', '.btn-wishlist-toggle', function(e) {
 const productId = $(this).data('id');
 window.toggleWishlist(e, productId, this);
 });

 // Luxury Confirm & Alert System (Replaces ugly browser alerts/confirms)
 window.showLuxuryConfirm = function({
 title = "{{ __('تأكيد الإجراء') }}",
 message = "{{ __('هل أنت متأكد من متابعة هذا الإجراء؟') }}",
 confirmText = "{{ __('تأكيد') }}",
 cancelText = "{{ __('إلغاء') }}",
 icon = "",
 iconBg = "bg-amber-50 text-amber-600 border-amber-200",
 confirmBtnClass = "bg-[#18181B] hover:bg-[#C5A059] text-white",
 onConfirm = () => {},
 onCancel = () => {}
 } = {}) {
 const modal = document.getElementById('luxuryConfirmModal');
 const card = document.getElementById('luxuryConfirmCard');
 const iconEl = document.getElementById('luxuryModalIcon');
 const titleEl = document.getElementById('luxuryModalTitle');
 const descEl = document.getElementById('luxuryModalDesc');
 const confirmBtn = document.getElementById('luxuryModalConfirmBtn');
 const cancelBtn = document.getElementById('luxuryModalCancelBtn');

 if (!modal) return;

 iconEl.textContent = icon;
 iconEl.className = `w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-3 border ${iconBg}`;
 titleEl.textContent = title;
 descEl.textContent = message;
 confirmBtn.textContent = confirmText;
 confirmBtn.className = `flex-1 py-3 px-5 rounded-full text-xs font-bold transition-all shadow-sm cursor-pointer ${confirmBtnClass}`;
 cancelBtn.textContent = cancelText;

 // Reset event listeners
 const newConfirmBtn = confirmBtn.cloneNode(true);
 confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

 const newCancelBtn = cancelBtn.cloneNode(true);
 cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

 const closeModal = () => {
 modal.classList.add('opacity-0', 'pointer-events-none');
 card.classList.remove('scale-100');
 card.classList.add('scale-95');
 };

 newConfirmBtn.addEventListener('click', () => {
 closeModal();
 if (typeof onConfirm === 'function') onConfirm();
 });

 newCancelBtn.addEventListener('click', () => {
 closeModal();
 if (typeof onCancel === 'function') onCancel();
 });

 modal.onclick = (e) => {
 if (e.target === modal) {
 closeModal();
 if (typeof onCancel === 'function') onCancel();
 }
 };

 modal.classList.remove('opacity-0', 'pointer-events-none');
 card.classList.remove('scale-95');
 card.classList.add('scale-100');
 };
 </script>

 {{-- Luxury Custom Modal Popup (Replaces browser "says" alert & confirm) --}}
 <div id="luxuryConfirmModal" 
 class="fixed inset-0 z-[99999] bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300"
 dir="rtl">
 <div id="luxuryConfirmCard" 
 class="bg-white rounded-3xl max-w-sm w-full p-6 sm:p-7 text-center shadow-2xl border border-[#EADBCC] transform scale-95 transition-all duration-300 space-y-4">
 
 <div id="luxuryModalIcon" class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center text-2xl mx-auto shadow-inner">
 
 </div>

 <div class="space-y-1.5">
 <h3 id="luxuryModalTitle" class="text-base font-bold text-[#18181B]">
 {{ __('تأكيد الإجراء') }}
 </h3>
 <p id="luxuryModalDesc" class="text-xs text-[#71717A] leading-relaxed">
 {{ __('هل أنت متأكد من المتابعة؟') }}
 </p>
 </div>

 <div class="flex items-center gap-3 pt-2">
 <button type="button" 
 id="luxuryModalCancelBtn"
 class="flex-1 py-3 px-5 rounded-full bg-gray-100 hover:bg-gray-200 text-[#18181B] text-xs font-bold transition-colors cursor-pointer">
 {{ __('إلغاء') }}
 </button>
 <button type="button" 
 id="luxuryModalConfirmBtn"
 class="flex-1 py-3 px-5 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
 {{ __('تأكيد') }}
 </button>
 </div>
 </div>
 </div>

 @stack('scripts')
</body>

</html>