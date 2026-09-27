{{-- resources/views/storefront/auth/login.blade.php --}}
@extends('layouts.storefront')

@section('title', 'تسجيل الدخول | ألبان وسوبرماركت التوحيد')

@section('content')
<div class="py-12 bg-canvas-base min-h-[80vh] flex items-center justify-center">
 <div class="container mx-auto px-4 max-w-md">
 
 <div class="bg-white rounded-3xl p-7 sm:p-9 border border-cream shadow-sm space-y-6">
 
 <!-- Brand Header -->
 <div class="text-center space-y-2">
 <div class="w-14 h-14 rounded-2xl bg-gradient-maroon text-white flex items-center justify-center shadow-md shadow-primary-900/20 mx-auto text-2xl font-bold">
 ت
 </div>
 <h1 class="text-xl sm:text-2xl font-black text-charcoal-heading">
 تسجيل الدخول لحسابك
 </h1>
 <p class="text-xs text-charcoal-muted">
 أدخل رقم الهاتف أو البريد لمتابعة طلباتك في ألبان التوحيد
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

 <!-- Login Form -->
 <form action="{{ route('login') }}" method="POST" class="space-y-4 text-xs">
 @csrf

 <!-- Phone / Email -->
 <div>
 <label for="login" class="font-bold text-charcoal-heading block mb-1.5">
 رقم الهاتف أو البريد الإلكتروني <span class="text-rose-600">*</span>
 </label>
 <input type="text" 
 id="login" 
 name="login" 
 value="{{ old('login') }}" 
 placeholder="01012345678 أو email@example.com"
 required 
 autofocus
 dir="ltr"
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal text-right outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <!-- Password -->
 <div>
 <div class="flex items-center justify-between mb-1.5">
 <label for="password" class="font-bold text-charcoal-heading">
 كلمة المرور <span class="text-rose-600">*</span>
 </label>
 @if(Route::has('password.request'))
 <a href="{{ route('password.request') }}" class="text-[11px] text-primary-700 hover:underline">
 نسيت كلمة المرور؟
 </a>
 @endif
 </div>
 <input type="password" 
 id="password" 
 name="password" 
 required 
 class="w-full bg-canvas-base border border-cream rounded-xl px-3.5 py-2.5 text-xs text-charcoal outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-100">
 </div>

 <!-- Remember Me -->
 <div class="flex items-center gap-2">
 <input type="checkbox" 
 id="remember" 
 name="remember" 
 value="1"
 class="w-4 h-4 rounded text-primary-800 focus:ring-primary-600 border-cream">
 <label for="remember" class="text-xs text-charcoal cursor-pointer font-semibold select-none">
 تذكرني على هذا الجهاز
 </label>
 </div>

 <!-- Submit Button -->
 <div class="pt-2">
 <button type="submit" 
 class="w-full py-3 px-4 rounded-xl bg-gradient-maroon text-white font-bold text-xs text-center shadow-md shadow-primary-900/20 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
 تسجيل الدخول
 </button>
 </div>
 </form>

 <!-- Register Link -->
 <div class="pt-4 border-t border-cream text-center text-xs text-charcoal-muted">
 ليس لديك حساب بعد؟ 
 <a href="{{ route('register') }}" class="font-bold text-primary-800 hover:underline">
 أنشئ حساباً جديداً مجاناً ←
 </a>
 </div>

 </div>

 </div>
</div>
@endsection
