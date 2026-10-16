{{-- resources/views/storefront/pages/return-policy.blade.php --}}
@extends('layouts.storefront')

@section('title', 'سياسة الاستبدال والاسترجاع | ' . settings('site_name', 'أزياء لافيستا'))

@section('content')
@php
    $customReturn = settings('return_policy_content');
@endphp

<div class="bg-[#FBF6EA] min-h-screen py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#2B2118] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">سياسة الاستبدال والاسترجاع</span>
        </nav>

        <!-- Main Card -->
        <div class="bg-white rounded-3xl border border-[#EADFC8] p-6 sm:p-10 shadow-sm space-y-8">
            <div class="border-b border-[#EADFC8] pb-6 space-y-2">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#F3E9D2] text-[#6B5642] border border-[#EADFC8]">
                    ضمان الرضا وتوافق المقاسات 100%
                </span>
                <h1 class="text-2xl sm:text-3xl font-black text-[#2B2118]">سياسة الاستبدال والاسترجاع للأزياء</h1>
                <p class="text-xs text-[#8F7B63]">تسوق بثقة تامة مع حق المعاينة وتجربة المقاس وخدمة استبدال مرنة وسريعة</p>
            </div>

            @if(!empty($customReturn))
            <div class="text-xs sm:text-sm text-[#2B2118] leading-relaxed whitespace-pre-line space-y-4">
                {{ $customReturn }}
            </div>
            @else
            <div class="text-xs sm:text-sm text-[#2B2118] leading-relaxed space-y-6">
                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">1. حق المعاينة وتجربة المقاس عند الاستلام</h2>
                    <p class="text-[#6B5642]">
                        يحق للعميل فتح الشحنة ومعاينة جودة الأقمشة والخامات بحضور مندوب الشحن قبل إتمام الاستلام والدفع، أو تجربة المقاس في غرف القياس المخصصة داخل فروعنا وبوتيكاتنا. في حال عدم ملائمة المقاس أو الرغبة في التبديل، يمكنك إرجاع القطعة فوراً مع المندوب.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">2. مهلة الاستبدال والاسترجاع (14 يوماً)</h2>
                    <p class="text-[#6B5642]">
                        يمكنك استبدال أو استرجاع أي قطعة من تشكيلاتنا خلال <strong>14 يوماً</strong> من تاريخ الاستلام، مع إمكانية استبدال المقاس بمقاس آخر أو اختيار قطعة مختلفة تماماً من المعروضات.
                    </p>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">3. شروط قبول الاسترجاع والاستبدال</h2>
                    <p class="text-[#6B5642]">
                        لضمان قبول طلبك بكل سهولة، يُرجى مراعاة ما يلي:
                    </p>
                    <ul class="list-disc list-inside text-[#6B5642] space-y-1 mr-2">
                        <li>أن تكون الملابس جديدة، غير مستعملة، وغير مغسولة، وخالية من أي روائح أو بقع عطرية.</li>
                        <li>احتفاظ القطعة ببطاقة السعر الأصلية والباركود (Tags) وتغليف البوتيك الخاص بها.</li>
                        <li>إحضار أو إرفاق إيصال الشراء أو رقم الطلب الإلكتروني.</li>
                    </ul>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">4. آلية استرداد الأموال</h2>
                    <div class="text-[#6B5642] space-y-1">
                        <p><strong>الدفع عند الاستلام:</strong> يتم رد المبلغ نقداً فور تسليم القطعة لمندوبنا أو داخل أي من فروعنا.</p>
                        <p><strong>الدفع عبر إنستاباي (InstaPay):</strong> يتم تحويل قيمة المشتريات إلى حسابكم البنكي أو المحفظة الإلكترونية خلال 24 ساعة من فحص القطعة المسترجعة.</p>
                    </div>
                </section>

                <section class="space-y-2">
                    <h2 class="text-base font-bold text-[#A9552F]">5. كيف تقدم طلب استبدال مقاس أو إرجاع؟</h2>
                    <p class="text-[#6B5642]">
                        تواصل فوراً مع منسقي خدمة العملاء عبر واتساب (<span dir="ltr">{{ settings('whatsapp_number', '01000000000') }}</span>) أو من خلال حسابك الشخصي في قسم الطلبات، وسيقوم فريقنا بتنسيق مندوب التبديل حتى باب بيتك.
                    </p>
                </section>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
