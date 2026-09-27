{{-- resources/views/storefront/pages/terms.blade.php --}}
@extends('layouts.storefront')

@section('title', 'الشروط والأحكام | ' . settings('site_name', 'ألبان وسوبرماركت التوحيد'))

@section('content')
@php
    $customTerms = settings('terms_content');
@endphp

<div class="bg-gray-50/50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-charcoal-heading font-bold">الشروط والأحكام</span>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-cream p-6 sm:p-10 shadow-sm space-y-8">
            <div class="border-b border-gray-100 pb-5 space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-800 border border-primary-100">
                    وثيقة الاستخدام الرسمية
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-charcoal-heading">الشروط والأحكام</h1>
                <p class="text-xs text-charcoal-muted">آخر تحديث: {{ date('Y') }}</p>
            </div>

            @if(!empty($customTerms))
            <div class="text-xs sm:text-sm text-charcoal leading-relaxed whitespace-pre-line space-y-4">
                {{ $customTerms }}
            </div>
            @else
            <div class="text-xs sm:text-sm text-charcoal leading-relaxed space-y-6">
                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">1. مقدمة وقبول الشروط</h2>
                    <p class="text-charcoal-muted">
                        مرحباً بكم في منصة {{ settings('site_name', 'ألبان وسوبرماركت التوحيد') }} الإلكترونية بالعامرية، الإسكندرية. باستخدامك لموقعنا أو طلبك لأي من منتجاتنا أو خدمات التوصيل والاستلام من الفرع، فإنك توافق التزامك الكامل بالشروط والأحكام الموضحة أدناه.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">2. المنتجات والأسعار والمخزون</h2>
                    <p class="text-charcoal-muted">
                        جميع المنتجات الغذائية والألبان الطازجة والأجبان يتم تسعيرها بالجنيه المصري (ج.م). نسعى جاهدين لعرض أحدث الأسعار بدقة، وفي حالة حدوث أي خطأ غير مقصود في التسعير أو نفاد صنف أثناء تجهيز الطلب، سيقوم فريق خدمة العملاء بالتواصل الفوري معكم لاقتراح البديل المناسب أو تعديل الفاتورة.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">3. التوصيل ومناطق الخدمة بالعامرية</h2>
                    <p class="text-charcoal-muted">
                        تغطي خدمة التوصيل كافة أحياء العامرية، كينج مريوط، شارع الإسعاف، النهضة والمناطق المجاورة. يتم احتساب رسوم توصيل رمزية ثابتة توضح للعميل بوضوح في صفحة إتمام الطلب (Checkout). يلتزم العميل بتقديم عنوان صحيح ورقم هاتف متاح لاستلام الطلب بدون تأخير للحفاظ على سلامة المواد المبردة.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">4. طرق الدفع والتحويل عبر InstaPay والمحافظ</h2>
                    <p class="text-charcoal-muted">
                        نقبل الدفع عند الاستلام (نقداً لمندوب التوصيل أو داخل الفرع)، كما نوفر الدفع المسبق عبر تطبيق InstaPay ومحافظ الهواتف الذكية. في حالة اختيار التحويل عبر InstaPay، يجب رفع صورة إيصال التحويل واضحة أو كتابة الرقم المرجعي للتحويل، ويظل الطلب قيد المراجعة حتى تأكيد الاستلام من الإدارة المالية.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">5. الحسابات وأمان البيانات</h2>
                    <p class="text-charcoal-muted">
                        أنت مسؤول تماماً عن الحفاظ على سرية بيانات حسابك وكلمة المرور الخاصة بك. يرجى إبلاغنا فوراً في حال الاشتباه في أي استخدام غير مصرح به لحسابك.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">6. التواصل والشكاوى</h2>
                    <p class="text-charcoal-muted">
                        يسعدنا استقبال استفساراتكم أو شكاواكم عبر صفحة <a href="{{ route('contact.index') }}" class="text-primary-800 font-bold underline">اتصل بنا</a> أو عبر رقم خدمة العملاء (<span dir="ltr">{{ settings('store_phone', '01000000000') }}</span>) أو الواتساب الموضح في الموقع.
                    </p>
                </section>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
