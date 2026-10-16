<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', settings('site_name', 'أزياء لافيستا') . ' | متجر الملابس والأزياء العصرية')</title>
    <meta name="description" content="@yield('meta_description', settings('site_tagline', 'أزياء لافيستا — متجر الملابس والأزياء المصرية العصرية لكافة أفراد الأسرة. أحدث تشكيلات الملابس الرجالية، الحريمية، والأطفال مع معاينة وقياس قبل الاستلام وشحن سريع لجميع المحافظات.'))">
    <meta name="keywords" content="@yield('meta_keywords', 'أزياء لافيستا, ملابس رجالي, ملابس حريمي, ملابس أطفال, قمصان, بناطيل, فساتين, جواكت, شحن لجميع المحافظات, دفع عند الاستلام, مصر')">
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="ar_EG" />
    <meta property="og:site_name" content="{{ settings('site_name', 'أزياء لافيستا') }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', settings('site_name', 'أزياء لافيستا'))" />
    <meta property="og:description" content="@yield('meta_description', settings('site_tagline', 'تسوق أرقى الملابس المصرية بأسعار مميزة وجودة عالية مع الدفع عند الاستلام.'))" />
    @if(settings('og_image'))
        <meta property="og:image" content="{{ asset('storage/' . settings('og_image')) }}" />
    @elseif(settings('site_logo'))
        <meta property="og:image" content="{{ asset('storage/' . settings('site_logo')) }}" />
    @endif

    <!-- Schema.org JSON-LD Structured Data for Google Rich Snippets -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "WebSite",
                "@id": "{{ url('/') }}#website",
                "url": "{{ url('/') }}",
                "name": "{{ settings('site_name', 'أزياء لافيستا') }}",
                "description": "{{ settings('site_tagline', 'متجر الملابس والأزياء العصرية الراقية') }}",
                "inLanguage": "ar-EG",
                "potentialAction": {
                    "@type": "SearchAction",
                    "target": "{{ route('site.products') }}?search={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            },
            {
                "@type": "ClothingStore",
                "@id": "{{ url('/') }}#store",
                "name": "{{ settings('site_name', 'أزياء لافيستا') }}",
                "url": "{{ url('/') }}",
                "logo": "{{ settings('site_logo') ? asset('storage/' . settings('site_logo')) : asset('assets/site/img/logo.png') }}",
                "image": "{{ settings('og_image') ? asset('storage/' . settings('og_image')) : (settings('site_logo') ? asset('storage/' . settings('site_logo')) : '') }}",
                "description": "{{ settings('site_tagline', 'متجر الملابس والأزياء العصرية الراقية لكافة أفراد الأسرة') }}",
                "telephone": "{{ settings('topbar_phone', '01000000000') }}",
                "priceRange": "$$",
                "currenciesAccepted": "EGP",
                "paymentAccepted": "Cash, InstaPay, Vodafone Cash, Credit Card",
                "address": {
                    "@type": "PostalAddress",
                    "addressCountry": "EG"
                }
            }
        ]
    }
    </script>

    <!-- Google Fonts: Alexandria & Cairo (Modern Clean Arabic Typography) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800;900&family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Swiper & Toastr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --brand-primary: #AC9653;
            --brand-secondary: #BDAB75;
            --brand-beige: #C2B280;
            --brand-camel: #CBA367;
            --brand-white: #FFFFFF;
        }

        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Alexandria', 'Cairo', system-ui, -apple-system, sans-serif;
            background-color: #FFFFFF;
            color: #1F2937;
        }

        /* Color utilities matching the approved palette */
        .bg-brand-primary { background-color: #AC9653; }
        .bg-brand-secondary { background-color: #BDAB75; }
        .bg-brand-beige { background-color: #C2B280; }
        .bg-brand-camel { background-color: #CBA367; }
        .text-brand-primary { color: #AC9653; }
        .text-brand-secondary { color: #BDAB75; }
        .text-brand-camel { color: #CBA367; }
        .border-brand-primary { border-color: #AC9653; }
        .border-brand-secondary { border-color: #BDAB75; }
        .border-brand-camel { border-color: #CBA367; }

        /* Toastr Customization */
        #toast-container > div {
            opacity: 1 !important;
            box-shadow: 0 10px 30px rgba(172, 150, 83, 0.25) !important;
            border-radius: 10px !important;
            font-family: 'Alexandria', sans-serif !important;
            text-align: right !important;
            direction: rtl !important;
            border: 1px solid #BDAB75 !important;
            background-color: #AC9653 !important;
            color: #FFFFFF !important;
        }

        /* Smooth scroll */
        html { scroll-behavior: smooth; }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #F3F4F6;
        }
        ::-webkit-scrollbar-thumb {
            background: #BDAB75;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #AC9653;
        }

        /* Micro-Animations & Interactivity */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-6px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(203, 163, 103, 0.5); }
            50% { box-shadow: 0 0 0 8px rgba(203, 163, 103, 0); }
        }
        .animate-pulse-glow {
            animation: pulse-glow 2.5s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .shimmer-badge {
            position: relative;
            overflow: hidden;
        }
        .shimmer-badge::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            animation: shimmer 2.5s infinite;
        }

        /* Animated Navigation Underline */
        .nav-link-indicator {
            position: relative;
            display: inline-flex;
            align-items: center;
            transition: color 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link-indicator::after {
            content: '';
            position: absolute;
            bottom: 0px;
            right: 50%;
            transform: translateX(50%);
            width: 0;
            height: 3px;
            background-color: #CBA367;
            border-radius: 3px 3px 0 0;
            transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .nav-link-indicator:hover::after,
        .nav-link-indicator.active::after {
            width: 80%;
        }

        /* Glassmorphic Dropdown Panels */
        .dropdown-glass {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 45px -10px rgba(43, 33, 24, 0.2), 0 0 0 1px rgba(189, 171, 117, 0.35);
        }

        /* Smooth card lift */
        .card-lift {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.2s ease;
        }
        .card-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 32px -8px rgba(172, 150, 83, 0.2);
            border-color: #AC9653;
        }

        html, body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-[#FFFFFF] text-[#1F2937] antialiased min-h-screen flex flex-col selection:bg-[#AC9653] selection:text-white pb-20 lg:pb-0 overflow-x-hidden w-full max-w-full" dir="rtl">

    <!-- Main Navigation Header (Includes Top Bar + Main Bar + Category Nav) -->
    <x-storefront.layout.header />

    <!-- Mobile Slide-over Menu -->
    <x-storefront.layout.mobile-menu />

    <!-- Slide-over Cart Drawer -->
    <x-storefront.layout.cart-drawer />

    <!-- Main Dynamic Content (Pure White Canvas) -->
    <main class="flex-grow bg-[#FFFFFF]">
        @yield('content')
    </main>

    <!-- Global Floating WhatsApp Help Button -->
    <x-storefront.layout.whatsapp-float />

    <!-- App-Like Mobile Sticky Bottom Navigation -->
    <x-storefront.layout.mobile-bottom-nav />

    <!-- Global Footer -->
    <x-storefront.layout.footer />

    <!-- Scripts CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-left",
            "timeOut": "4000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
        @if(session('info'))
            toastr.info("{{ session('info') }}");
        @endif
    </script>

    @stack('scripts')
</body>

</html>
