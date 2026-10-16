{{-- resources/views/storefront/auth/register.blade.php --}}
@extends('layouts.storefront')

@section('title', 'إنشاء حساب جديد | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
<div class="py-14 bg-[#FBF6EA] min-h-[85vh] flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-lg">
        
        <div class="bg-white rounded-3xl p-7 sm:p-9 border border-[#EADFC8] shadow-sm space-y-6">
            
            <!-- Brand Header -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-[#382416] text-[#FBF6EA] flex items-center justify-center shadow-md mx-auto text-2xl font-black">
                    {{ mb_substr(settings('site_name', 'أزياء لافيستا'), 0, 1) }}
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-[#2B2118]">
                    إنشاء حساب جديد
                </h1>
                <p class="text-xs text-[#8F7B63]">
                    انضم لعملاء {{ settings('site_name', 'أزياء لافيستا') }} واستمتع بأحدث التشكيلات وخدمة المعاينة والعروض الحصرية
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

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4 text-xs">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="font-bold text-[#2B2118] block mb-1.5">
                        الاسم بالكامل <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           placeholder="مثال: سارة محمد الشريف"
                           required 
                           autofocus
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="font-bold text-[#2B2118] block mb-1.5">
                        رقم الهاتف (واتساب) <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           placeholder="01012345678"
                           required 
                           dir="ltr"
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] text-right outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                    <span class="text-[10px] text-[#8F7B63] mt-1 block">رقم للتواصل وتأكيد شحن طلبات الأزياء</span>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="font-bold text-[#2B2118] block mb-1.5">
                        البريد الإلكتروني <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="name@example.com"
                           required 
                           dir="ltr"
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] text-right outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                </div>

                <!-- Initial Address -->
                <div>
                    <label for="address" class="font-bold text-[#2B2118] block mb-1.5">
                        عنوان التوصيل الافتراضي <span class="text-[#8F7B63] font-normal">(اختياري)</span>
                    </label>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           value="{{ old('address') }}" 
                           placeholder="المحافظة، المدينة، الشارع، رقم العمارة والشقة..."
                           class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                </div>

                <!-- Password Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="font-bold text-[#2B2118] block mb-1.5">
                            كلمة المرور <span class="text-[#A9552F]">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required 
                               placeholder="6 أحرف على الأقل"
                               class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                    </div>

                    <div>
                        <label for="password_confirmation" class="font-bold text-[#2B2118] block mb-1.5">
                            تأكيد كلمة المرور <span class="text-[#A9552F]">*</span>
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               placeholder="أعد إدخال كلمة المرور"
                               class="w-full bg-[#FBF6EA]/40 border border-[#EADFC8] rounded-xl px-3.5 py-2.5 text-xs text-[#2B2118] outline-none focus:border-[#382416] focus:ring-1 focus:ring-[#382416] transition-colors">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-3">
                    <button type="submit" 
                            class="w-full py-3 px-4 rounded-xl bg-[#382416] text-[#FBF6EA] font-bold text-xs text-center shadow-md hover:bg-[#2B2118] transition-all cursor-pointer">
                        إنشاء الحساب والمتابعة
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="pt-4 border-t border-[#EADFC8] text-center text-xs text-[#8F7B63]">
                لديك حساب بالفعل؟ 
                <a href="{{ route('login') }}" class="font-bold text-[#A9552F] hover:underline">
                    تسجيل الدخول هنا ←
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
