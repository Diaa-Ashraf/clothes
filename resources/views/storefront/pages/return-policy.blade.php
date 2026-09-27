{{-- resources/views/storefront/pages/return-policy.blade.php --}}
@extends('layouts.storefront')

@section('title', 'سياسة الاستبدال والاسترجاع | ' . settings('site_name', 'ألبان وسوبرماركت التوحيد'))

@section('content')
@php
    $customReturn = settings('return_policy_content');
@endphp

<div class="bg-gray-50/50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-charcoal-heading font-bold">سياسة الاستبدال والاسترجاع</span>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-cream p-6 sm:p-10 shadow-sm space-y-8">
            <div class="border-b border-gray-100 pb-5 space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-100">
                    ضمان رضا العميل بنسبة 100%
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-charcoal-heading">سياسة الاستبدال والاسترجاع</h1>
                <p class="text-xs text-charcoal-muted">حرصاً منا على صحة وسلامة أهالينا في العامرية</p>
            </div>

            @if(!empty($customReturn))
            <div class="text-xs sm:text-sm text-charcoal leading-relaxed whitespace-pre-line space-y-4">
                {{ $customReturn }}
            </div>
            @else
            <div class="text-xs sm:text-sm text-charcoal leading-relaxed space-y-6">
                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">1. فحص الطلب فور الاستلام (حق العميل الكامل)</h2>
                    <p class="text-charcoal-muted">
                        يحق للعميل فحص جميع الأصناف والمنتجات بحضور مندوب التوصيل قبل مغادرته، أو عند استلام الطلب داخل الفرع. في حال وجود أي منتج غير مطابق أو تالف، يحق للعميل رفض استلام هذا المنتج فوراً وخصم قيمته من الفاتورة.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">2. المنتجات الطازجة سريعة التلف (الألبان، القشطة، الأجبان الطازجة)</h2>
                    <p class="text-charcoal-muted">
                        نظراً لطبيعة منتجات الألبان الطازجة وحساسيتها العالية للحرارة وحفظها المبرد، نرجو الإبلاغ عن أي ملاحظة بخصوص جودة المنتج أو مذاقه خلال <strong>24 ساعة</strong> كحد أقصى من الاستلام، مع الاحتفاظ بالمنتج وعبوته في الثلاجة حتى يتم إرسال مندوب لاستبداله أو استرداد قيمته.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">3. منتجات البقالة والمعلبات الجافة</h2>
                    <p class="text-charcoal-muted">
                        يمكن استبدال أو استرجاع منتجات البقالة الجافة والمغلفة خلال <strong>14 يوماً</strong> من تاريخ الشراء، بشرط أن تكون العبوة الأصلية مغلقة تماماً ولم يتم فتحها أو استخدامها، ومرفقة بفاتورة الشراء أو رقم الطلب.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">4. آلية استرداد الأموال</h2>
                    <div class="text-charcoal-muted space-y-1">
                        <p><strong>الدفع نقداً عند الاستلام:</strong> يتم رد المبلغ نقداً فور استلام الصنف المسترجع من مندوبنا.</p>
                        <p><strong>الدفع عبر InstaPay:</strong> يتم تحويل المبلغ المسترد إلى نفس حساب InstaPay أو المحفظة الإلكترونية للعميل خلال 24 ساعة من تأكيد عملية الإرجاع.</p>
                    </div>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">5. كيف تقدم طلب استرجاع أو استبدال؟</h2>
                    <p class="text-charcoal-muted">
                        يمكنك التواصل مباشرة مع خدمة العملاء عبر واتساب أو الاتصال الهاتفي (<span dir="ltr">{{ settings('store_phone', '01000000000') }}</span>) أو من خلال صفحة <a href="{{ route('contact.index') }}" class="text-primary-800 font-bold underline">اتصل بنا</a> وتزويدنا برقم طلبك وسيقوم فريقنا بخدمتك على الفور.
                    </p>
                </section>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
