<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <title>{{ settings('site_name', 'Regalest Store') }} - {{ __('إنشاء حساب جديد') }}</title>
 
 <!-- Fonts -->
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
 
 <!-- Vite Assets -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])

 <style>
 * { box-sizing: border-box; margin: 0; padding: 0; }
 body { font-family: 'Alexandria', 'Plus Jakarta Sans', sans-serif; }
 
 .split-wrapper {
 display: flex;
 min-height: 100vh;
 width: 100%;
 }
 
 .split-form-col {
 flex: 1 1 50%;
 width: 50%;
 display: flex;
 flex-direction: column;
 justify-content: space-between;
 background-color: #FFFFFF;
 padding: 40px 48px;
 min-height: 100vh;
 }

 .split-image-col {
 flex: 1 1 50%;
 width: 50%;
 position: relative;
 background-color: #18181B;
 display: flex;
 align-items: center;
 justify-content: center;
 padding: 48px;
 overflow: hidden;
 min-height: 100vh;
 }

 .split-image-bg {
 position: absolute;
 inset: 0;
 width: 100%;
 height: 100%;
 object-fit: cover;
 opacity: 0.9;
 transition: transform 1.2s ease-out;
 }
 .split-image-col:hover .split-image-bg {
 transform: scale(1.04);
 }

 .split-overlay {
 position: absolute;
 inset: 0;
 background: linear-gradient(to top, rgba(24, 24, 27, 0.85) 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0.2) 100%);
 }

 @media (max-width: 1024px) {
 .split-wrapper {
 flex-direction: column;
 }
 .split-form-col {
 width: 100%;
 flex: 1 1 100%;
 padding: 32px 20px;
 }
 .split-image-col {
 display: none;
 }
 }
 </style>
</head>
<body class="bg-[#F8F9FA] text-[#18181B] antialiased">

 <div class="split-wrapper">
 
 {{-- Right / Form Side in RTL (Left in LTR) --}}
 <div class="split-form-col">
 
 {{-- Top Header --}}
 <div class="flex items-center justify-between mb-6">
 <a href="{{ route('site.home') }}" class="flex items-center gap-2 group">
 <span class="text-2xl filter drop-shadow"></span>
 <span class="font-royal text-xl font-extrabold tracking-wider text-[#18181B] group-hover:text-[#C5A059] transition-colors">
 {{ settings('site_name', 'REGALEST') }}
 </span>
 </a>
 
 <a href="{{ route('site.home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-[#C5A059] transition-colors">
 <span>{{ __('العودة للرئيسية') }}</span>
 <span class="rtl:rotate-180">&rarr;</span>
 </a>
 </div>

 {{-- Centered Form --}}
 <div class="max-w-md w-full mx-auto my-auto py-4">
 
 <div class="mb-7 text-start">
 <h1 class="text-2xl sm:text-3xl font-extrabold text-[#18181B] tracking-tight">
 {{ __('إنشاء حساب جديد') }} 
 </h1>
 <p class="text-xs sm:text-sm text-gray-500 mt-2 leading-relaxed">
 {{ __('انضم إلى مجتمعنا لتجربة تسوق فريدة ومتابعة مشترياتك وهداياك بكل سهولة') }}
 </p>
 </div>

 {{-- Error & Session Alerts --}}
 @if (session('error'))
 <div class="p-3.5 mb-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center gap-2">
 <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 <span>{{ session('error') }}</span>
 </div>
 @endif

 @if (session('success'))
 <div class="p-3.5 mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium flex items-center gap-2">
 <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
 <span>{{ session('success') }}</span>
 </div>
 @endif

 @if ($errors->any())
 <div class="p-3.5 mb-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium">
 <ul class="list-disc list-inside space-y-1">
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <form method="POST" action="{{ route('register') }}" class="space-y-4">
 @csrf

 {{-- Name --}}
 <div>
 <label for="name" class="block text-xs font-bold text-gray-700 mb-1.5">
 {{ __('الاسم الكامل') }}
 </label>
 <div class="relative">
 <input type="text" 
 id="name" 
 name="name" 
 value="{{ old('name') }}" 
 required 
 autofocus 
 placeholder="{{ __('أدخل اسمك الكريم') }}"
 class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl bg-[#F8F9FA] border border-gray-200 text-[#18181B] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white transition-all">
 <div class="absolute end-3.5 inset-y-0 flex items-center pointer-events-none text-gray-400">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
 </div>
 </div>
 </div>

 {{-- Email --}}
 <div>
 <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5">
 {{ __('البريد الإلكتروني') }}
 </label>
 <div class="relative">
 <input type="email" 
 id="email" 
 name="email" 
 value="{{ old('email') }}" 
 required 
 placeholder="name@example.com"
 class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl bg-[#F8F9FA] border border-gray-200 text-[#18181B] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white transition-all">
 <div class="absolute end-3.5 inset-y-0 flex items-center pointer-events-none text-gray-400">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
 </div>
 </div>
 </div>

 {{-- Passwords Row --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
 <div>
 <label for="password" class="block text-xs font-bold text-gray-700 mb-1.5">
 {{ __('كلمة المرور') }}
 </label>
 <div class="relative">
 <input type="password" 
 id="password" 
 name="password" 
 required 
 placeholder="••••••••"
 class="w-full px-4 py-3 text-xs rounded-2xl bg-[#F8F9FA] border border-gray-200 text-[#18181B] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white transition-all">
 </div>
 </div>

 <div>
 <label for="password_confirmation" class="block text-xs font-bold text-gray-700 mb-1.5">
 {{ __('تأكيد كلمة المرور') }}
 </label>
 <div class="relative">
 <input type="password" 
 id="password_confirmation" 
 name="password_confirmation" 
 required 
 placeholder="••••••••"
 class="w-full px-4 py-3 text-xs rounded-2xl bg-[#F8F9FA] border border-gray-200 text-[#18181B] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white transition-all">
 </div>
 </div>
 </div>

 {{-- Terms Checkbox --}}
 <div class="flex items-center gap-2 py-1">
 <input type="checkbox" 
 id="terms" 
 name="terms" 
 required 
 class="w-4 h-4 rounded text-[#C5A059] focus:ring-[#C5A059] border-gray-300">
 <label for="terms" class="text-xs text-gray-600">
 {{ __('أوافق على') }} 
 <a href="#" class="text-[#C5A059] hover:underline">{{ __('الشروط والأحكام') }}</a>
 {{ __('و سياسة الخصوصية') }}
 </label>
 </div>

 {{-- Submit Button --}}
 <button type="submit" 
 class="w-full py-3.5 rounded-full bg-[#18181B] hover:bg-[#C5A059] text-white text-xs sm:text-sm font-bold transition-all duration-300 shadow-md hover:shadow-lg active:scale-98 flex items-center justify-center gap-2">
 <span>{{ __('إنشاء الحساب') }}</span>
 <span>&larr;</span>
 </button>
 </form>

 {{-- Login Link --}}
 <div class="mt-7 text-center text-xs text-gray-500">
 <span>{{ __('لديك حساب بالفعل؟') }}</span>
 <a href="{{ route('login') }}" class="font-bold text-[#C5A059] hover:underline ms-1">
 {{ __('تسجيل الدخول') }}
 </a>
 </div>
 </div>

 {{-- Bottom Footer --}}
 <div class="text-center text-[11px] text-gray-400 mt-4">
 &copy; {{ date('Y') }} {{ settings('site_name', 'Regalest Store') }}. {{ __('جميع الحقوق محفوظة.') }}
 </div>
 </div>

 {{-- Left / Image Showcase Side in RTL (Right in LTR) --}}
 <div class="split-image-col">
 <img src="{{ asset('assets/site/img/auth/luxury-showcase.jpg') }}" 
 alt="Regalest Showcase" 
 class="split-image-bg">
 
 <div class="split-overlay"></div>

 <div class="relative z-10 max-w-lg p-8 sm:p-10 rounded-3xl bg-black/40 backdrop-blur-md border border-white/20 text-white shadow-2xl">
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#C5A059]/30 border border-[#C5A059]/50 text-[#C5A059] text-[11px] font-bold mb-4">
 <span></span>
 <span>{{ __('انضم إلى عملاء النخبة') }}</span>
 </div>
 
 <h2 class="font-royal text-2xl sm:text-3xl font-extrabold leading-snug mb-3">
 {{ __('وجهتك الاستثنائية للهدايا والمقتنيات الفاخرة') }}
 </h2>
 
 <p class="text-xs sm:text-sm text-gray-200 leading-relaxed font-normal">
 {{ __('أنشئ حسابك خلال ثوانٍ واحصل على تجربة تسوق مخصصة، عروض حصرية للأعضاء، وسرعة وسهولة في إتمام الطلبات.') }}
 </p>

 <div class="mt-6 pt-6 border-t border-white/20 flex items-center justify-between text-xs text-gray-200">
 <div class="flex items-center gap-2">
 <span class="text-emerald-400 font-bold"></span>
 <span>{{ __('تتبع مباشر لحالة الطلبات') }}</span>
 </div>
 <div class="flex items-center gap-2">
 <span class="text-emerald-400 font-bold"></span>
 <span>{{ __('عروض وخصومات خاصة للأعضاء') }}</span>
 </div>
 </div>
 </div>
 </div>

 </div>

</body>
</html>
