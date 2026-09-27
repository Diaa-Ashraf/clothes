{{-- resources/views/layouts/storefront.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <meta name="csrf-token" content="{{ csrf_token() }}">

 <title>@yield('title', settings('site_name', 'Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯')) | Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ© - Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©</title>
 <meta name="description" content="@yield('meta_description', 'Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯ ÙÙŠ Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©ØŒ Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©. Ø£Ø¬ÙˆØ¯ Ù…Ù†ØªØ¬Ø§Øª Ø§Ù„Ø£Ù„Ø¨Ø§Ù† ÙˆØ§Ù„Ø£Ø¬Ø¨Ø§Ù† Ø§Ù„Ø·Ø¨ÙŠØ¹ÙŠØ© ÙˆØ§Ù„Ø³Ù„Ø¹ Ø§Ù„ØºØ°Ø§Ø¦ÙŠØ© ÙˆØ®Ø¯Ù…Ø© ØªÙˆØµÙŠÙ„ ÙÙˆØ±ÙŠØ©.')">
 <meta name="keywords" content="@yield('meta_keywords', 'Ø£Ù„Ø¨Ø§Ù† Ø§Ù„ØªÙˆØ­ÙŠØ¯, Ø³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯, Ø¬Ø¨Ù† Ø¨Ù„Ø¯ÙŠ, Ø£Ù„Ø¨Ø§Ù† Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©, ØªÙˆØµÙŠÙ„ Ø·Ù„Ø¨Ø§Øª Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©, Ø³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©, ÙƒÙŠÙ†Ø¬ Ù…Ø±ÙŠÙˆØ·')">
 <link rel="canonical" href="{{ url()->current() }}" />

 <!-- Open Graph / Facebook -->
 <meta property="og:type" content="website" />
 <meta property="og:locale" content="ar_EG" />
 <meta property="og:site_name" content="Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯" />
 <meta property="og:url" content="{{ url()->current() }}" />
 <meta property="og:title" content="@yield('title', settings('site_name', 'Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯')) | Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ© - Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©" />
 <meta property="og:description" content="@yield('meta_description', 'Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯ ÙÙŠ Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©ØŒ Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©. Ø£Ø¬ÙˆØ¯ Ù…Ù†ØªØ¬Ø§Øª Ø§Ù„Ø£Ù„Ø¨Ø§Ù† ÙˆØ§Ù„Ø£Ø¬Ø¨Ø§Ù† Ø§Ù„Ø·Ø¨ÙŠØ¹ÙŠØ© ÙˆØ§Ù„Ø³Ù„Ø¹ Ø§Ù„ØºØ°Ø§Ø¦ÙŠØ© ÙˆØ®Ø¯Ù…Ø© ØªÙˆØµÙŠÙ„ ÙÙˆØ±ÙŠØ©.')" />
 <meta property="og:image" content="@yield('og_image', asset('assets/site/img/og-cover.png'))" />

 <!-- Twitter Card -->
 <meta name="twitter:card" content="summary_large_image" />
 <meta name="twitter:title" content="@yield('title', settings('site_name', 'Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯')) | Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©" />
 <meta name="twitter:description" content="@yield('meta_description', 'Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯ ÙÙŠ Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©ØŒ Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©.')" />
 <meta name="twitter:image" content="@yield('og_image', asset('assets/site/img/og-cover.png'))" />

 @yield('meta')

 <!-- Global Schema.org JSON-LD (GroceryStore / LocalBusiness) -->
 <script type="application/ld+json">
 {
 "@context": "https://schema.org",
 "@type": "GroceryStore",
 "name": "Ø£Ù„Ø¨Ø§Ù† ÙˆØ³ÙˆØ¨Ø±Ù…Ø§Ø±ÙƒØª Ø§Ù„ØªÙˆØ­ÙŠØ¯",
 "image": "{{ asset('assets/site/img/og-cover.png') }}",
 "telephone": "{{ settings('store_phone', '01000000000') }}",
 "address": {
 "@type": "PostalAddress",
 "streetAddress": "Ø´Ø§Ø±Ø¹ Ø§Ù„Ù…Ø³ØªØ´ÙÙ‰ - Ø¨Ø¬ÙˆØ§Ø± Ø§Ù„Ø¥Ø³Ø¹Ø§Ù",
 "addressLocality": "Ø§Ù„Ø¹Ø§Ù…Ø±ÙŠØ©",
 "addressRegion": "Ø§Ù„Ø¥Ø³ÙƒÙ†Ø¯Ø±ÙŠØ©",
 "addressCountry": "EG"
 },
 "priceRange": "EGP",
 "currenciesAccepted": "EGP",
 "paymentAccepted": "Cash, InstaPay",
 "openingHours": "Mo-Su 08:00-00:00",
 "url": "{{ url('/') }}"
 }
 </script>

 @php
 $siteFavicon = get_site_favicon();
 @endphp
 @if($siteFavicon)
 <link rel="icon" href="{{ $siteFavicon }}">
 <link rel="shortcut icon" href="{{ $siteFavicon }}">
 <link rel="apple-touch-icon" href="{{ $siteFavicon }}">
 @else
 <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22></text></svg>">
 @endif

 <!-- Google Arabic Fonts (Cairo, Alexandria, Readex Pro) -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700;800;900&family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

 <!-- Swiper & Toastr CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

 <!-- Vite Assets (Tailwind & Alpine) -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])

 <style>
 [x-cloak] {
 display: none !important;
 }

 /* Toastr customization for Tawheed brand */
 #toast-container > div {
 opacity: 1 !important;
 box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15) !important;
 border-radius: 12px !important;
 font-family: 'Cairo', sans-serif !important;
 text-align: right !important;
 direction: rtl !important;
 }
 .toast-success {
 background-color: #065F46 !important;
 }
 .toast-error {
 background-color: #730E1F !important;
 }
 </style>

 @stack('styles')
