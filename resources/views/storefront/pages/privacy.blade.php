{{-- resources/views/storefront/pages/privacy.blade.php --}}
@extends('layouts.storefront')

@section('title', 'سياسة الخصوصية | ' . settings('site_name', 'ألبان وسوبرماركت التوحيد'))

@section('content')
@php
    $customPrivacy = settings('privacy_content');
@endphp

<div class="bg-gray-50/50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-charcoal-heading font-bold">سياسة الخصوصية</span>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-cream p-6 sm:p-10 shadow-sm space-y-8">
            <div class="border-b border-gray-100 pb-5 space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-100">
                    أمان وسرية بياناتكم
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-charcoal-heading">سياسة الخصوصية وحماية البيانات</h1>
                <p class="text-xs text-charcoal-muted">آخر تحديث: {{ date('Y') }}</p>
            </div>

            @if(!empty($customPrivacy))
            <div class="text-xs sm:text-sm text-charcoal leading-relaxed whitespace-pre-line space-y-4">
                {{ $customPrivacy }}
            </div>
            @else
            <div class="text-xs sm:text-sm text-charcoal leading-relaxed space-y-6">
                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">1. التزامنا بحماية خصوصيتك</h2>
                    <p class="text-charcoal-muted">
                        نحن في {{ settings('site_name', 'ألبان وسوبرماركت التوحيد') }} نعتز بثقتكم الغالية، ونلتزم بأقصى درجات السرية والأمان تجاه بياناتكم الشخصية. تشرح هذه السياسة كيفية جمع واستخدام وحماية معلوماتكم عند استخدام موقعنا الإلكتروني.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">2. البيانات التي نجمعها</h2>
                    <p class="text-charcoal-muted">
                        نقوم بجمع البيانات الضرورية فقط لتنفيذ وتوصيل طلباتكم بدقة وأمان، وتشمل:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-charcoal-muted pr-2">
                        <li>الاسم الكامل ورقم الهاتف المحمول للتواصل أثناء التوصيل.</li>
                        <li>عنوان التوصيل التفصيلي (المنطقة، الشارع، رقم العقار والشقة).</li>
                        <li>البريد الإلكتروني (اختياري، لإرسال فواتير الطلبات وإشعارات الحساب).</li>
                        <li>إيصالات تحويل InstaPay في حالة اختيار الدفع المسبق لتأكيد عملية التحويل.</li>
                        <li>ملفات السير الذاتية في حال التقديم على وظائف التوحيد.</li>
                    </ul>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">3. كيف نستخدم معلوماتك</h2>
                    <p class="text-charcoal-muted">
                        تُستخدم البيانات لأغراض: تجهيز وتوصيل طلباتك، إرسال إشعارات التتبع وحالة الطلب، الرد على استفساراتكم وشكاواكم، وتحسين تجربة التسوق في المتجر.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">4. عدم مشاركة البيانات مع أطراف ثالثة</h2>
                    <p class="text-charcoal-muted">
                        نضمن لك أننا لا نقوم ببيع أو تأجير أو مشاركة بياناتك الشخصية مع أي جهات تسويقية خارجية على الإطلاق. لا يتم تزويد مندوبي التوصيل إلا بالاسم ورقم الهاتف والعنوان لتسليم الطلب فقط.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-primary-800">5. حقوقك في تعديل أو حذف البيانات</h2>
                    <p class="text-charcoal-muted">
                        يمكنك في أي وقت الدخول إلى <a href="{{ route('account.profile') }}" class="text-primary-800 font-bold underline">صفحة حسابك</a> لتعديل بياناتك، أو تعديل وحذف العناوين المحفوظة، أو التواصل مع خدمة العملاء لطلب حذف الحساب بالكامل.
                    </p>
                </section>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
