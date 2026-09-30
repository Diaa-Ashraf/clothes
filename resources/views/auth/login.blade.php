<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول | ألبان وسوبرماركت التوحيد بالعامرية</title>
    <meta name="description" content="تسجيل الدخول إلى لوحة تحكم ألبان وسوبرماركت التوحيد بالعامرية، الإسكندرية.">
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="ar_EG" />
    <meta property="og:site_name" content="ألبان وسوبرماركت التوحيد" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="تسجيل الدخول | ألبان وسوبرماركت التوحيد" />
    <meta property="og:description" content="بوابة الدخول إلى لوحة التحكم ومتجر ألبان وسوبرماركت التوحيد - العامرية، الإسكندرية." />
    <meta property="og:image" content="{{ asset('assets/site/img/og-cover.png') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', 'Alexandria', sans-serif; }
        
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
            background: linear-gradient(135deg, #7F1D1D 0%, #DC2626 50%, #EA580C 100%);
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
            opacity: 0.35;
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
<body class="bg-canvas-base text-charcoal antialiased">

    <div class="split-wrapper">
        
        {{-- Right / Form Side in RTL --}}
        <div class="split-form-col">
            
            {{-- Top Header --}}
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('site.home') }}" class="flex items-center gap-3 group">
                    @if(get_site_logo())
                        <div class="w-10 h-10 rounded-2xl bg-white p-1 flex items-center justify-center shadow-md border border-orange-200 overflow-hidden shrink-0 group-hover:scale-105 transition-transform">
                            <img src="{{ get_site_logo() }}" alt="{{ settings('site_name', 'ألبان التوحيد') }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-700 via-red-600 to-orange-500 text-white flex items-center justify-center font-black text-xl shadow-md border border-orange-200 shrink-0">
                            🥛
                        </div>
                    @endif
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-black text-red-600">{{ settings('site_name', 'ألبان التوحيد') }}</span>
                            <span class="px-1.5 py-0.2 rounded text-[8px] font-black bg-orange-500 text-white uppercase">ماركت</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-bold">العامرية • الإسكندرية</span>
                    </div>
                </a>
                
                <a href="{{ route('site.home') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-red-600 transition-colors">
                    <span>العودة للمتجر</span>
                    <span class="rotate-180">←</span>
                </a>
            </div>

            {{-- Centered Form --}}
            <div class="max-w-md w-full mx-auto my-auto py-4">
                
                <div class="mb-7 text-right">
                    <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                        تسجيل الدخول
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed font-medium">
                        أدخل بياناتك للدخول إلى حسابك أو لوحة التحكم ومتابعة الطلبات.
                    </p>
                </div>

                {{-- Status / Error Alerts --}}
                @if (session('status'))
                    <div class="p-3 mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="p-3.5 mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-3.5 mb-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3.5 mb-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 mb-1.5 text-right">
                            البريد الإلكتروني
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', 'admin@altawheed.com') }}" 
                                   required 
                                   autofocus 
                                   placeholder="name@example.com"
                                   class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl bg-canvas-base border border-orange-100 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-right">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-gray-700">
                                كلمة المرور
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-orange-600 hover:underline">
                                    نسيت كلمة المرور؟
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl bg-canvas-base border border-orange-100 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:bg-white transition-all text-right">
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between py-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" 
                                   name="remember" 
                                   id="remember" 
                                   checked
                                   class="w-4 h-4 rounded text-red-600 focus:ring-red-500 border-gray-300">
                            <span class="text-xs text-gray-600 font-bold">تذكر تسجيل دخولي</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs sm:text-sm font-black transition-all shadow-lg hover:shadow-orange-500/30 active:scale-98 flex items-center justify-center gap-2 cursor-pointer">
                        <span>تسجيل الدخول</span>
                        <span class="rotate-180">←</span>
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="mt-7 text-center text-xs text-gray-500 font-medium">
                    <span>ليس لديك حساب حتى الآن؟</span>
                    <a href="{{ route('register') }}" class="font-bold text-red-600 hover:underline ms-1">
                        إنشاء حساب عميل جديد
                    </a>
                </div>
            </div>

            {{-- Bottom Footer --}}
            <div class="text-center text-[11px] text-gray-400 mt-4 font-medium">
                &copy; {{ date('Y') }} ألبان وسوبرماركت التوحيد بالعامرية. جميع الحقوق محفوظة.
            </div>
        </div>

        {{-- Left / Showcase Side --}}
        <div class="split-image-col">
            <div class="relative z-10 max-w-lg p-8 sm:p-10 rounded-3xl bg-black/40 backdrop-blur-md border border-white/20 text-white shadow-2xl space-y-4 text-right" dir="rtl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400 text-gray-950 text-xs font-black">
                    <span>🥛 منتجات ألبان بلدية 100% طازجة</span>
                </div>
                
                <h2 class="text-2xl sm:text-3xl font-black leading-snug">
                    ألبان وسوبرماركت التوحيد بالعامرية
                </h2>
                
                <p class="text-xs sm:text-sm text-gray-100 leading-relaxed font-medium">
                    لوحة الإدارة والتحكم لمتابعة طلبات التوصيل بالعامرية، تحديث كميات الأجبان والألبان الفلاحية والمخزون، وتأكيد تحويلات إنستاباي.
                </p>

                <div class="pt-4 border-t border-white/20 flex items-center justify-between text-xs text-gray-200">
                    <div class="flex items-center gap-1.5 font-bold">
                        <span class="text-emerald-400">✓</span>
                        <span>4 فروع في خدمتك</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-bold">
                        <span class="text-emerald-400">✓</span>
                        <span>توصيل سريع حتى 2 صباحاً</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
