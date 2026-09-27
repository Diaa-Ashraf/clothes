{{-- resources/views/storefront/branches/index.blade.php --}}
@extends('layouts.storefront')

@section('title', 'فروع ألبان وسوبرماركت التوحيد في العامرية | أقرب فرع لك')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-medium text-charcoal-muted" aria-label="Breadcrumb">
            <a href="{{ route('site.home') }}" class="hover:text-primary-800 transition-colors">الرئيسية</a>
            <span>/</span>
            <span class="text-charcoal-heading font-bold">فروعنا</span>
        </nav>

        <!-- Header Hero Banner -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-900 via-primary-800 to-emerald-950 text-white p-6 sm:p-10 shadow-lg text-center space-y-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-accent-500/20 text-accent-300 border border-accent-400/30 text-xs font-bold mx-auto">
                <svg class="w-3.5 h-3.5 text-accent-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                <span>4 فروع تغطي كل أحياء العامرية بالإسكندرية</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black max-w-2xl mx-auto leading-tight">
                أهلاً بك في فروع ألبان وسوبرماركت التوحيد
            </h1>
            <p class="text-xs sm:text-sm text-cream-muted max-w-xl mx-auto leading-relaxed">
                يسعدنا دائماً استقبالكم في فروعنا لتجربة منتجات الألبان الطازجة يومياً، أو يمكنكم الطلب أونلاين واختيار الاستلام من الفرع الأقرب إليكم بدون انتظار.
            </p>
        </div>

        <!-- Branches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($branches as $branch)
                <x-storefront.ui.branch-card :branch="$branch" />
            @empty
                <div class="col-span-full bg-white rounded-3xl border border-cream p-12 text-center text-charcoal-muted space-y-3">
                    <div class="w-12 h-12 mx-auto rounded-2xl bg-primary-50 text-primary-800 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <p class="text-sm font-bold text-charcoal-heading">يتم تحديث قائمة الفروع حالياً</p>
                </div>
            @endforelse
        </div>

        <!-- Al-Ameriya Delivery & Pickup Notice -->
        <div class="bg-primary-50 rounded-3xl border border-primary-200 p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xs">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white text-primary-800 shadow-sm border border-primary-100 flex items-center justify-center text-2xl flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-charcoal-heading">خدمة التوصيل السريع متاحة من جميع الفروع</h3>
                    <p class="text-xs text-charcoal-muted leading-relaxed">
                        نغطي مناطق العامرية، كينج مريوط، شارع الإسعاف، النهضة، عبد القادر، والقرى المجاورة بسيارات مجهزة ومبردة للحفاظ على سلامة الألبان واللحوم.
                    </p>
                </div>
            </div>

            <a href="{{ route('shop') }}" class="px-6 py-3 rounded-2xl bg-primary-800 hover:bg-primary-900 text-white text-xs font-bold transition-all shadow-sm flex items-center gap-2 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span>اطلب أونلاين الآن</span>
            </a>
        </div>

    </div>
</div>
@endsection
