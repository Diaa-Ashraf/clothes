{{-- resources/views/storefront/contact.blade.php --}}
@extends('layouts.storefront')

@section('title', 'اتصل بنا | ألبان وسوبرماركت التوحيد بالعامرية')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-8">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

 <!-- Breadcrumbs -->
 <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">اتصل بنا</span>
 </nav>

 <!-- Hero Header -->
 <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-900 via-primary-800 to-emerald-950 text-white p-6 sm:p-12 shadow-xl text-center space-y-4">
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent-500/20 text-accent-300 border border-accent-400/30 text-xs font-bold mx-auto">
 <svg class="w-3.5 h-3.5 text-accent-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
 </svg>
 <span>نسعد بخدمتكم والاستماع لآرائكم دائماً</span>
 </div>
 <h1 class="text-2xl sm:text-4xl font-black max-w-2xl mx-auto leading-tight">
 تواصل مع إدارة ألبان وسوبرماركت التوحيد
 </h1>
 <p class="text-xs sm:text-sm text-cream-muted max-w-xl mx-auto leading-relaxed">
 سواء كان لديك استفسار عن منتج، متابعة لطلبك، طلب كميات جملة أو شكوى، فريقنا متواجد لخدمتك ومساعدتك في أي وقت.
 </p>
 </div>

 <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
 
 <!-- Left Column: Contact Channels & Branch Direct Numbers (5 cols) -->
 <div class="lg:col-span-5 space-y-6">
 
 <!-- Quick Contact Info Card -->
 <div class="bg-white rounded-3xl border border-cream p-6 shadow-sm space-y-5">
 <h3 class="text-base font-bold text-charcoal-heading border-b border-gray-100 pb-3 flex items-center gap-2">
 <svg class="w-5 h-5 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
 </svg>
 <span>قنوات الاتصال المباشرة</span>
 </h3>

 <!-- Customer Service Phone -->
 <div class="flex items-start gap-3">
 <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-800 flex items-center justify-center text-lg flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
 </svg>
 </div>
 <div>
 <span class="text-[11px] text-charcoal-muted block">خدمة العملاء والطلبات:</span>
 <a href="tel:01000000000" class="text-sm font-black text-primary-800 hover:underline" dir="ltr">
 {{ $settings['site_phone'] ?? '01012345678' }}
 </a>
 </div>
 </div>

 <!-- WhatsApp -->
 <div class="flex items-start gap-3">
 <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
 </svg>
 </div>
 <div>
 <span class="text-[11px] text-charcoal-muted block">واتساب الطلبات السريعة:</span>
 <a href="https://wa.me/201012345678" target="_blank" rel="noopener noreferrer" class="text-sm font-black text-emerald-700 hover:underline" dir="ltr">
 {{ $settings['site_whatsapp'] ?? '+20 101 234 5678' }}
 </a>
 </div>
 </div>

 <!-- Operating Hours -->
 <div class="flex items-start gap-3">
 <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
 </svg>
 </div>
 <div>
 <span class="text-[11px] text-charcoal-muted block">مواعيد استقبال الطلبات:</span>
 <span class="text-xs font-bold text-charcoal-heading">يومياً من 8:00 صباحاً حتى 12:00 منتصف الليل</span>
 </div>
 </div>

 <!-- Region -->
 <div class="flex items-start gap-3">
 <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg flex-shrink-0">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
 </svg>
 </div>
 <div>
 <span class="text-[11px] text-charcoal-muted block">المنطقة والخدمة:</span>
 <span class="text-xs font-bold text-charcoal-heading">العامرية، كينج مريوط، وضواحي غرب الإسكندرية</span>
 </div>
 </div>
 </div>

 <!-- Branches Mini Directory -->
 <div class="bg-white rounded-3xl border border-cream p-6 shadow-sm space-y-4">
 <div class="flex items-center justify-between border-b border-gray-100 pb-3">
 <h3 class="text-sm font-bold text-charcoal-heading flex items-center gap-2">
 <svg class="w-4 h-4 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
 </svg>
 <span>أرقام الفروع المباشرة</span>
 </h3>
 <a href="{{ route('branches.index') }}" class="text-[11px] font-bold text-primary-800 hover:underline">
 عرض الخرائط ←
 </a>
 </div>

 <div class="space-y-3">
 @foreach($branches as $b)
 <div class="p-3 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between gap-3">
 <div class="space-y-0.5">
 <h4 class="text-xs font-bold text-charcoal-heading">{{ $b->name }}</h4>
 <p class="text-[10px] text-charcoal-muted line-clamp-1">{{ $b->address }}</p>
 </div>
 @if($b->phone)
 <a href="tel:{{ $b->phone }}" class="px-3 py-1.5 rounded-xl bg-white border border-gray-200 text-xs font-bold text-primary-800 hover:bg-primary-50 transition-colors flex items-center gap-1.5">
 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
 </svg>
 <span>اتصال</span>
 </a>
 @endif
 </div>
 @endforeach
 </div>
 </div>

 </div>

 <!-- Right Column: Contact Message Form (7 cols) -->
 <div class="lg:col-span-7 bg-white rounded-3xl border border-cream p-6 sm:p-10 shadow-sm space-y-6">
 <div>
 <div class="w-10 h-10 rounded-2xl bg-primary-50 text-primary-800 flex items-center justify-center mb-3">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
 </svg>
 </div>
 <h2 class="text-xl font-black text-charcoal-heading">أرسل لنا رسالة أو استفسار</h2>
 <p class="text-xs text-charcoal-muted mt-1">نسعد باستقبال رسائلك واقتراحاتك، وسيتم مراجعتها من الإدارة والرد عليك سريعاً.</p>
 </div>

 @if(session('success'))
 <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 text-emerald-800 text-xs flex items-center gap-2">
 <span class="text-base"></span>
 <span>{{ session('success') }}</span>
 </div>
 @endif

 @if($errors->any())
 <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 space-y-1 text-rose-700 text-xs">
 <span class="font-bold block">يرجى تصحيح الأخطاء التالية:</span>
 <ul class="list-disc list-inside space-y-0.5">
 @foreach($errors->all() as $err)
 <li>{{ $err }}</li>
 @endforeach
 </ul>
 </div>
 @endif

 <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
 @csrf

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <!-- Name -->
 <div class="space-y-1.5">
 <label for="name" class="block text-xs font-bold text-charcoal-heading">
 الاسم الكريم <span class="text-rose-500">*</span>
 </label>
 <input type="text" 
 name="name" 
 id="name" 
 value="{{ old('name', auth()->user()?->name) }}"
 required
 placeholder="أحمد محمد"
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 </div>

 <!-- Phone -->
 <div class="space-y-1.5">
 <label for="phone" class="block text-xs font-bold text-charcoal-heading">
 رقم الهاتف <span class="text-rose-500">*</span>
 </label>
 <input type="tel" 
 name="phone" 
 id="phone" 
 dir="ltr"
 value="{{ old('phone', auth()->user()?->phone) }}"
 required
 placeholder="01012345678"
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal text-right focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 </div>
 </div>

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <!-- Email -->
 <div class="space-y-1.5">
 <label for="email" class="block text-xs font-bold text-charcoal-heading">
 البريد الإلكتروني <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <input type="email" 
 name="email" 
 id="email" 
 dir="ltr"
 value="{{ old('email', auth()->user()?->email) }}"
 placeholder="example@mail.com"
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal text-right focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 </div>

 <!-- Subject -->
 <div class="space-y-1.5">
 <label for="subject" class="block text-xs font-bold text-charcoal-heading">
 موضوع الرسالة <span class="text-rose-500">*</span>
 </label>
 <select name="subject" id="subject" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal bg-white focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 <option value="">اختر موضوع الرسالة...</option>
 <option value="استفسار عام عن منتجات الألبان والبقالة" {{ old('subject') == 'استفسار عام عن منتجات الألبان والبقالة' ? 'selected' : '' }}>استفسار عام عن المنتجات</option>
 <option value="متابعة أو استفسار عن طلب ديليفري" {{ old('subject') == 'متابعة أو استفسار عن طلب ديليفري' ? 'selected' : '' }}>متابعة طلب دليفري</option>
 <option value="طلب كميات أو أسعار جملة للمطاعم والمحلات" {{ old('subject') == 'طلب كميات أو أسعار جملة للمطاعم والمحلات' ? 'selected' : '' }}>طلب كميات أو توريدات جملة</option>
 <option value="شكوى بخصوص جودة منتج أو خدمة" {{ old('subject') == 'شكوى بخصوص جودة منتج أو خدمة' ? 'selected' : '' }}>شكوى أو ملاحظة</option>
 <option value="اقتراح لتطوير خدمات التوحيد" {{ old('subject') == 'اقتراح لتطوير خدمات التوحيد' ? 'selected' : '' }}>اقتراح للتطوير</option>
 </select>
 </div>
 </div>

 <!-- Message Body -->
 <div class="space-y-1.5">
 <label for="message" class="block text-xs font-bold text-charcoal-heading">
 نص الرسالة <span class="text-rose-500">*</span>
 </label>
 <textarea name="message" 
 id="message" 
 rows="5" 
 required
 placeholder="اكتب استفسارك أو تفاصيل رسالتك هنا..."
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">{{ old('message') }}</textarea>
 </div>

 <!-- Submit Button -->
 <button type="submit" 
 class="w-full py-3.5 px-6 rounded-2xl bg-primary-800 hover:bg-primary-900 text-white text-xs font-bold transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 cursor-pointer">
 <span></span>
 <span>إرسال الرسالة الآن</span>
 </button>
 </form>
 </div>

 </div>

 </div>
</div>
@endsection
