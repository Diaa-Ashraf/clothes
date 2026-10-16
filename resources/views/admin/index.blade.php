@extends('layouts.admin')

@section('title', 'الرئيسية والإحصائيات')

@section('content')
<div class="space-y-6 sm:space-y-8">
    {{-- Header --}}
    <div class="bg-cream border border-sand rounded-card p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-pill text-xs font-medium bg-action text-action-ink shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-clay animate-pulse"></span>
                    <span>منصة وتطبيق متجر الأزياء والملابس</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-espresso tracking-tight">
                لوحة المتابعة الشاملة والأداء
            </h1>
            <p class="text-xs sm:text-sm text-mocha font-normal mt-1">
                متابعة حركة الطلبات، مستويات المخزون والقطع، وتحليلات المبيعات اليومية والشهرية.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2.5 rounded-pill border border-sand bg-linen hover:bg-beige text-espresso text-xs font-medium transition-all shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>الطلبات ({{ $metrics['pending_orders'] ?? 0 }} قيد الانتظار)</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" 
               class="px-4 py-2.5 rounded-pill border border-sand bg-cream hover:bg-linen text-espresso text-xs font-medium transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span>+ قسم وتصنيف</span>
            </a>
            <a href="{{ route('admin.attributes.index') }}" 
               class="px-4 py-2.5 rounded-pill border border-sand bg-cream hover:bg-linen text-espresso text-xs font-medium transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                <span>+ مقاسات وألوان</span>
            </a>
            <a href="{{ route('admin.deals.create') }}" 
               class="px-4 py-2.5 rounded-pill border border-sand bg-linen hover:bg-beige text-clay text-xs font-medium transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>+ عرض وتخفيض</span>
            </a>
            <a href="{{ route('admin.products.create') }}" 
               class="btn-atelier-primary text-xs cursor-pointer">
                <span>+</span>
                <span>إضافة منتج أزياء جديد</span>
            </a>
        </div>
    </div>

    {{-- 4 Stat Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Orders --}}
        <div class="bg-cream border border-sand rounded-card p-5 shadow-xs flex items-center justify-between hover:bg-linen/40 transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-taupe">إجمالي الطلبات المسجلة</span>
                <h3 class="text-2xl font-bold text-espresso tabular-nums">{{ number_format($metrics['total_orders'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-medium text-mocha bg-linen px-2.5 py-0.5 rounded-lg border border-sand">
                    اليوم: {{ $metrics['today_orders'] ?? 0 }} طلب
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-linen text-mocha border border-sand flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-cream border border-sand rounded-card p-5 shadow-xs flex items-center justify-between hover:bg-linen/40 transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-taupe">إجمالي المبيعات</span>
                <h3 class="text-2xl font-bold text-espresso tabular-nums">
                    {{ format_currency($metrics['total_revenue'] ?? 0) }}
                </h3>
                <span class="inline-block text-[11px] font-medium text-status-success bg-linen px-2.5 py-0.5 rounded-lg border border-sand">
                    مبيعات معتمدة
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-linen text-clay border border-sand flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Order Inquiries / WhatsApp --}}
        <div class="bg-cream border border-sand rounded-card p-5 shadow-xs flex items-center justify-between hover:bg-linen/40 transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-taupe">نقرات الشراء والاستفسار</span>
                <h3 class="text-2xl font-bold text-espresso tabular-nums">{{ number_format($metrics['checkout_clicks'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-medium text-mocha bg-linen px-2.5 py-0.5 rounded-lg border border-sand">
                    اليوم: {{ $metrics['today_whatsapp_clicks'] ?? 0 }} نقرة
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-linen text-mocha border border-sand flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
        </div>

        {{-- Abandoned Carts --}}
        <div class="bg-cream border border-sand rounded-card p-5 shadow-xs flex items-center justify-between hover:bg-linen/40 transition-all">
            <div class="space-y-1">
                <span class="text-xs font-medium text-taupe">سلات تسوق متروكة</span>
                <h3 class="text-2xl font-bold text-clay tabular-nums">{{ number_format($metrics['abandoned_carts_count'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-medium text-mocha bg-linen px-2.5 py-0.5 rounded-lg border border-sand">
                    القيمة: {{ format_currency($metrics['abandoned_carts_total'] ?? 0) }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-linen text-clay border border-sand flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Branch Performance Section --}}
    <div class="bg-cream border border-sand rounded-card p-6 sm:p-7 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-sand">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-status-success"></span>
                    <h2 class="font-bold text-base sm:text-lg text-espresso">متابعة أداء ومبيعات الفروع ومنافذ العرض</h2>
                </div>
                <p class="text-xs text-mocha mt-0.5">تفاصيل الطلبات والاستلام وتوزيع المبيعات على منافذ البيع</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.branches.index') }}" class="btn-atelier-ghost text-xs">
                    إدارة الفروع ←
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn-atelier-ghost text-xs">
                    التقارير الشاملة ←
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($branchStats ?? [] as $bStat)
                <div class="p-4 rounded-xl bg-linen/50 border border-sand flex flex-col justify-between space-y-3 hover:bg-linen transition-all">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <h3 class="font-bold text-sm text-espresso truncate">{{ $bStat['name'] }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-beige text-espresso border border-sand">
                                {{ $bStat['active_count'] }} طلب نشط
                            </span>
                        </div>
                        <p class="text-[11px] text-taupe truncate flex items-center gap-1">
                            <svg class="w-3 h-3 text-mocha shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $bStat['address'] }}</span>
                        </p>
                    </div>

                    <div class="pt-2 border-t border-sand/60 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-taupe">إجمالي الطلبات:</span>
                            <span class="font-medium text-espresso tabular-nums">{{ $bStat['total_orders'] }} طلب</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-taupe">الطلبات المسلمة:</span>
                            <span class="font-medium text-status-success tabular-nums">{{ $bStat['delivered_count'] }} طلب</span>
                        </div>
                        <div class="flex items-center justify-between pt-1 border-t border-sand">
                            <span class="text-taupe">مبيعات الفرع:</span>
                            <span class="font-bold text-espresso tabular-nums">{{ format_currency($bStat['total_revenue']) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 py-6 text-center text-xs text-taupe">
                    لا توجد فروع مسجلة حتى الآن.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Content Row: Top Products & Quick Links --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Top Products --}}
        <div class="lg:col-span-8 bg-cream border border-sand rounded-card p-6 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-espresso">قطع ومنتجات الأزياء الأكثر طلباً ومبيعاً</h2>
                    <p class="text-xs text-mocha">الملابس والتشكيلات الأكثر رواجاً وإقبالاً</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-medium text-clay hover:text-action transition-colors">
                    عرض كل المنتجات ←
                </a>
            </div>

            @if(count($topProducts) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-sand text-taupe font-medium">
                                <th class="pb-3 text-right">المنتج / القطعة</th>
                                <th class="pb-3 text-center">الكمية المباعة</th>
                                <th class="pb-3 text-center">إجمالي الإيراد</th>
                                <th class="pb-3 text-left">إجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sand/40">
                            @foreach($topProducts as $item)
                                <tr class="hover:bg-linen/40 transition-colors">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item['image'] ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="w-10 h-10 object-cover rounded-lg border border-sand bg-vanilla p-0.5">
                                            <span class="font-medium text-espresso">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-pill bg-linen text-mocha font-medium text-[11px] border border-sand">
                                            {{ $item['total_sold'] }} قطعة
                                        </span>
                                    </td>
                                    <td class="py-3 text-center font-bold text-espresso tabular-nums">
                                        {{ format_currency($item['total_revenue']) }}
                                    </td>
                                    <td class="py-3 text-left">
                                        <a href="{{ route('admin.products.edit', $item['product_id']) }}" 
                                           class="px-3 py-1.5 rounded-lg bg-linen hover:bg-beige text-espresso font-medium text-[11px] border border-sand transition-colors">
                                            تعديل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-taupe">
                    <p class="text-xs">لا توجد مبيعات مسجلة حتى الآن.</p>
                </div>
            @endif
        </div>

        {{-- Quick Management Tools --}}
        <div class="lg:col-span-4 bg-cream border border-sand rounded-card p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h2 class="text-base font-bold text-espresso mb-1">إجراءات وروابط سريعة</h2>
                <p class="text-xs text-taupe mb-4">أدوات الإدارة السريعة للكتالوج والطلبات</p>
                
                <div class="space-y-2.5">
                    <a href="{{ route('admin.deals.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-xl bg-linen/50 hover:bg-linen border border-sand transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-action text-action-ink flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-espresso block group-hover:text-clay transition-colors">العروض والتخفيضات الموسمية</strong>
                            <span class="text-[11px] text-mocha font-normal">إدارة نسب الخصم وتخفيضات المواسم</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.branches.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-xl bg-linen/50 hover:bg-linen border border-sand transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-action text-action-ink flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-espresso block group-hover:text-clay transition-colors">الفروع ومنافذ البيع</strong>
                            <span class="text-[11px] text-mocha font-normal">إدارة الفروع ومواعيد العمل وبيانات التواصل</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-xl bg-linen/50 hover:bg-linen border border-sand transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-action text-action-ink flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-espresso block group-hover:text-clay transition-colors">التقارير الشاملة والمبيعات</strong>
                            <span class="text-[11px] text-mocha font-normal">تحليلات الأداء والفترات وتفاصيل الإيرادات</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.bundles.create') }}" 
                       class="flex items-start gap-3 p-3 rounded-xl bg-linen/50 hover:bg-linen border border-sand transition-all group">
                        <div class="w-9 h-9 rounded-lg bg-action text-action-ink flex items-center justify-center shrink-0 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-espresso block group-hover:text-clay transition-colors">إنشاء أطقم ومجموعات (Bundles)</strong>
                            <span class="text-[11px] text-mocha font-normal">تنسيق أطقم ملابس مجمعة بسعر مميز</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection