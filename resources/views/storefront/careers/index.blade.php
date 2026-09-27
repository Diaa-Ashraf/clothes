{{-- resources/views/storefront/careers/index.blade.php --}}
@extends('layouts.storefront')

@section('title', 'وظائف ألبان وسوبرماركت التوحيد | انضم لفريقنا في العامرية')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-8">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

 <!-- Breadcrumbs -->
 <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">وظائف التوحيد</span>
 </nav>

 <!-- Hero Section -->
 <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-900 via-primary-800 to-emerald-950 text-white p-6 sm:p-12 shadow-xl">
 <div class="relative z-10 max-w-2xl space-y-4">
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent-500/20 text-accent-300 border border-accent-400/30 text-xs font-bold">
 <svg class="w-3.5 h-3.5 text-accent-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
 </svg>
 <span>بيئة عمل محفزة ومستقرة</span>
 </div>
 <h1 class="text-2xl sm:text-4xl font-black leading-tight tracking-tight">
 انضم إلى عائلة ألبان وسوبرماركت التوحيد بالعامرية
 </h1>
 <p class="text-xs sm:text-sm text-cream-muted leading-relaxed">
 نبحث دائماً عن الكفاءات الطموحة والمخلصة للانضمام لفريقنا في فروعنا بالعامرية. نوفر فرص عمل واعدة، رواتب مجزية، بيئة عمل قائمة على الاحترام والتطوير المهني المستمر.
 </p>
 <div class="pt-2">
 <a href="#apply-form" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-accent-500 hover:bg-accent-600 text-primary-950 text-xs font-black transition-all shadow-md">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
 </svg>
 <span>قدّم سيرتك الذاتية الآن</span>
 </a>
 </div>
 </div>

 <!-- Background Shapes -->
 <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-accent-500/10 rounded-full blur-3xl pointer-events-none"></div>
 <div class="absolute -top-16 -right-16 w-64 h-64 bg-primary-600/30 rounded-full blur-3xl pointer-events-none"></div>
 </div>

 <!-- Benefits Grid -->
 <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
 <div class="bg-white rounded-2xl border border-cream p-5 text-center space-y-2.5 shadow-2xs">
 <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
 </svg>
 </div>
 <h3 class="text-xs font-bold text-charcoal-heading">رواتب وحوافز مجزية</h3>
 <p class="text-[11px] text-charcoal-muted">مرتبات تنافسية مع مكافآت انتظام وإنتاجية شهرية</p>
 </div>
 <div class="bg-white rounded-2xl border border-cream p-5 text-center space-y-2.5 shadow-2xs">
 <div class="w-10 h-10 mx-auto rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
 </svg>
 </div>
 <h3 class="text-xs font-bold text-charcoal-heading">استقرار وتأمينات</h3>
 <p class="text-[11px] text-charcoal-muted">عقود رسمية، تأمينات اجتماعية، وبيئة عمل آمنة</p>
 </div>
 <div class="bg-white rounded-2xl border border-cream p-5 text-center space-y-2.5 shadow-2xs">
 <div class="w-10 h-10 mx-auto rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
 </svg>
 </div>
 <h3 class="text-xs font-bold text-charcoal-heading">تدرج وترقي وظيفي</h3>
 <p class="text-[11px] text-charcoal-muted">أولوية الترقي الداخلي وإدارة الفروع للمجتهدين</p>
 </div>
 <div class="bg-white rounded-2xl border border-cream p-5 text-center space-y-2.5 shadow-2xs">
 <div class="w-10 h-10 mx-auto rounded-xl bg-orange-50 text-orange-700 flex items-center justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
 </svg>
 </div>
 <h3 class="text-xs font-bold text-charcoal-heading">روح الأسرة الواحدة</h3>
 <p class="text-[11px] text-charcoal-muted">فريق عمل متعاون ومحترم يساند بعضه في كل الأوقات</p>
 </div>
 </div>

 <!-- Available Roles Section -->
 <div class="space-y-6">
 <div class="flex items-center justify-between border-b border-gray-200 pb-3">
 <div class="flex items-center gap-2.5">
 <div class="w-8 h-8 rounded-xl bg-primary-50 text-primary-800 flex items-center justify-center font-bold text-sm">
 
 </div>
 <div>
 <h2 class="text-lg sm:text-xl font-black text-charcoal-heading">الوظائف المتاحة حالياً</h2>
 <p class="text-xs text-charcoal-muted">اختر الوظيفة المناسبة لك واضغط تقديم لتعبئة النموذج</p>
 </div>
 </div>
 </div>

 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
 @foreach($openPositions as $position)
 <div class="bg-white rounded-2xl border border-cream hover:border-primary-300 p-5 shadow-xs hover:shadow-card-hover transition-all flex flex-col justify-between space-y-4">
 <div class="space-y-2.5">
 <div class="flex items-start justify-between gap-2">
 <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary-50 text-primary-900 border border-primary-100">
 {{ $position['department'] }}
 </span>
 <span class="text-[11px] text-charcoal-muted font-medium">
 {{ $position['type'] }}
 </span>
 </div>
 <h3 class="text-base font-bold text-charcoal-heading">{{ $position['title'] }}</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed">
 {{ $position['description'] }}
 </p>
 </div>

 <button type="button" 
 onclick="selectJobPosition('{{ $position['title'] }}')"
 class="w-full py-2.5 px-4 rounded-xl bg-gray-50 hover:bg-primary-800 text-charcoal hover:text-white border border-gray-200 hover:border-primary-800 text-xs font-bold transition-all text-center flex items-center justify-center gap-1.5 cursor-pointer">
 <span>قدّم لهذه الوظيفة</span>
 <span>←</span>
 </button>
 </div>
 @endforeach
 </div>
 </div>

 <!-- Application Form Card -->
 <div id="apply-form" class="bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-10 max-w-3xl mx-auto space-y-8">
 <div class="text-center space-y-2">
 <span class="inline-block p-3 rounded-2xl bg-primary-50 text-primary-800 text-2xl"></span>
 <h2 class="text-xl sm:text-2xl font-black text-charcoal-heading">نموذج التقديم للوظائف</h2>
 <p class="text-xs text-charcoal-muted">املأ البيانات بدقة وأرفق سيرتك الذاتية وسيتم مراجعتها من إدارة التوظيف</p>
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

 <form action="{{ route('careers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
 @csrf

 <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
 <!-- Full Name -->
 <div class="space-y-1.5">
 <label for="name" class="block text-xs font-bold text-charcoal-heading">
 الاسم بالكامل <span class="text-rose-500">*</span>
 </label>
 <input type="text" 
 name="name" 
 id="name" 
 value="{{ old('name', auth()->user()?->name) }}"
 required
 placeholder="مثال: أحمد محمد علي"
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 </div>

 <!-- Phone Number -->
 <div class="space-y-1.5">
 <label for="phone" class="block text-xs font-bold text-charcoal-heading">
 رقم الهاتف المحمول (مصر) <span class="text-rose-500">*</span>
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

 <!-- Position -->
 <div class="space-y-1.5">
 <label for="position" class="block text-xs font-bold text-charcoal-heading">
 الوظيفة المتقدم إليها <span class="text-rose-500">*</span>
 </label>
 <input type="text" 
 name="position" 
 id="position" 
 value="{{ old('position') }}"
 required
 placeholder="مثال: كاشير / بائع ألبان"
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 </div>
 </div>

 <!-- Preferred Branch -->
 <div class="space-y-1.5">
 <label for="branch_id" class="block text-xs font-bold text-charcoal-heading">
 الفرع المفضل للعمل به بالعامرية <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <select name="branch_id" id="branch_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal bg-white focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">
 <option value="">أي فرع متاح بالعامرية</option>
 @foreach($branches as $branch)
 <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
 {{ $branch->name }}
 </option>
 @endforeach
 </select>
 </div>

 <!-- CV File Upload -->
 <div class="space-y-1.5">
 <label class="block text-xs font-bold text-charcoal-heading">
 ملف السيرة الذاتية (CV) <span class="text-rose-500">*</span>
 </label>
 <div class="border-2 border-dashed border-gray-200 hover:border-primary-500 rounded-2xl p-6 text-center transition-colors bg-gray-50/50 relative">
 <input type="file" 
 name="cv_file" 
 id="cv_file" 
 required
 accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
 onchange="previewCvFileName(this)"
 class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
 <div class="space-y-2 pointer-events-none">
 <span class="text-3xl block"></span>
 <span id="cvFileName" class="text-xs font-bold text-primary-800 block">
 اضغط هنا لاختيار ملف السيرة الذاتية
 </span>
 <span class="text-[11px] text-charcoal-muted block">
 يُقبل ملفات PDF أو Word أو صور بحد أقصى 10 ميجابايت
 </span>
 </div>
 </div>
 </div>

 <!-- Message / Experience Notes -->
 <div class="space-y-1.5">
 <label for="message" class="block text-xs font-bold text-charcoal-heading">
 نبذة عن خبراتك السابقة أو ملاحظات إضافية <span class="text-charcoal-muted font-normal">(اختياري)</span>
 </label>
 <textarea name="message" 
 id="message" 
 rows="3" 
 placeholder="اذكر بإيجاز خبراتك السابقة وسنوات العمل في السوبرماركت أو المحلات..."
 class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-xs text-charcoal focus:border-primary-800 focus:ring-1 focus:ring-primary-800 transition-colors">{{ old('message') }}</textarea>
 </div>

 <!-- Submit Button -->
 <button type="submit" 
 class="w-full py-3.5 px-6 rounded-2xl bg-primary-800 hover:bg-primary-900 text-white text-xs font-bold transition-all shadow-sm hover:shadow flex items-center justify-center gap-2 cursor-pointer">
 <span></span>
 <span>إرسال طلب التوظيف</span>
 </button>
 </form>
 </div>

 </div>
</div>

@push('scripts')
<script>
 function selectJobPosition(title) {
 const input = document.getElementById('position');
 if (input) {
 input.value = title;
 document.getElementById('apply-form').scrollIntoView({ behavior: 'smooth' });
 input.focus();
 }
 }

 function previewCvFileName(input) {
 const label = document.getElementById('cvFileName');
 if (input.files && input.files[0]) {
 label.textContent = ` تم اختيار: ${input.files[0].name}`;
 label.classList.add('text-emerald-700');
 label.classList.remove('text-primary-800');
 }
 }
</script>
@endpush
@endsection
