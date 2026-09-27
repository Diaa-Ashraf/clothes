@extends('layouts.site')

@section('title', __('حسابي ولوحة التحكم الشخصية - ') . settings('site_name', 'Regalest Store'))

@section('content')
<div class="bg-[#FAF8F5] min-h-[85vh] py-4 sm:py-8 md:py-12 text-[#18181B]" x-data="{ 
 activeTab: '{{ session('status') === 'password-updated' || $errors->updatePassword->any() ? 'security' : (request('tab') ?? 'info') }}',
 avatarPreview: '{{ $user->avatar ? (str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/avatars/' . $user->avatar)) : '' }}'
}">
 <div class="w-full max-w-[1400px] mx-auto px-3 sm:px-6 lg:px-8">
 
 {{-- Breadcrumb Navigation --}}
 <nav class="text-xs text-[#71717A] flex items-center gap-1.5 sm:gap-2 mb-4 sm:mb-6 overflow-x-auto no-scrollbar py-1">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors shrink-0">{{ __('الرئيسية') }}</a>
 <span class="shrink-0">/</span>
 <span class="text-[#18181B] font-bold shrink-0">{{ __('لوحة تحكم الحساب الشخصي') }}</span>
 </nav>

 {{-- Top Notification Alerts --}}
 @if (session('success') || session('status') === 'profile-updated')
 <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2.5 sm:gap-3 shadow-xs">
 <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
 </svg>
 <span>{{ session('success') ?? __('تم حفظ وتحديث بيانات حسابك بنجاح.') }}</span>
 </div>
 @endif

 @if (session('status') === 'password-updated')
 <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-2.5 sm:gap-3 shadow-xs">
 <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
 </svg>
 <span>{{ __('تم تغيير كلمة المرور بنجاح وحماية حسابك.') }}</span>
 </div>
 @endif

 @if (session('error'))
 <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold flex items-center gap-2.5 sm:gap-3 shadow-xs">
 <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
 </svg>
 <span>{{ session('error') }}</span>
 </div>
 @endif

 @if ($errors->any() && !$errors->updatePassword->any() && !$errors->userDeletion->any())
 <div class="mb-4 sm:mb-6 p-3.5 sm:p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold shadow-xs">
 <div class="flex items-center gap-2 mb-1">
 <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
 <span>{{ __('يرجى تصحيح الأخطاء التالية:') }}</span>
 </div>
 <ul class="list-disc list-inside ps-4 sm:ps-6 space-y-0.5 font-medium text-[11px] sm:text-xs">
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 {{-- 1. Luxury Customer Hero Card --}}
 <div class="bg-gradient-to-br from-[#18181B] via-[#242429] to-[#121214] text-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 mb-6 sm:mb-8 border border-[#3F3F46] shadow-xl relative overflow-hidden">
 {{-- Background Subtle Glows --}}
 <div class="absolute -top-24 -end-24 w-48 sm:w-72 h-48 sm:h-72 rounded-full bg-[#C5A059]/15 blur-3xl pointer-events-none"></div>
 <div class="absolute -bottom-24 -start-24 w-48 sm:w-72 h-48 sm:h-72 rounded-full bg-[#C5A059]/10 blur-3xl pointer-events-none"></div>

 <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5 sm:gap-6">
 {{-- User Info Section --}}
 <div class="flex items-center gap-3.5 sm:gap-6">
 <div class="relative shrink-0">
 <template x-if="avatarPreview">
 <img :src="avatarPreview" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 md:w-22 md:h-22 rounded-full object-cover border-2 border-[#C5A059] shadow-lg">
 </template>
 <template x-if="!avatarPreview">
 <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-22 md:h-22 rounded-full bg-gradient-to-tr from-[#C5A059] to-[#EADBCC] text-[#18181B] flex items-center justify-center font-extrabold text-xl sm:text-2xl md:text-3xl border-2 border-[#C5A059] shadow-lg">
 {{ mb_substr($user->name, 0, 1) }}
 </div>
 </template>
 @if($user->google_id)
 <span class="absolute bottom-0 end-0 w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-white p-0.5 shadow-md flex items-center justify-center" title="{{ __('مرتبط بـ Google') }}">
 <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24Z"/><path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15Z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98Z"/></svg>
 </span>
 @endif
 </div>

 <div class="space-y-1 min-w-0 flex-1">
 <div class="flex items-center gap-2 flex-wrap">
 <h1 class="text-base sm:text-xl md:text-2xl font-black text-white tracking-tight truncate">{{ $user->name }}</h1>
 <span class="px-2 py-0.5 rounded-full bg-[#C5A059]/20 border border-[#C5A059]/40 text-[#EADBCC] text-[9px] sm:text-[10px] font-bold tracking-wider shrink-0">
 {{ __('عميل متميز') }}
 </span>
 </div>
 <p class="text-[11px] sm:text-xs text-gray-300 font-medium flex items-center gap-1 truncate">
 <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
 <span class="truncate">{{ $user->email }}</span>
 </p>
 <p class="text-[10px] sm:text-[11px] text-gray-400">
 {{ __('عضو منذ:') }} <span class="font-bold text-gray-300">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
 </p>
 </div>
 </div>

 {{-- Account Quick Stats Grid --}}
 <div class="grid grid-cols-3 gap-2 sm:gap-4 border-t md:border-t-0 md:border-s border-white/10 pt-3 sm:pt-4 md:pt-0 md:ps-8">
 <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center">
 <span class="block text-[9px] sm:text-xs text-gray-400 font-medium mb-0.5 truncate">{{ __('الطلبات') }}</span>
 <span class="text-sm sm:text-xl font-black text-[#C5A059]">{{ $stats['orders_count'] }}</span>
 </div>

 <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center">
 <span class="block text-[9px] sm:text-xs text-gray-400 font-medium mb-0.5 truncate">{{ __('المفضلة') }}</span>
 <span class="text-sm sm:text-xl font-black text-rose-400">{{ $stats['wishlist_count'] }}</span>
 </div>

 <div class="bg-white/5 border border-white/10 rounded-xl sm:rounded-2xl p-2.5 sm:p-4 text-center">
 <span class="block text-[9px] sm:text-xs text-gray-400 font-medium mb-0.5 truncate">{{ __('المشتريات') }}</span>
 <span class="text-sm sm:text-xl font-black text-emerald-400">{{ number_format($stats['total_spent'], 2) }} ج.م</span>
 </div>
 </div>
 </div>
 </div>

 {{-- Mobile Horizontal Scrollable Tabs Strip --}}
 <div class="lg:hidden mb-6 overflow-x-auto no-scrollbar py-1">
 <div class="flex items-center gap-2 min-w-max p-1 bg-white border border-[#EADBCC] rounded-2xl shadow-xs">
 <button type="button" 
 @click="activeTab = 'info'" 
 :class="activeTab === 'info' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-xs' : 'text-[#71717A] hover:bg-[#F8F6F2] font-medium'"
 class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
 <span></span>
 <span>{{ __('البيانات الشخصية') }}</span>
 </button>

 <button type="button" 
 @click="activeTab = 'orders'" 
 :class="activeTab === 'orders' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-xs' : 'text-[#71717A] hover:bg-[#F8F6F2] font-medium'"
 class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
 <span></span>
 <span>{{ __('طلباتي') }}</span>
 <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-[#C5A059] text-[#18181B]">
 {{ $stats['orders_count'] }}
 </span>
 </button>

 <button type="button" 
 @click="activeTab = 'wishlist'" 
 :class="activeTab === 'wishlist' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-xs' : 'text-[#71717A] hover:bg-[#F8F6F2] font-medium'"
 class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
 <span></span>
 <span>{{ __('المفضلة') }}</span>
 <span class="px-1.5 py-0.2 rounded-full text-[9px] font-bold bg-rose-500 text-white">
 {{ $stats['wishlist_count'] }}
 </span>
 </button>

 <button type="button" 
 @click="activeTab = 'security'" 
 :class="activeTab === 'security' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-xs' : 'text-[#71717A] hover:bg-[#F8F6F2] font-medium'"
 class="px-3.5 py-2 rounded-xl text-xs flex items-center gap-1.5 transition-all">
 <span></span>
 <span>{{ __('الأمان') }}</span>
 </button>
 </div>
 </div>

 {{-- 2. Layout Grid: Sidebar Tabs + Active Content --}}
 <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">
 
 {{-- Desktop Navigation Sidebar --}}
 <div class="hidden lg:block lg:col-span-3 bg-white border border-[#EADBCC] rounded-3xl p-3 sm:p-4 shadow-xs space-y-1.5 sticky top-24">
 <button type="button" 
 @click="activeTab = 'info'" 
 :class="activeTab === 'info' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-sm' : 'text-[#71717A] hover:bg-[#F8F6F2] hover:text-[#18181B] font-medium'"
 class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-xs transition-all text-start">
 <span class="flex items-center gap-3">
 <span class="text-base"></span>
 <span>{{ __('البيانات الشخصية والعنوان') }}</span>
 </span>
 <span class="rtl:rotate-180 text-xs">&rarr;</span>
 </button>

 <button type="button" 
 @click="activeTab = 'orders'" 
 :class="activeTab === 'orders' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-sm' : 'text-[#71717A] hover:bg-[#F8F6F2] hover:text-[#18181B] font-medium'"
 class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-xs transition-all text-start">
 <span class="flex items-center gap-3">
 <span class="text-base"></span>
 <span>{{ __('طلباتي ومتابعة الشحن') }}</span>
 </span>
 <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
 :class="activeTab === 'orders' ? 'bg-[#C5A059] text-[#18181B]' : 'bg-gray-100 text-gray-600'">
 {{ $stats['orders_count'] }}
 </span>
 </button>

 <button type="button" 
 @click="activeTab = 'wishlist'" 
 :class="activeTab === 'wishlist' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-sm' : 'text-[#71717A] hover:bg-[#F8F6F2] hover:text-[#18181B] font-medium'"
 class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-xs transition-all text-start">
 <span class="flex items-center gap-3">
 <span class="text-base"></span>
 <span>{{ __('المقتنيات المفضلة') }}</span>
 </span>
 <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
 :class="activeTab === 'wishlist' ? 'bg-rose-500 text-white' : 'bg-gray-100 text-gray-600'">
 {{ $stats['wishlist_count'] }}
 </span>
 </button>

 <button type="button" 
 @click="activeTab = 'security'" 
 :class="activeTab === 'security' ? 'bg-[#18181B] text-[#EADBCC] font-bold shadow-sm' : 'text-[#71717A] hover:bg-[#F8F6F2] hover:text-[#18181B] font-medium'"
 class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-xs transition-all text-start">
 <span class="flex items-center gap-3">
 <span class="text-base"></span>
 <span>{{ __('الأمان وكلمة المرور') }}</span>
 </span>
 <span class="rtl:rotate-180 text-xs">&rarr;</span>
 </button>

 <div class="pt-3 border-t border-[#EADBCC]/60 mt-2">
 <form method="POST" action="{{ route('logout') }}">
 @csrf
 <button type="submit" 
 class="w-full flex items-center gap-3 px-4 py-2.5 rounded-2xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors text-start">
 <span></span>
 <span>{{ __('تسجيل الخروج') }}</span>
 </button>
 </form>
 </div>
 </div>

 {{-- Tab Content Panel --}}
 <div class="w-full lg:col-span-9 space-y-6">
 
 {{-- TAB 1: Personal Info & Address --}}
 <div x-show="activeTab === 'info'" class="bg-white border border-[#EADBCC] rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xs">
 <div class="pb-4 sm:pb-5 mb-5 sm:mb-6 border-b border-[#EADBCC] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
 <div>
 <h2 class="text-base sm:text-lg font-black text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('البيانات الشخصية ومعلومات التوصيل') }}</span>
 </h2>
 <p class="text-[11px] sm:text-xs text-[#71717A] mt-1">{{ __('قم بتحديث اسمك، بريدك، ورقم هاتفك وعنوان الشحن لتسهيل عملية الشراء.') }}</p>
 </div>
 </div>

 <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5 sm:space-y-6">
 @csrf
 @method('patch')

 {{-- Avatar Upload Field --}}
 <div class="p-3.5 sm:p-4 rounded-2xl bg-[#F8F6F2] border border-[#EADBCC] flex flex-col sm:flex-row items-center gap-4 sm:gap-5">
 <div class="relative shrink-0">
 <template x-if="avatarPreview">
 <img :src="avatarPreview" class="w-16 h-16 sm:w-18 sm:h-18 rounded-full object-cover border-2 border-[#C5A059] shadow-sm">
 </template>
 <template x-if="!avatarPreview">
 <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-full bg-[#18181B] text-[#EADBCC] flex items-center justify-center font-bold text-xl border-2 border-[#C5A059]">
 {{ mb_substr($user->name, 0, 1) }}
 </div>
 </template>
 </div>

 <div class="space-y-1.5 text-center sm:text-start flex-1 w-full">
 <label class="block text-xs font-bold text-[#18181B]">{{ __('الصورة الشخصية (Avatar)') }}</label>
 <p class="text-[10px] sm:text-[11px] text-gray-500">{{ __('صيغ مسموحة: JPG, PNG, WEBP بحد أقصى 3 ميجابايت.') }}</p>
 <input type="file" 
 name="avatar" 
 accept="image/*"
 @change="const file = $event.target.files[0]; if(file) { avatarPreview = URL.createObjectURL(file); }"
 class="w-full text-xs text-gray-500 file:me-3 file:py-1.5 file:px-3.5 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#18181B] file:text-[#EADBCC] hover:file:bg-black cursor-pointer">
 </div>
 </div>

 {{-- Form Inputs Grid --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
 {{-- Full Name --}}
 <div>
 <label for="name" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('الاسم الكامل') }} <span class="text-rose-500">*</span></label>
 <input type="text" 
 id="name" 
 name="name" 
 value="{{ old('name', $user->name) }}" 
 required
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium">
 </div>

 {{-- Email --}}
 <div>
 <label for="email" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('البريد الإلكتروني') }} <span class="text-rose-500">*</span></label>
 <input type="email" 
 id="email" 
 name="email" 
 value="{{ old('email', $user->email) }}" 
 required
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium">
 </div>

 {{-- Phone --}}
 <div class="sm:col-span-2">
 <label for="phone" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('رقم الهاتف / الواتساب') }}</label>
 <input type="text" 
 id="phone" 
 name="phone" 
 value="{{ old('phone', $user->phone) }}" 
 placeholder="+966 50 123 4567"
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium text-start" 
 dir="ltr">
 </div>
 </div>

 {{-- Delivery Address --}}
 <div>
 <label for="address" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('عنوان الشحن والتوصيل الافتراضي') }}</label>
 <textarea id="address" 
 name="address" 
 rows="3" 
 placeholder="{{ __('المدينة، الحي، اسم الشارع، رقم المبنى أو أي تفاصيل إضافية...') }}"
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium leading-relaxed">{{ old('address', $user->address) }}</textarea>
 </div>

 {{-- Save Button --}}
 <div class="flex items-center justify-end pt-2 sm:pt-3">
 <button type="submit" 
 class="w-full sm:w-auto px-8 py-3 rounded-full bg-[#18181B] hover:bg-black text-[#EADBCC] text-xs font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
 <span></span>
 <span>{{ __('حفظ التغييرات') }}</span>
 </button>
 </div>
 </form>
 </div>

 {{-- TAB 2: Orders History & Tracking --}}
 <div x-show="activeTab === 'orders'" style="display: none;" class="bg-white border border-[#EADBCC] rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xs">
 <div class="pb-4 sm:pb-5 mb-5 sm:mb-6 border-b border-[#EADBCC] flex items-center justify-between">
 <div>
 <h2 class="text-base sm:text-lg font-black text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('سجل طلباتي ومتابعة الشحنات') }}</span>
 </h2>
 <p class="text-[11px] sm:text-xs text-[#71717A] mt-1">{{ __('استعرض جميع طلباتك السابقة وتفاصيل كل شحنة وحالتها الحالية.') }}</p>
 </div>
 </div>

 @if($orders->count() > 0)
 <div class="space-y-3 sm:space-y-4">
 @foreach($orders as $order)
 @php
 $statusBadges = [
 'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200', 'label' => 'قيد الانتظار', 'icon' => '⏳'],
 'confirmed' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'label' => 'تم التأكيد', 'icon' => ''],
 'processing' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'label' => 'قيد التجهيز', 'icon' => ''],
 'shipped' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'label' => 'تم الشحن', 'icon' => ''],
 'delivered' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'label' => 'تم التوصيل بنجاح', 'icon' => ''],
 'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200', 'label' => 'ملغي', 'icon' => ''],
 ];
 $badge = $statusBadges[$order->status] ?? ['bg' => 'bg-gray-50', 'text' => 'text-gray-700', 'border' => 'border-gray-200', 'label' => $order->status, 'icon' => ''];
 @endphp

 <div class="border border-[#EADBCC] rounded-2xl p-3.5 sm:p-5 hover:shadow-md transition-shadow bg-[#FCFBF9]">
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 pb-3 sm:pb-4 border-b border-[#EADBCC]/60">
 <div class="space-y-1">
 <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
 <span class="font-black text-xs sm:text-sm text-[#18181B]">#{{ $order->order_number ?? ('REG-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)) }}</span>
 <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-[9px] sm:text-[10px] font-bold border {{ $badge['bg'] }} {{ $badge['text'] }} {{ $badge['border'] }} flex items-center gap-1 shrink-0">
 <span>{{ $badge['icon'] }}</span>
 <span>{{ __($badge['label']) }}</span>
 </span>
 </div>
 <p class="text-[10px] sm:text-[11px] text-gray-500">
 {{ __('تاريخ الطلب:') }} {{ $order->created_at ? $order->created_at->translatedFormat('d F Y - h:i A') : '-' }}
 </p>
 </div>

 <div class="flex items-center justify-between sm:block sm:text-end pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100">
 <span class="text-[10px] sm:text-[11px] text-gray-500 sm:block">{{ __('الإجمالي') }}:</span>
 <span class="text-sm sm:text-base font-black text-[#18181B]">{{ number_format($order->total_price, 2) }} ج.م</span>
 </div>
 </div>

 {{-- Order Items Preview --}}
 <div class="pt-3 sm:pt-4 space-y-2 sm:space-y-3">
 <h4 class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-wider">{{ __('المنتجات المطلوبة') }} ({{ $order->orderItems->count() }}):</h4>
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
 @foreach($order->orderItems as $item)
 <div class="flex items-center gap-2.5 sm:gap-3 p-2 rounded-xl bg-white border border-[#EADBCC]/50">
 @if($item->product && $item->product->image)
 <img src="{{ asset('storage/products/' . $item->product->image) }}" 
 alt="{{ $item->product->name }}" 
 class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg object-cover border border-gray-100 shrink-0">
 @else
 <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gray-100 flex items-center justify-center text-base sm:text-lg shrink-0"></div>
 @endif
 <div class="min-w-0 flex-1">
 <h5 class="text-xs font-bold text-[#18181B] truncate">{{ $item->product ? $item->product->name : __('منتج غير متوفر') }}</h5>
 <p class="text-[10px] sm:text-[11px] text-gray-500">{{ __('الكمية:') }} {{ $item->quantity }} &times; {{ number_format($item->unit_price ?? $item->price, 2) }} ج.م</p>
 </div>
 </div>
 @endforeach
 </div>
 </div>
 </div>
 @endforeach

 {{-- Pagination --}}
 <div class="pt-4 overflow-x-auto">
 {{ $orders->links() }}
 </div>
 </div>
 @else
 <div class="text-center py-10 sm:py-12 space-y-3">
 <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-[#F8F6F2] border border-[#EADBCC] flex items-center justify-center text-2xl sm:text-3xl">
 
 </div>
 <h3 class="text-sm sm:text-base font-bold text-[#18181B]">{{ __('لا توجد طلبات سابقة حتى الآن') }}</h3>
 <p class="text-xs text-[#71717A] max-w-sm mx-auto">{{ __('استكشف تشكيلتنا الحصرية من المجوهرات والمقتنيات الملكية وابدأ طلبك الأول الآن.') }}</p>
 <div class="pt-2">
 <a href="{{ route('product.shop') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#18181B] text-[#EADBCC] hover:bg-black text-xs font-bold transition-all shadow-sm">
 <span></span>
 <span>{{ __('تصفح المتجر الآن') }}</span>
 </a>
 </div>
 </div>
 @endif
 </div>

 {{-- TAB 3: Wishlist Quick View --}}
 <div x-show="activeTab === 'wishlist'" style="display: none;" class="bg-white border border-[#EADBCC] rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xs">
 <div class="pb-4 sm:pb-5 mb-5 sm:mb-6 border-b border-[#EADBCC] flex flex-col sm:flex-row sm:items-center justify-between gap-2">
 <div>
 <h2 class="text-base sm:text-lg font-black text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('مقتنياتك المفضلة المحفوظة') }}</span>
 </h2>
 <p class="text-[11px] sm:text-xs text-[#71717A] mt-1">{{ __('المنتجات التي قمت بحفظها للرجوع إليها أو شرائها لاحقاً.') }}</p>
 </div>

 <a href="{{ route('wishlist.index') }}" class="text-xs font-bold text-[#C5A059] hover:underline flex items-center gap-1 self-start sm:self-auto">
 <span>{{ __('عرض صفحة المفضلة الكاملة') }}</span>
 <span class="rtl:rotate-180">&rarr;</span>
 </a>
 </div>

 @if($wishlistItems->count() > 0)
 <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
 @foreach($wishlistItems as $item)
 @if($item->product)
 <div class="border border-[#EADBCC] rounded-2xl p-2.5 sm:p-3 bg-[#FCFBF9] text-center space-y-1.5 sm:space-y-2 hover:shadow-md transition-shadow">
 <a href="{{ route('product.details', $item->product->slug ?? $item->product->id) }}" class="block aspect-square rounded-xl overflow-hidden bg-white border border-gray-100">
 @if($item->product->image)
 <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
 @else
 <div class="w-full h-full flex items-center justify-center text-2xl sm:text-3xl"></div>
 @endif
 </a>
 <h4 class="text-[11px] sm:text-xs font-bold text-[#18181B] truncate">{{ $item->product->name }}</h4>
 <span class="block text-xs font-black text-[#C5A059]">{{ number_format($item->product->final_price, 2) }} ج.م</span>
 <a href="{{ route('product.details', $item->product->slug ?? $item->product->id) }}" class="block w-full py-1.5 sm:py-2 rounded-full bg-[#18181B] text-[#EADBCC] hover:bg-black text-[9px] sm:text-[10px] font-bold transition-colors">
 {{ __('عرض التفاصيل') }}
 </a>
 </div>
 @endif
 @endforeach
 </div>
 @else
 <div class="text-center py-10 sm:py-12 space-y-3">
 <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto rounded-full bg-[#F8F6F2] border border-[#EADBCC] flex items-center justify-center text-2xl sm:text-3xl">
 
 </div>
 <h3 class="text-sm sm:text-base font-bold text-[#18181B]">{{ __('قائمة المفضلة فارغة') }}</h3>
 <p class="text-xs text-[#71717A] max-w-sm mx-auto">{{ __('لم تقم بحفظ أي منتجات في المفضلة بعد. تصفح المتجر واضغط على أيقونة القلب لحفظها.') }}</p>
 </div>
 @endif
 </div>

 {{-- TAB 4: Security & Password --}}
 <div x-show="activeTab === 'security'" style="display: none;" class="space-y-5 sm:space-y-6">
 
 {{-- Google OAuth Status Box --}}
 @if($user->google_id)
 <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
 <div class="flex items-center gap-3">
 <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white p-2 shadow-xs shrink-0 flex items-center justify-center">
 <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.665-5.17 3.665-9.17Z"/><path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24Z"/><path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.14-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15Z"/><path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98Z"/></svg>
 </div>
 <div>
 <h4 class="text-xs font-bold text-emerald-900">{{ __('حسابك مربوط بتسجيل الدخول السريع عبر Google') }}</h4>
 <p class="text-[10px] sm:text-[11px] text-emerald-700 mt-0.5">{{ __('يمكنك تسجيل الدخول بنقرة واحدة في أي وقت باستخدام حساب جوجل الخاص بك.') }}</p>
 </div>
 </div>
 <span class="px-2.5 py-1 rounded-full bg-emerald-600 text-white text-[9px] sm:text-[10px] font-bold shrink-0 self-start sm:self-auto">
 {{ __('مفعل') }}
 </span>
 </div>
 @endif

 {{-- Update Password Box --}}
 <div class="bg-white border border-[#EADBCC] rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xs">
 <div class="pb-4 sm:pb-5 mb-5 sm:mb-6 border-b border-[#EADBCC]">
 <h2 class="text-base sm:text-lg font-black text-[#18181B] flex items-center gap-2">
 <span></span>
 <span>{{ __('تغيير وتحديث كلمة المرور') }}</span>
 </h2>
 <p class="text-[11px] sm:text-xs text-[#71717A] mt-1">{{ __('احرص على استخدام كلمة مرور قوية تتكون من 8 أحرف على الأقل لحماية حسابك.') }}</p>
 </div>

 @if ($errors->updatePassword->any())
 <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
 <ul class="list-disc list-inside space-y-1">
 @foreach ($errors->updatePassword->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <form method="POST" action="{{ route('password.update') }}" class="space-y-4 sm:space-y-5">
 @csrf
 @method('put')

 <div>
 <label for="update_password_current_password" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('كلمة المرور الحالية') }} <span class="text-rose-500">*</span></label>
 <input type="password" 
 id="update_password_current_password" 
 name="current_password" 
 autocomplete="current-password"
 required
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium">
 </div>

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
 <div>
 <label for="update_password_password" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('كلمة المرور الجديدة') }} <span class="text-rose-500">*</span></label>
 <input type="password" 
 id="update_password_password" 
 name="password" 
 autocomplete="new-password"
 required
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium">
 </div>

 <div>
 <label for="update_password_password_confirmation" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('تأكيد كلمة المرور الجديدة') }} <span class="text-rose-500">*</span></label>
 <input type="password" 
 id="update_password_password_confirmation" 
 name="password_confirmation" 
 autocomplete="new-password"
 required
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-[#C5A059] focus:bg-white font-medium">
 </div>
 </div>

 <div class="flex items-center justify-end pt-2 sm:pt-3">
 <button type="submit" 
 class="w-full sm:w-auto px-8 py-3 rounded-full bg-[#18181B] hover:bg-black text-[#EADBCC] text-xs font-bold transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
 <span></span>
 <span>{{ __('تحديث كلمة المرور') }}</span>
 </button>
 </div>
 </form>
 </div>

 {{-- Danger Zone: Delete Account --}}
 <div class="bg-white border border-rose-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 md:p-8 shadow-xs" x-data="{ openDeleteModal: false }">
 <div class="pb-4 border-b border-rose-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
 <div>
 <h3 class="text-xs sm:text-sm font-bold text-rose-600 flex items-center gap-2">
 <span></span>
 <span>{{ __('منطقة الخطر: حذف الحساب نهائياً') }}</span>
 </h3>
 <p class="text-[10px] sm:text-[11px] text-gray-500 mt-1">{{ __('بمجرد حذف حسابك، سيتم مسح كافة بياناتك وسجلاتك بشكل نهائي وغير قابل للاسترجاع.') }}</p>
 </div>
 <button type="button" 
 @click="openDeleteModal = true" 
 class="px-4 py-2 rounded-full border border-rose-300 text-rose-600 hover:bg-rose-50 text-xs font-bold transition-colors self-start sm:self-auto">
 {{ __('حذف الحساب...') }}
 </button>
 </div>

 {{-- Delete Confirmation Modal --}}
 <div x-show="openDeleteModal" 
 style="display: none;"
 class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-xs"
 x-cloak>
 <div @click.away="openDeleteModal = false" class="bg-white rounded-2xl sm:rounded-3xl p-5 sm:p-8 max-w-md w-full border border-rose-200 shadow-2xl space-y-4 sm:space-y-5 text-start">
 <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl sm:text-2xl mx-auto">
 
 </div>
 <div class="text-center">
 <h4 class="text-sm sm:text-base font-black text-[#18181B]">{{ __('هل أنت متأكد من رغبتك في حذف الحساب؟') }}</h4>
 <p class="text-[11px] sm:text-xs text-gray-500 mt-1 leading-relaxed">{{ __('يرجى إدخال كلمة المرور الحالية لتأكيد رغبتك في حذف حسابك نهائياً.') }}</p>
 </div>

 <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
 @csrf
 @method('delete')

 <div>
 <label for="delete_user_password" class="block text-xs font-bold text-[#18181B] mb-1.5">{{ __('كلمة المرور الحالية') }}</label>
 <input type="password" 
 id="delete_user_password" 
 name="password" 
 required
 placeholder="{{ __('أدخل كلمة مرورك للتأكيد') }}"
 class="w-full px-3.5 sm:px-4 py-2.5 text-xs rounded-xl bg-[#F8F6F2] border border-[#EADBCC] text-[#18181B] focus:outline-none focus:ring-2 focus:ring-rose-500 font-medium">
 </div>

 <div class="flex items-center gap-3 pt-2">
 <button type="button" 
 @click="openDeleteModal = false" 
 class="w-1/2 py-2.5 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-bold transition-colors">
 {{ __('إلغاء') }}
 </button>
 <button type="submit" 
 class="w-1/2 py-2.5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-colors shadow-sm">
 {{ __('نعم، احذف الحساب') }}
 </button>
 </div>
 </form>
 </div>
 </div>
 </div>
 </div>

 </div>
 </div>

 </div>
</div>
@endsection

