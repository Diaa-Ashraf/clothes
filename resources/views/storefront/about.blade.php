{{-- resources/views/storefront/about.blade.php --}}
@extends('layouts.storefront')

@section('title', 'من نحن | قصة ألبان وسوبرماركت التوحيد بالعامرية')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-8">
 <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

 <!-- Breadcrumbs -->
 <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
 <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
 <span>/</span>
 <span class="text-charcoal-heading font-bold">من نحن</span>
 </nav>

 <!-- Hero Section -->
 <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-950 via-primary-900 to-emerald-900 text-white p-8 sm:p-14 shadow-xl">
 <div class="relative z-10 max-w-3xl space-y-4">
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent-500/20 text-accent-300 border border-accent-400/30 text-xs font-bold">
 <span></span> أصالة الألبان الطبيعية وثقة أهالي العامرية
 </div>
 <h1 class="text-3xl sm:text-5xl font-black leading-tight tracking-tight">
 ألبان وسوبرماركت التوحيد.. جودة المزرعة تصل إلى مائدتك
 </h1>
 <p class="text-sm sm:text-base text-cream-muted leading-relaxed max-w-2xl">
 منذ انطلاقتنا في قلب العامرية بالإسكندرية، حملنا عهداً واحداً: تقديم منتجات ألبان طازجة 100%، خالية من أي مواد حافظة، جنباً إلى جنب مع أفضل تشكيلة بقالة متكاملة تلبّي احتياجات كل بيت مصري بأفضل الأسعار.
 </p>
 <div class="flex flex-wrap items-center gap-3 pt-3">
 <a href="{{ route('shop') }}" class="px-6 py-3 rounded-2xl bg-accent-500 hover:bg-accent-600 text-primary-950 text-xs font-black transition-all shadow-md flex items-center gap-2">
 <span></span>
 <span>تسوق منتجاتنا الآن</span>
 </a>
 <a href="{{ route('branches.index') }}" class="px-6 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold transition-all flex items-center gap-2">
 <span></span>
 <span>تعرف على فروعنا الأربعة</span>
 </a>
 </div>
 </div>

 <!-- Background subtle pattern -->
 <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-accent-500/10 rounded-full blur-3xl pointer-events-none"></div>
 <div class="absolute -top-24 -right-24 w-80 h-80 bg-primary-600/20 rounded-full blur-3xl pointer-events-none"></div>
 </div>

 <!-- Numbers & Stats Grid -->
 <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
 <div class="bg-white rounded-3xl border border-cream p-6 text-center space-y-2 shadow-xs hover:border-primary-300 transition-colors">
 <span class="text-3xl sm:text-4xl font-black text-primary-800 block">15+</span>
 <span class="text-xs font-bold text-charcoal-heading block">عاماً من الخبرة والريادة</span>
 <span class="text-[11px] text-charcoal-muted block">في خدمة أهالي العامرية</span>
 </div>
 <div class="bg-white rounded-3xl border border-cream p-6 text-center space-y-2 shadow-xs hover:border-primary-300 transition-colors">
 <span class="text-3xl sm:text-4xl font-black text-accent-500 block">4</span>
 <span class="text-xs font-bold text-charcoal-heading block">فروع رئيسية</span>
 <span class="text-[11px] text-charcoal-muted block">تغطي كل زوايا العامرية وكينج مريوط</span>
 </div>
 <div class="bg-white rounded-3xl border border-cream p-6 text-center space-y-2 shadow-xs hover:border-primary-300 transition-colors">
 <span class="text-3xl sm:text-4xl font-black text-emerald-600 block">10,000+</span>
 <span class="text-xs font-bold text-charcoal-heading block">طلب شهرياً</span>
 <span class="text-[11px] text-charcoal-muted block">بين زيارات الفروع والطلبات أونلاين</span>
 </div>
 <div class="bg-white rounded-3xl border border-cream p-6 text-center space-y-2 shadow-xs hover:border-primary-300 transition-colors">
 <span class="text-3xl sm:text-4xl font-black text-primary-900 block">100%</span>
 <span class="text-xs font-bold text-charcoal-heading block">طازج يومياً</span>
 <span class="text-[11px] text-charcoal-muted block">ألبان وجبن وقشطة بلدي أصيلة</span>
 </div>
 </div>

 <!-- The Story and Values Grid -->
 <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
 <div class="space-y-4">
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-800 text-xs font-bold border border-primary-200">
 حكايتنا ورؤيتنا
 </div>
 <h2 class="text-2xl sm:text-3xl font-black text-charcoal-heading leading-tight">
 لماذا يختار أهالي العامرية ألبان وسوبرماركت التوحيد؟
 </h2>
 <div class="text-xs sm:text-sm text-charcoal-muted leading-relaxed space-y-3">
 <p>
 بدأنا بفكرة بسيطة ومقدسة: أن نوفر لأولادنا ولأسر العامرية حليباً وجبناً كما ينبغي أن يكون، نقياً، بخيره، من خير مزارعنا بدون أي غش تجاري أو تخفيف أو إضافات.
 </p>
 <p>
 مع مرور السنوات ومع ثقة عملائنا الغالية، توسعنا لنفتتح 4 فروع متكاملة ونطور خدمات السوبرماركت لتشمل جميع مستلزمات البقالة، الحلويات، المخبوزات، والعطارة، موفرين تجربة تسوق راقية وشاملة.
 </p>
 <p>
 اليوم، ومع إطلاق منصتنا الإلكترونية، أصبح بإمكانك طلب كل ما تحتاجه بضغطة زر واحدة لتصلك طلباتك طازجة حتى باب منزلك مع أسرع دليفري في العامرية.
 </p>
 </div>
 </div>

 <!-- Pillars Cards -->
 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
 <div class="bg-white rounded-2xl border border-cream p-5 space-y-2 shadow-xs">
 <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-800 flex items-center justify-center text-lg">
 
 </div>
 <h3 class="text-sm font-bold text-charcoal-heading">لبن بلدي بخيره 100%</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed">
 يصلنا يومياً من المزارع مباشرة، مبستر ومحفوظ بعناية فائقة لضمان أعلى فائدة غذائية وطعم أصيل.
 </p>
 </div>

 <div class="bg-white rounded-2xl border border-cream p-5 space-y-2 shadow-xs">
 <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
 
 </div>
 <h3 class="text-sm font-bold text-charcoal-heading">أجبان وقشطة وزبدة طبيعية</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed">
 تشكيلة واسعة من الجبن الأبيض القديم، الرومي المعتق، القشطة البلدي، والموتزاريلا الطبيعية الممتازة.
 </p>
 </div>

 <div class="bg-white rounded-2xl border border-cream p-5 space-y-2 shadow-xs">
 <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-lg">
 
 </div>
 <h3 class="text-sm font-bold text-charcoal-heading">توصيل سريع مجهز</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed">
 سيارات ودراجات مجهزة بحافظات تبريد للحفاظ على سلامة ونضارة المنتجات أثناء التوصيل في حر الصيف وبرد الشتاء.
 </p>
 </div>

 <div class="bg-white rounded-2xl border border-cream p-5 space-y-2 shadow-xs">
 <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
 
 </div>
 <h3 class="text-sm font-bold text-charcoal-heading">أسعار عادلة وأمانة تامة</h3>
 <p class="text-xs text-charcoal-muted leading-relaxed">
 نؤمن بأن البركة في الأمانة، لذلك نحرص دوماً على موازين إلكترونية دقيقة وأسعار تنافسية تناسب كل عائلة.
 </p>
 </div>
 </div>
 </div>

 <!-- Branches Quick Teaser -->
 <div class="bg-white rounded-3xl border border-cream p-8 space-y-6 shadow-xs">
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-gray-100 pb-4">
 <div>
 <h3 class="text-lg font-black text-charcoal-heading">فروعنا في خدمتك دائماً</h3>
 <p class="text-xs text-charcoal-muted">4 فروع جاهزة لاستقبالك في العامرية وضواحيها</p>
 </div>
 <a href="{{ route('branches.index') }}" class="text-xs font-bold text-primary-800 hover:text-primary-950 flex items-center gap-1">
 <span>عرض التفاصيل ومواقع الخرائط</span>
 <span>←</span>
 </a>
 </div>

 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
 @foreach($branches as $branch)
 <div class="bg-gray-50/80 rounded-2xl p-4 space-y-2 border border-gray-100">
 <div class="flex items-center gap-2">
 <span class="text-base"></span>
 <h4 class="text-xs font-black text-charcoal-heading">{{ $branch->name }}</h4>
 </div>
 <p class="text-[11px] text-charcoal-muted line-clamp-2 leading-relaxed">
 {{ $branch->address }}
 </p>
 @if($branch->phone)
 <a href="tel:{{ $branch->phone }}" class="text-[11px] font-bold text-primary-800 block hover:underline">
 {{ $branch->phone }}
 </a>
 @endif
 </div>
 @endforeach
 </div>
 </div>

 </div>
</div>
@endsection
