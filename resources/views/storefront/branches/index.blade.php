{{-- resources/views/storefront/branches/index.blade.php --}}
@extends('layouts.storefront')

@section('title', 'فروع ' . settings('site_name', 'أزياء لافيستا') . ' | أقرب فرع لك')

@section('content')
<div class="bg-[#FBF6EA] min-h-screen py-8" dir="rtl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-[#8F7B63]" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-[#382416] transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-[#2B2118] font-bold">فروعنا وبوتيكاتنا</span>
        </nav>

        <!-- Header Hero Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-[#2B2118] text-[#FBF6EA] p-6 sm:p-10 shadow-lg text-center space-y-4 border border-[#CDB78F]/30">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#F3E9D2] text-[#382416] border border-[#CDB78F] text-xs font-bold mx-auto">
                <svg class="w-3.5 h-3.5 text-[#A9552F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                <span>4 بوتيكات راقية في خدمتكم لتجربة القياس المباشر</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-serif font-black max-w-2xl mx-auto leading-tight text-[#FBF6EA]">
                أهلاً بك في فروع {{ settings('site_name', 'أزياء لافيستا') }}
            </h1>
            <p class="text-xs sm:text-sm text-[#F3E9D2]/90 max-w-xl mx-auto leading-relaxed font-normal">
                يسعدنا دائماً استقبالكم في معارضنا لتجربة ومعاينة أحدث تشكيلات الأزياء، أو يمكنكم الطلب أونلاين واختيار الاستلام مجاناً من أقرب بوتيك إليكم.
            </p>
        </div>

        <!-- Branches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($branches as $branch)
                <x-storefront.ui.branch-card :branch="$branch" />
            @empty
                <div class="col-span-full bg-[#FDFBF7] rounded-3xl border border-[#EADFC8] p-12 text-center text-[#8F7B63] space-y-3">
                    <p class="text-sm font-serif font-bold text-[#2B2118]">يتم تحديث قائمة الفروع حالياً</p>
                </div>
            @endforelse
        </div>

        <!-- Boutique Service Notice -->
        <div class="bg-[#FDFBF7] rounded-3xl border border-[#EADFC8] p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#F3E9D2] text-[#382416] shadow-2xs border border-[#EADFC8] flex items-center justify-center text-2xl shrink-0">
                    🛍️
                </div>
                <div class="space-y-1">
                    <h3 class="font-serif font-bold text-base text-[#2B2118]">خدمة الاستلام المجاني وتجربة القياس متاحة في كافة الفروع</h3>
                    <p class="text-xs text-[#8F7B63] leading-relaxed">
                        يمكنك حجز مشترياتك أونلاين والتوجه لأقرب بوتيك لمعاينتها وتجربة المقاس قبل الاستلام، مع خدمة عملاء متميزة.
                    </p>
                </div>
            </div>

            <a href="{{ route('site.products') }}" class="px-6 py-3 rounded-2xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] text-xs font-bold transition-all shadow-md flex items-center gap-2 shrink-0 border border-[#CDB78F]/30">
                <span>تصفح الكتالوج الآن</span>
                <span class="rotate-180">←</span>
            </a>
        </div>

    </div>
</div>
@endsection
