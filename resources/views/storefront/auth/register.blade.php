{{-- resources/views/storefront/auth/register.blade.php --}}
@extends('layouts.storefront')

@section('title', 'إنشاء حساب جديد | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-12 bg-canvas-base min-h-[85vh] flex items-center justify-center">
 <div class="container mx-auto px-4 max-w-lg">
 
 <div class="bg-white rounded-3xl p-7 sm:p-9 border border-cream shadow-sm space-y-6">
 
 <!-- Brand Header -->
 <div class="text-center space-y-2">
 <div class="w-14 h-14 rounded-2xl bg-gradient-maroon text-white flex items-center justify-center shadow-md shadow-primary-900/20 mx-auto text-2xl font-bold">
 ت
 </div>
 <h1 class="text-xl sm:text-2xl font-black text-charcoal-heading">
 إنشاء حساب جديد
 </h1>
 <p class="text-xs text-charcoal-muted">
 انضم لعملاء ألبان وسوبرماركت التوحيد بالعامرية واستمتع بتوصيل فوري وعروض حصرية
 </p>
 </div>

 @if($errors->any())
 <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
 @foreach($errors->all() as $error)
 <div class="flex items-center gap-1.5 font-bold">
 <span></span>
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
 <label for="name" class="font-bold text-charcoal-heading block mb-1.5">
 الاسم بالكامل <span class="text-rose-600">*</span>
 </label>
 <input type="text" 
 id="name" 
 name="name" 
 value="{{ old('name') }}" 
 placeholder="محمود أحمد عبد الله"
 required 
 autofocus
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <!-- Phone -->
 <div>
 <label for="phone" class="font-bold text-charcoal-heading block mb-1.5">
 رقم الهاتف (واتساب) <span class="text-rose-600">*</span>
 </label>
 <input type="tel" 
 id="phone" 
 name="phone" 
 value="{{ old('phone') }}" 
 placeholder="01012345678"
 required 
 dir="ltr"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 <span class="text-[10px] text-charcoal-muted mt-1 block">رقم هاتف مصري لتأكيد وتوصيل طلباتك</span>
 </div>

 <!-- Email -->
 <div>
 <label for="email" class="font-bold text-charcoal-heading block mb-1.5">
 البريد الإلكتروني <span class="text-rose-600">*</span>
 </label>
 <input type="email" 
 id="email" 
 name="email" 
 value="{{ old('email') }}" 
 placeholder="name@example.com"
 required 
 dir="ltr"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <!-- Initial Address -->
 <div>
 <label for="address" class="font-bold text-charcoal-heading block mb-1.5">
 عنوان التوصيل الافتراضي بالعامرية <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <input type="text" 
 id="address" 
 name="address" 
 value="{{ old('address') }}" 
 placeholder="العامرية، الشارع الرئيسي، رقم العمارة..."
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <!-- Password Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
 <div>
 <label for="password" class="font-bold text-charcoal-heading block mb-1.5">
 كلمة المرور <span class="text-rose-600">*</span>
 </label>
 <input type="password" 
 id="password" 
 name="password" 
 required 
 placeholder="6 أحرف على الأقل"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <div>
 <label for="password_confirmation" class="font-bold text-charcoal-heading block mb-1.5">
 تأكيد كلمة المرور <span class="text-rose-600">*</span>
 </label>
 <input type="password" 
 id="password_confirmation" 
 name="password_confirmation" 
 required 
 placeholder="أعد إدخال كلمة المرور"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>
 </div>

 <!-- Submit Button -->
 <div class="pt-3">
 <button type="submit" 
 class="w-full py-3 px-4 rounded-xl bg-gradient-maroon text-white font-bold text-xs text-center shadow-md shadow-primary-900/20 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
 إنشاء الحساب والمتابعة
 </button>
 </div>
 </form>

 <!-- Login Link -->
 <div class="pt-4 border-t border-cream text-center text-xs text-charcoal-muted">
 لديك حساب بالفعل؟ 
 <a href="{{ route('login') }}" class="font-bold text-primary-800 hover:underline">
 تسجيل الدخول هنا ←
 </a>
 </div>

 </div>

 </div>
</div>
@endsection
