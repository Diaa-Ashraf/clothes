{{-- resources/views/storefront/about.blade.php --}}
@extends('layouts.storefront')

@section('title', 'قصة دار ساهارا أتيليه | الفخامة الصحراوية والأناقة الخالدة')

@section('content')
<div class="bg-white min-h-screen py-10" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- مسار التنقل -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#AC9653] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">قصة الأتيليه</span>
        </nav>

        <!-- البانر الرئيسي لحكاية الأتيليه -->
        <div class="relative overflow-hidden bg-gradient-to-l from-[#AC9653] via-[#BDAB75] to-[#C2B280] text-white p-8 sm:p-16 border border-[#BDAB75]/40 shadow-xs">
            <div class="relative z-10 max-w-3xl space-y-5 text-right">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/15 backdrop-blur-xs border border-white/20 text-white text-[11px] font-bold">
                    <span>✦</span> حكاية الحرفية والهوية الصحراوية
                </div>
                <h1 class="text-3xl sm:text-5xl font-serif-luxury font-bold leading-tight">
                    ساهارا أتيليه..<br>
                    حيث تلتقي نقاوة الصحراء مع إتقان الحياكة الإيطالية
                </h1>
                <p class="text-xs sm:text-sm text-white/95 leading-relaxed max-w-2xl font-light">
                    وُلدت دار ساهارا أتيليه في قلب الرياض كاستجابة للشغف بالأناقة الهادئة (Quiet Luxury). فلسفتنا بسيطة وعميقة: أقمشة نبيلة طبيعية 100%، ألوان مستوحاة من تموجات الرمال والذهب، وتفصيل يدوي دقيق يمنح القطعة خلوداً يتجاوز تقلبات الموضة العابرة.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-3">
                    <a href="{{ route('site.products') }}" class="px-8 py-3.5 bg-[#CBA367] hover:bg-[#b88f55] text-white text-xs font-bold transition-all shadow-md">
                        استعراض التشكيلات الحصرية
                    </a>
                    <a href="{{ route('contact') }}" class="px-7 py-3.5 bg-transparent hover:bg-white text-white hover:text-[#2B2118] border border-white text-xs font-bold transition-all">
                        حجز موعد استشارة وتفصيل خاص
                    </a>
                </div>
            </div>

            <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
        </div>

        <!-- أرقام ومعايير التميز في ساهارا أتيليه -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="bg-[#FDFBF7] p-8 border border-[#BDAB75]/30 space-y-2">
                <span class="text-3xl sm:text-4xl font-serif-luxury font-bold text-[#AC9653] block">50</span>
                <span class="text-xs font-bold text-[#2B2118] block">قطعة فقط لكل تصميم</span>
                <span class="text-[11px] text-[#8F7B63] block">لضمان التفرد والندرة</span>
            </div>
            <div class="bg-[#FDFBF7] p-8 border border-[#BDAB75]/30 space-y-2">
                <span class="text-3xl sm:text-4xl font-serif-luxury font-bold text-[#CBA367] block">100%</span>
                <span class="text-xs font-bold text-[#2B2118] block">خامات نبيلة طبيعية</span>
                <span class="text-[11px] text-[#8F7B63] block">كتان بلجيكي وصوف جملي نقي</span>
            </div>
            <div class="bg-[#FDFBF7] p-8 border border-[#BDAB75]/30 space-y-2">
                <span class="text-3xl sm:text-4xl font-serif-luxury font-bold text-[#AC9653] block">الرياض</span>
                <span class="text-xs font-bold text-[#2B2118] block">أتيليه التفصيل والحياكة</span>
                <span class="text-[11px] text-[#8F7B63] block">حي العليا للأزياء الفاخرة</span>
            </div>
            <div class="bg-[#FDFBF7] p-8 border border-[#BDAB75]/30 space-y-2">
                <span class="text-3xl sm:text-4xl font-serif-luxury font-bold text-[#CBA367] block">24 ساعة</span>
                <span class="text-xs font-bold text-[#2B2118] block">توصيل بالقفازات البيضاء</span>
                <span class="text-[11px] text-[#8F7B63] block">شحن مخصص داخل المملكة</span>
            </div>
        </div>

        <!-- فصول الحرفية الثلاثة -->
        <div class="space-y-12">
            <div class="text-center">
                <span class="text-xs font-bold tracking-widest text-[#AC9653] uppercase block mb-1">فصول التميز</span>
                <h2 class="text-2xl sm:text-3xl font-serif-luxury font-bold text-[#2B2118]">ثلاث ركائز تبني هويتنا</h2>
                <div class="w-12 h-0.5 bg-[#CBA367] mx-auto mt-3"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- الفصل الأول -->
                <div class="p-8 bg-[#FDFBF7] border border-[#BDAB75]/30 space-y-4 text-right">
                    <span class="text-xs font-mono font-bold text-[#AC9653]">الفصل الأول</span>
                    <h3 class="text-lg font-serif-luxury font-bold text-[#2B2118]">الكتان البلجيكي العضوي</h3>
                    <p class="text-xs text-[#8F7B63] leading-relaxed">
                        نستورد ألياف الكتان مباشرة من سهول فلاندرز ببلجيكا، حيث تُنسج يدوياً بأعلى مواصفات النقاء، مما يمنح قمصاننا وبليزراتنا تلك النعومة والتهوية والانسيابية المتفردة.
                    </p>
                </div>

                <!-- الفصل الثاني -->
                <div class="p-8 bg-[#FDFBF7] border border-[#BDAB75]/30 space-y-4 text-right">
                    <span class="text-xs font-mono font-bold text-[#AC9653]">الفصل الثاني</span>
                    <h3 class="text-lg font-serif-luxury font-bold text-[#2B2118]">صوف الجمل الإيطالي البكر</h3>
                    <p class="text-xs text-[#8F7B63] leading-relaxed">
                        نعتمد الصوف الجملي البكر ثنائي الأوجه المحاك في أرقى المغازل الإيطالية، ليمنح معاطف الأتيليه خفة استثنائية وعزلاً دافئاً يلائم هواء الصحراء المسائي.
                    </p>
                </div>

                <!-- الفصل الثالث -->
                <div class="p-8 bg-[#FDFBF7] border border-[#BDAB75]/30 space-y-4 text-right">
                    <span class="text-xs font-mono font-bold text-[#AC9653]">الفصل الثالث</span>
                    <h3 class="text-lg font-serif-luxury font-bold text-[#2B2118]">الحياكة اليدوية الدقيقة</h3>
                    <p class="text-xs text-[#8F7B63] leading-relaxed">
                        في أتيليه الرياض، يقضي خياطونا المحترفون عشرات الساعات في تفصيل كل قطعة بإنهاء يدوي كامل عند الطيات والياقات والأزرار الطبيعية، لتكون النتيجة تحفة فنية لا تضاهى.
                    </p>
                </div>
            </div>
        </div>

        <!-- رسالة من المدير الإبداعي -->
        <div class="p-10 sm:p-14 bg-[#C2B280]/15 border border-[#BDAB75]/30 text-center space-y-5">
            <span class="text-xs font-bold tracking-widest text-[#AC9653] uppercase block">فلسفة الدار</span>
            <blockquote class="text-xl sm:text-2xl font-serif-luxury font-bold text-[#2B2118] max-w-3xl mx-auto leading-relaxed">
                “الأناقة الحقيقية لا تصرخ؛ بل تهمس كصمت الكثبان الرملية تحت ضوء القمر. نحن لا نصنع ملابس لموسم، بل نبتكر قطعاً تزداد قيمة مع كل عام ترتديها فيه.”
            </blockquote>
            <div class="text-xs text-[#8F7B63] font-medium pt-2">
                — ساهارا أتيليه، الرياض
            </div>
        </div>

    </div>
</div>
@endsection
