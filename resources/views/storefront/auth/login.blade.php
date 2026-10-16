{{-- resources/views/storefront/auth/login.blade.php --}}
@extends('layouts.storefront')

@section('title', 'تسجيل الدخول | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-14 bg-[#FBF6EA] min-h-[80vh] flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-md">
        
        <div class="bg-white rounded-3xl p-7 sm:p-9 border border-[#EADFC8] shadow-sm space-y-6">
            
            <!-- Brand Header -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center shadow-md mx-auto text-2xl font-black">
                    {{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-[#2B2118]">
                    تسجيل الدخول لـ {{ settings('site_name', 'أزياء لافيستا') }}
                </h1>
                <p class="text-xs text-[#8F7B63]">
                    سجل دخولك لمتابعة مشترياتك وطلبات أزيائك وتنسيقاتك المفضلة
                </p>
            </div>

            @if($errors->any())
            <div class="p-3.5 rounded-2xl bg-[#FBF6EA] border border-[#A9552F] text-[#A9552F] text-xs space-y-1">
                @foreach($errors->all() as $error)
                <div class="flex items-center gap-1.5 font-bold">
                    <span>•</span>
                    <span>{{ $error }}</span>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <!-- Phone / Email -->
                <div>
                    <label for="login" class="font-bold text-[#2B2118] block mb-1.5">
                        رقم الهاتف أو البريد الإلكتروني <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" 
                           id="login" 
                           name="login" 
                           value="{{ old('login') }}" 
                           placeholder="01012345678 أو email@example.com"
                           required 
                           autofocus
                           dir="ltr"
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] text-right outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="font-bold text-[#2B2118]">
                            كلمة المرور <span class="text-[#A9552F]">*</span>
                        </label>
                        @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[11px] text-[#A9552F] hover:underline font-medium">
                            نسيت كلمة المرور؟
                        </a>
                        @endif
                    </div>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" 
                           id="remember" 
                           name="remember" 
                           value="1"
                           class="w-4 h-4 rounded text-[#382416] focus:ring-[#382416] border-[#EADFC8] accent-[#382416]">
                    <label for="remember" class="text-xs text-[#6B5642] cursor-pointer font-semibold select-none">
                        تذكرني على هذا الجهاز
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-[#382416] text-[#FBF6EA] font-bold text-xs text-center shadow-md hover:bg-[#2B2118] transition-all cursor-pointer">
                        تسجيل الدخول
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="pt-4 border-t border-[#EADFC8] text-center text-xs text-[#8F7B63]">
                ليس لديك حساب بعد؟ 
                <a href="{{ route('register') }}" class="font-bold text-[#A9552F] hover:underline">
                    أنشئ حساباً جديداً مجاناً ←
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
