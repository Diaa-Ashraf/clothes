<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول | {{ settings('site_name', 'أزياء لافيستا') }}</title>
    <meta name="description" content="تسجيل الدخول إلى لوحة تحكم {{ settings('site_name', 'أزياء لافيستا') }}.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', 'Alexandria', sans-serif; background-color: #FBF6EA; }
        
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
            background: linear-gradient(135deg, #2B2118 0%, #382416 60%, #543924 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
            overflow: hidden;
            min-height: 100vh;
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
<body class="bg-[#FBF6EA] text-[#2B2118] antialiased">

    <div class="split-wrapper">
        
        {{-- Right / Form Side in RTL --}}
        <div class="split-form-col">
            
            {{-- Top Header --}}
            <div class="flex items-center justify-between mb-6">
                <a href="{{ route('site.home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center font-black text-xl shadow-md border border-[#CDB78F]/30 shrink-0">
                        {{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5">
                            <span class="text-base font-black text-[#2B2118]">{{ settings('site_name', 'أزياء لافيستا') }}</span>
                            <span class="px-1.5 py-0.2 rounded text-[8px] font-black bg-[#A9552F] text-white">بوتيك</span>
                        </div>
                        <span class="text-[10px] text-[#8F7B63] font-bold">ملابس وأزياء راقية</span>
                    </div>
                </a>
                
                <a href="{{ route('site.home') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#8F7B63] hover:text-[#2B2118] transition-colors">
                    <span>العودة للبوتيك</span>
                    <span class="rotate-180">←</span>
                </a>
            </div>

            {{-- Centered Form --}}
            <div class="max-w-md w-full mx-auto my-auto py-4">
                
                <div class="mb-7 text-right">
                    <h1 class="text-2xl sm:text-3xl font-black text-[#2B2118] tracking-tight">
                        تسجيل الدخول
                    </h1>
                    <p class="text-xs sm:text-sm text-[#8F7B63] mt-1.5 leading-relaxed font-medium">
                        أدخل بياناتك للدخول إلى حسابك أو لوحة تحكم إدارة المتجر.
                    </p>
                </div>

                {{-- Status / Error Alerts --}}
                @if (session('status'))
                    <div class="p-3 mb-5 rounded-2xl bg-[#F3E9D2] border border-[#CDB78F] text-[#2B2118] text-xs font-bold">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="p-3.5 mb-5 rounded-2xl bg-[#F3E9D2] border border-[#CDB78F] text-[#2B2118] text-xs font-bold flex items-center gap-2">
                        <span>✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-3.5 mb-5 rounded-2xl bg-[#FBF6EA] border border-[#A9552F] text-[#A9552F] text-xs font-bold flex items-center gap-2">
                        <span>•</span>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3.5 mb-5 rounded-2xl bg-[#FBF6EA] border border-[#A9552F] text-[#A9552F] text-xs font-bold flex items-center gap-2">
                        <span>•</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-[#2B2118] mb-1.5 text-right">
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
                                   class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl bg-[#FBF6EA]/40 border border-[#EADFC8] text-[#2B2118] placeholder-[#8F7B63] focus:outline-none focus:ring-2 focus:ring-[#382416] focus:bg-white transition-all text-right">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-[#2B2118]">
                                كلمة المرور
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-[#A9552F] hover:underline">
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
                                   class="w-full px-4 py-3 text-xs sm:text-sm rounded-2xl bg-[#FBF6EA]/40 border border-[#EADFC8] text-[#2B2118] placeholder-[#8F7B63] focus:outline-none focus:ring-2 focus:ring-[#382416] focus:bg-white transition-all text-right">
                        </div>
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between py-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" 
                                   name="remember" 
                                   id="remember" 
                                   checked
                                   class="w-4 h-4 rounded text-[#382416] focus:ring-[#382416] border-[#EADFC8] accent-[#382416]">
                            <span class="text-xs text-[#6B5642] font-bold">تذكر تسجيل دخولي</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-3.5 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs sm:text-sm font-black transition-all shadow-md active:scale-98 flex items-center justify-center gap-2 cursor-pointer">
                        <span>تسجيل الدخول</span>
                        <span class="rotate-180">←</span>
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="mt-7 text-center text-xs text-[#8F7B63] font-medium">
                    <span>ليس لديك حساب حتى الآن؟</span>
                    <a href="{{ route('register') }}" class="font-bold text-[#A9552F] hover:underline ms-1">
                        إنشاء حساب عميل جديد
                    </a>
                </div>
            </div>

            {{-- Bottom Footer --}}
            <div class="text-center text-[11px] text-[#8F7B63] mt-4 font-medium">
                &copy; {{ date('Y') }} {{ settings('site_name', 'أزياء لافيستا') }}. جميع الحقوق محفوظة.
            </div>
        </div>

        {{-- Left / Showcase Side --}}
        <div class="split-image-col">
            <div class="relative z-10 max-w-lg p-8 sm:p-10 rounded-3xl bg-[#2B2118]/80 backdrop-blur-md border border-[#CDB78F]/30 text-[#FBF6EA] shadow-2xl space-y-4 text-right" dir="rtl">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3E9D2] text-[#382416] text-xs font-black">
                    <span>✨ تشكيلات أزياء راقية وخامات طبيعية</span>
                </div>
                
                <h2 class="text-2xl sm:text-3xl font-black leading-snug text-[#FBF6EA]">
                    {{ settings('site_name', 'أزياء لافيستا') }}
                </h2>
                
                <p class="text-xs sm:text-sm text-[#EADFC8] leading-relaxed font-medium">
                    لوحة الإدارة والتحكم لمتابعة طلبيات الأزياء، وتنسيق المقاسات وتحديث المخزون وإدارة المعروضات، وتأكيد تحويلات إنستاباي.
                </p>

                <div class="pt-4 border-t border-[#CDB78F]/30 flex items-center justify-between text-xs text-[#EADFC8]">
                    <div class="flex items-center gap-1.5 font-bold">
                        <span class="text-[#CDB78F]">✓</span>
                        <span>فروع وبوتيكات متعددة</span>
                    </div>
                    <div class="flex items-center gap-1.5 font-bold">
                        <span class="text-[#CDB78F]">✓</span>
                        <span>شحن سريع ومعاينة قبل الاستلام</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
