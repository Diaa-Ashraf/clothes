@extends('layouts.site')

@section('title', __('تواصل معنا') . ' | ' . settings('site_name', 'Regalest Store'))

@section('content')
@php
 $storePhone = settings('whatsapp_number', '963999999999');
 $storeEmail = settings('contact_email', 'concierge@regalest.store');
 $storeAddress = settings('store_address', 'دمشق / حلب — خدمة التوصيل الفوري لكافة المحافظات');
 $waContactUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $storePhone) . "?text=" . urlencode("مرحباً، أود التواصل مع خدمة العملاء بخصوص استفسار أو طلب خاص ");
@endphp

<div class="bg-[#F8F9FA] text-[#18181B] min-h-[85vh] py-8 sm:py-12" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
 <div class="w-full max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
 
 {{-- Breadcrumbs --}}
 <nav class="flex items-center gap-2 text-xs text-[#71717A] mb-6 sm:mb-8 font-sans overflow-x-auto whitespace-nowrap pb-1">
 <a href="{{ route('site.home') }}" class="hover:text-[#C5A059] transition-colors">{{ __('الرئيسية') }}</a>
 <span class="text-gray-300">/</span>
 <span class="text-[#18181B] font-semibold">{{ __('تواصل معنا') }}</span>
 </nav>

 {{-- Main Contact Header Banner --}}
 <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 sm:p-10 mb-8 sm:mb-12 shadow-xs text-center relative overflow-hidden">
 <div class="max-w-2xl mx-auto relative z-10 space-y-3">
 <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-[#C5A059]/10 text-[#C5A059] border border-[#C5A059]/20">
 {{ __('خدمة كونسيرج Regalest الملكية') }}
 </span>
 <h1 class="text-2xl sm:text-4xl font-extrabold text-[#18181B] tracking-tight">
 {{ __('يسعدنا دائماً تواصلكم والإجابة عن استفساراتكم') }}
 </h1>
 <p class="text-xs sm:text-sm text-[#71717A] leading-relaxed">
 {{ __('فريق خدمة العملاء جاهز على مدار الساعة لتقديم المساعدة، حجز الساعات النادرة، ومتابعة شحناتكم بأعلى درجات العناية.') }}
 </p>
 </div>
 </div>

 {{-- Contact Cards Grid --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-12">
 
 {{-- WhatsApp VIP Support --}}
 <a href="{{ $waContactUrl }}" target="_blank" class="group bg-white hover:bg-[#FAF8F5] border border-[#E5E7EB] hover:border-[#C5A059] rounded-3xl p-6 text-center transition-all duration-300 shadow-xs hover:shadow-xl hover:-translate-y-1 block">
 <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-[#25D366] border border-emerald-100 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform">
 
 </div>
 <h3 class="font-bold text-base text-[#18181B] mb-1.5">{{ __('واتساب المباشر') }}</h3>
 <p class="text-xs text-[#71717A] mb-3">{{ __('رد فوري وتأكيد الطلبات') }}</p>
 <span class="text-xs font-bold text-[#25D366] group-hover:underline block" dir="ltr">{{ $storePhone }}</span>
 </a>

 {{-- Phone Calls --}}
 <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 text-center shadow-xs">
 <div class="w-14 h-14 rounded-2xl bg-amber-50 text-[#C5A059] border border-amber-100 flex items-center justify-center text-2xl mx-auto mb-4">
 
 </div>
 <h3 class="font-bold text-base text-[#18181B] mb-1.5">{{ __('الاتصال الهاتفي') }}</h3>
 <p class="text-xs text-[#71717A] mb-3">{{ __('متاح يومياً من 10 ص - 11 م') }}</p>
 <span class="text-xs font-bold text-[#18181B] block" dir="ltr">{{ $storePhone }}</span>
 </div>

 {{-- Address / Delivery --}}
 <div class="bg-white border border-[#E5E7EB] rounded-3xl p-6 text-center shadow-xs">
 <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center text-2xl mx-auto mb-4">
 
 </div>
 <h3 class="font-bold text-base text-[#18181B] mb-1.5">{{ __('مقر المعاينة والتوصيل') }}</h3>
 <p class="text-xs text-[#71717A] mb-3">{{ __('شحن مؤمن لجميع المحافظات') }}</p>
 <span class="text-xs font-semibold text-[#18181B] block">{{ $storeAddress }}</span>
 </div>

 {{-- Email Support --}}
 <a href="mailto:{{ $storeEmail }}" class="group bg-white hover:bg-[#FAF8F5] border border-[#E5E7EB] hover:border-[#C5A059] rounded-3xl p-6 text-center transition-all duration-300 shadow-xs hover:shadow-xl hover:-translate-y-1 block">
 <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 border border-purple-100 flex items-center justify-center text-2xl mx-auto mb-4 group-hover:scale-110 transition-transform">
 
 </div>
 <h3 class="font-bold text-base text-[#18181B] mb-1.5">{{ __('البريد الإلكتروني') }}</h3>
 <p class="text-xs text-[#71717A] mb-3">{{ __('للاستفسارات الرسمية والشركات') }}</p>
 <span class="text-xs font-bold text-[#18181B] group-hover:text-[#C5A059] truncate block" dir="ltr">{{ $storeEmail }}</span>
 </a>

 </div>

 {{-- Direct WhatsApp CTA Action Card --}}
 <div class="bg-[#18181B] text-white rounded-3xl p-8 sm:p-12 shadow-xl border border-white/10 flex flex-col md:flex-row items-center justify-between gap-6">
 <div class="space-y-2 text-center md:text-start">
 <span class="text-xs font-bold text-[#C5A059] tracking-wider uppercase"> {{ __('طلب خاص أو تخصيص هدية؟') }}</span>
 <h2 class="text-xl sm:text-2xl font-bold text-white">{{ __('تحدث مباشرة مع مستشار المقتنيات الملكية') }}</h2>
 <p class="text-xs sm:text-sm text-gray-300 max-w-xl">
 {{ __('يمكننا تجهيز طلبات الهدايا مع علب ملكية مخصصة، كروت تهنئة فاخرة، وخدمة تسليم مباشر للشخص المهدى إليه.') }}
 </p>
 </div>
 <a href="{{ $waContactUrl }}" 
 target="_blank" 
 class="px-8 py-4 rounded-full bg-[#25D366] hover:bg-[#1EBE5D] text-white font-bold text-sm transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95 flex items-center gap-2 shrink-0">
 <span class="text-lg"></span>
 <span>{{ __('بدء محادثة واتساب الآن') }}</span>
 </a>
 </div>

 </div>
</div>
@endsection