</head>

<body class="bg-canvas-base text-charcoal font-sans antialiased min-h-screen flex flex-col overflow-x-clip selection:bg-primary-700 selection:text-white">
 
 <!-- Top Information Bar -->
 <x-storefront.layout.top-bar />

 <!-- Main Navigation Header -->
 <x-storefront.layout.header />

 <!-- Mobile Slide-over Menu -->
 <x-storefront.layout.mobile-menu />

 <!-- Main Body Content -->
 <main class="flex-grow">
 @yield('content')
 </main>

 <!-- Global Footer -->
 <x-storefront.layout.footer />

    <!-- Slide-over Cart Drawer -->
    <x-storefront.layout.cart-drawer />

 <!-- WhatsApp Floating Action Button -->
 <x-storefront.layout.whatsapp-float />

 <!-- Custom Tawheed Modal Dialog -->
 <div id="tawheedModal" 
 class="fixed inset-0 z-[99999] bg-charcoal-heading/60 backdrop-blur-xs flex items-center justify-center p-4 opacity-0 pointer-events-none transition-all duration-300"
 dir="rtl">
 <div id="tawheedModalCard" 
 class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl border border-cream transform scale-95 transition-all duration-300 space-y-4">
 
 <div id="tawheedModalIcon" class="w-12 h-12 rounded-2xl bg-primary-50 text-primary-800 border border-primary-200 flex items-center justify-center text-xl mx-auto shadow-inner">
 
 </div>

 <div class="space-y-1.5">
 <h3 id="tawheedModalTitle" class="text-base font-bold text-charcoal-heading">
 ØªØ£ÙƒÙŠØ¯ Ø§Ù„Ø¥Ø¬Ø±Ø§Ø¡
 </h3>
 <p id="tawheedModalDesc" class="text-xs text-charcoal-muted leading-relaxed">
 Ù‡Ù„ Ø£Ù†Øª Ù…ØªØ£ÙƒØ¯ Ù…Ù† Ø§Ù„Ù…ØªØ§Ø¨Ø¹Ø©ØŸ
 </p>
 </div>

 <div class="flex items-center gap-2.5 pt-2">
 <button type="button" 
 id="tawheedModalCancelBtn"
 class="flex-1 py-2.5 px-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-charcoal text-xs font-bold transition-colors cursor-pointer">
 Ø¥Ù„ØºØ§Ø¡
 </button>
 <button type="button" 
 id="tawheedModalConfirmBtn"
 class="flex-1 py-2.5 px-4 rounded-xl bg-primary-800 hover:bg-primary-900 text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
 ØªØ£ÙƒÙŠØ¯
 </button>
 </div>
 </div>
 </div>

 <!-- Scripts -->
 <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

 <script>
 // Setup CSRF token for jQuery AJAX
 $.ajaxSetup({
 headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 }
 });

 // Toastr Defaults
 toastr.options = {
 "closeButton": true,
 "progressBar": true,
 "positionClass": "toast-bottom-left",
 "timeOut": "3500",
 "rtl": true
 };

 // Flash message toasts
 @if(session('success'))
 toastr.success(@json(session('success')));
 @endif
 @if(session('error'))
 toastr.error(@json(session('error')));
 @endif
 @if(session('status'))
 toastr.info(@json(session('status')));
 @endif

 // Global Tawheed Modal Helper
 window.tawheedConfirm = function(title, message, onConfirm, onCancel, options = {}) {
 const modal = document.getElementById('tawheedModal');
 const card = document.getElementById('tawheedModalCard');
 const titleEl = document.getElementById('tawheedModalTitle');
 const descEl = document.getElementById('tawheedModalDesc');
 const confirmBtn = document.getElementById('tawheedModalConfirmBtn');
 const cancelBtn = document.getElementById('tawheedModalCancelBtn');
 const iconEl = document.getElementById('tawheedModalIcon');

 if (!modal) return;

 titleEl.textContent = title || 'ØªØ£ÙƒÙŠØ¯';
 descEl.textContent = message || '';
 confirmBtn.textContent = options.confirmText || 'ØªØ£ÙƒÙŠØ¯';
 cancelBtn.textContent = options.cancelText || 'Ø¥Ù„ØºØ§Ø¡';
 if (options.icon) iconEl.textContent = options.icon;

 const closeModal = () => {
 modal.classList.add('opacity-0', 'pointer-events-none');
 card.classList.remove('scale-100');
 card.classList.add('scale-95');
 };

 const newConfirm = confirmBtn.cloneNode(true);
 confirmBtn.parentNode.replaceChild(newConfirm, confirmBtn);

 const newCancel = cancelBtn.cloneNode(true);
 cancelBtn.parentNode.replaceChild(newCancel, cancelBtn);

 newConfirm.addEventListener('click', () => {
 closeModal();
 if (typeof onConfirm === 'function') onConfirm();
 });

 newCancel.addEventListener('click', () => {
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

 // Custom Event listener for cart count updates
 window.addEventListener('cart-updated', function(event) {
 const count = event.detail?.count ?? event.detail;
 const badge = document.getElementById('cart-badge-count');
 if (badge && count !== undefined) {
 badge.textContent = count;
 }
 });

 // Global Wishlist toggle handler
 window.toggleWishlist = function(productId, btnEl) {
 fetch('/wishlist/toggle/' + productId, {
 method: 'POST',
 headers: {
 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
 'Accept': 'application/json'
 }
 })
 .then(res => {
 if (res.status === 401) {
 toastr.info('ÙŠØ±Ø¬Ù‰ ØªØ³Ø¬ÙŠÙ„ Ø§Ù„Ø¯Ø®ÙˆÙ„ Ù„Ø­ÙØ¸ Ø§Ù„Ù…Ù†ØªØ¬ ÙÙŠ Ø§Ù„Ù…ÙØ¶Ù„Ø©');
 return null;
 }
 return res.json();
 })
 .then(data => {
 if (!data) return;
 if (data.success) {
 toastr.success(data.message || 'ØªÙ… ØªØ­Ø¯ÙŠØ« Ø§Ù„Ù…ÙØ¶Ù„Ø©');
 if (btnEl) {
 const svg = btnEl.querySelector('svg');
 if (data.in_wishlist) {
 btnEl.classList.add('text-rose-600');
 if (svg) svg.setAttribute('fill', 'currentColor');
 } else {
 btnEl.classList.remove('text-rose-600');
 if (svg) svg.setAttribute('fill', 'none');
 }
 }
 } else {
 toastr.error(data.message || 'ØªØ¹Ø°Ø± ØªØ­Ø¯ÙŠØ« Ø§Ù„Ù…ÙØ¶Ù„Ø©');
 }
 })
 .catch(err => {
 toastr.error('Ø­Ø¯Ø« Ø®Ø·Ø£ Ø£Ø«Ù†Ø§Ø¡ ØªØ­Ø¯ÙŠØ« Ø§Ù„Ù…ÙØ¶Ù„Ø©');
 });
 };

 // Global Add To Cart AJAX Helper
 window.addToCart = function(productId, quantity = 1, btnEl = null) {
 let originalContent = '';
 if (btnEl) {
 originalContent = btnEl.innerHTML;
 btnEl.disabled = true;
 btnEl.innerHTML = '<svg class="w-4 h-4 animate-spin mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>';
 }

 return fetch('/add-to-cart/' + productId, {
 method: 'POST',
 headers: {
 'Content-Type': 'application/json',
 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
 'Accept': 'application/json'
 },
 body: JSON.stringify({ quantity: quantity })
 })
 .then(res => res.json())
 .then(data => {
 if (btnEl) {
 btnEl.disabled = false;
 btnEl.innerHTML = originalContent;
 }
 if (data.success || data.status) {
 toastr.success(data.message || 'ØªÙ…Øª Ø¥Ø¶Ø§ÙØ© Ø§Ù„Ù…Ù†ØªØ¬ Ø¥Ù„Ù‰ Ø§Ù„Ø³Ù„Ø© Ø¨Ù†Ø¬Ø§Ø­');
 window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count ?? data.count } }));
 } else {
 toastr.error(data.message || 'ØªØ¹Ø°Ø± Ø¥Ø¶Ø§ÙØ© Ø§Ù„Ù…Ù†ØªØ¬ Ù„Ù„Ø³Ù„Ø©');
 }
 return data;
 })
 .catch(err => {
 if (btnEl) {
 btnEl.disabled = false;
 btnEl.innerHTML = originalContent;
 }
 toastr.error('Ø­Ø¯Ø« Ø®Ø·Ø£ Ø£Ø«Ù†Ø§Ø¡ Ø§Ù„Ø§ØªØµØ§Ù„ Ø¨Ø§Ù„Ø®Ø§Ø¯Ù…');
 throw err;
 });
 };
 </script>

 @stack('scripts')
</body>

</html>
