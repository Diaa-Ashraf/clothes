{{-- resources/views/storefront/pages/terms.blade.php --}}
@extends('layouts.storefront')

@section('title', 'الشروط والأحكام | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
@php
    $customTerms = settings('terms_content');
@endphp

<div class="bg-[#FBF6EA] min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#2B2118] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">الشروط والأحكام</span>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-[#EADFC8] p-6 sm:p-10 shadow-sm space-y-8">
            <div class="border-b border-[#EADFC8] pb-6 space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#6B5642] border border-[#EADFC8]">
                    وثيقة الشروط والاستخدام الرسمية
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-[#2B2118]">الشروط والأحكام</h1>
                <p class="text-xs text-[#8F7B63]">آخر تحديث: {{ date('Y') }}</p>
            </div>

            @if(!empty($customTerms))
            <div class="text-xs sm:text-sm text-[#2B2118] leading-relaxed whitespace-pre-line space-y-4">
                {{ $customTerms }}
            </div>
            @else
            <div class="text-xs sm:text-sm text-[#2B2118] leading-relaxed space-y-6">
                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">1. مقدمة وقبول الشروط</h2>
                    <p class="text-[#6B5642]">
                        مرحباً بكم في منصة {{ settings('site_name', 'أزياء لافيستا') }}. باستخدامك لموقعنا الإلكتروني وتصفحك لمجموعات الأزياء والموديلات أو إجراء عمليات الشراء والتوصيل والاستلام من فروعنا، فإنك تقر بموافقتك الكاملة على كافة الشروط والبنود الواردة في هذه الوثيقة.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">2. دقة المعروضات والمقاسات والأسعار</h2>
                    <p class="text-[#6B5642]">
                        نحرص دائماً على تصوير القطع وتوضيح خاماتها وألوانها وجداول مقاساتها بأعلى دقة ممكنة. جميع الأسعار بالجنيه المصري (ج.م) وشاملة للضرائب المطبقة. في حالة نفاد مقاس أو لون محدد أثناء تأكيد طلبك، سيتواصل معك أحد منسقي الأزياء لدينا لاقتراح البديل الأنسب أو إتمام الشحن بدون الصنف الناقص.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">3. الشحن والتوصيل ومعاينة الموديلات</h2>
                    <p class="text-[#6B5642]">
                        نوفر شحن سريع ومضمون لكافة المحافظات والمناطق. يحق للعميل فتح الطرد ومعاينة الخامات والتشطيب والتطريز قبل سداد قيمة الطلب للمندوب. يرجى توفير رقم هاتف فعال ومتاح للتنسيق مع مندوب التوصيل قبل الحضور.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">4. طرق الدفع والتحويل عبر InstaPay</h2>
                    <p class="text-[#6B5642]">
                        نوفر خيارات الدفع عند الاستلام (كاش) لكافة الطلبات، بالإضافة إلى الدفع المسبق الآمن عبر تطبيق إنستاباي (InstaPay) والمحافظ الإلكترونية البنكية. في حالة التحويل عبر إنستاباي يرجى إرفاق إيصال التحويل لتسريع تجهيز طلبيتك من البوتيك.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">5. حقوق الملكية الفكرية والعلامة التجارية</h2>
                    <p class="text-[#6B5642]">
                        كافة الصور والتصاميم والتنسيقات والشعارات المنشورة على هذا الموقع هي ملكية حصرية لـ {{ settings('site_name', 'أزياء لافيستا') }} ويحظر نسخها أو استخدامها لأغراض تجارية دون إذن كتابي مسبق.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">6. خدمة العملاء واستفسارات المقاسات</h2>
                    <p class="text-[#6B5642]">
                        فريق منسقي الموضة وخدمة العملاء في خدمتكم دائماً عبر الواتساب المباشر (<span dir="ltr">{{ settings('whatsapp_number', '01000000000') }}</span>) أو من خلال صفحة <a href="{{ route('contact.index') }}" class="text-[#A9552F] font-bold underline">اتصل بنا</a> لمساعدتكم في اختيار المقاس المناسب.
                    </p>
                </section>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
