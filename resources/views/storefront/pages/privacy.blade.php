{{-- resources/views/storefront/pages/privacy.blade.php --}}
@extends('layouts.storefront')

@section('title', 'سياسة الخصوصية | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
@php
    $customPrivacy = settings('privacy_content');
@endphp

<div class="bg-[#FBF6EA] min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#2B2118] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">سياسة الخصوصية</span>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-[#EADFC8] p-6 sm:p-10 shadow-sm space-y-8">
            <div class="border-b border-[#EADFC8] pb-6 space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#6B5642] border border-[#EADFC8]">
                    أمان وسرية بياناتكم
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-[#2B2118]">سياسة الخصوصية وحماية البيانات</h1>
                <p class="text-xs text-[#8F7B63]">آخر تحديث: {{ date('Y') }}</p>
            </div>

            @if(!empty($customPrivacy))
            <div class="text-xs sm:text-sm text-[#2B2118] leading-relaxed whitespace-pre-line space-y-4">
                {{ $customPrivacy }}
            </div>
            @else
            <div class="text-xs sm:text-sm text-[#2B2118] leading-relaxed space-y-6">
                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">1. التزامنا بحماية خصوصيتك</h2>
                    <p class="text-[#6B5642]">
                        نحن في {{ settings('site_name', 'أزياء لافيستا') }} نعتز بثقتكم الكريمة، ونلتزم بأعلى معايير السرية والأمان تجاه بياناتكم الشخصية. توضح هذه الوثيقة كيفية حفظ واستخدام معلوماتكم عند تسوقكم من متجرنا الإلكتروني.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">2. البيانات التي نجمعها</h2>
                    <p class="text-[#6B5642]">
                        نقوم بجمع البيانات الضرورية فقط لضمان دقة تنفيذ وتوصيل طلبيات الأزياء والمقاسات المطلوبة:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-[#6B5642] pr-2">
                        <li>الاسم ورقم الهاتف لتنسيق موعد التسليم والمقاسات.</li>
                        <li>عنوان التوصيل أو الفرع المفضل للاستلام وقياس الملابس.</li>
                        <li>البريد الإلكتروني لتأكيد استلام الطلب وتتبع الشحن.</li>
                        <li>إيصالات تحويل إنستاباي في حال اختيار الدفع المسبق لتأكيد التحويل.</li>
                    </ul>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">3. كيف نستخدم معلوماتك</h2>
                    <p class="text-[#6B5642]">
                        تُستخدم البيانات لتجهيز قطع الأزياء، وتنسيق التوصيل السريع، وإرسال تحديثات حالة الطلب ومواعيد الشحن، والرد على أي استفسارات تخص المقاسات والتنسيقات.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">4. الخصوصية وعدم مشاركة البيانات</h2>
                    <p class="text-[#6B5642]">
                        نؤكد لكم أننا لا نقوم ببيع أو تأجير أو مشاركة بياناتكم مع أي جهة إعلانية خارجية. بياناتكم محفوظة في خوادم مشفرة وآمنة تماماً.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">5. تعديل بياناتك الشخصية</h2>
                    <p class="text-[#6B5642]">
                        يمكنك دائماً تعديل بياناتك أو عناوين التوصيل عبر <a href="{{ route('account.profile') }}" class="text-[#A9552F] font-bold underline">صفحة حسابك الشخصي</a>، أو طلب حذف بياناتك عبر التواصل مع خدمة العملاء.
                    </p>
                </section>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
