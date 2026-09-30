@extends('layouts.admin')

@section('title', 'الرئيسية والإحصائيات')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-orange-100 text-orange-700">
                    <span class="w-2 h-2 rounded-full bg-red-600 animate-pulse"></span>
                    <span>لوحة تحكم ألبان وسوبرماركت التوحيد بالعامرية</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                إحصائيات المبيعات والطلبات اليومية
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                نظرة شاملة على حركة المبيعات، الطلبات المعلقة، ونشاط فروع العامرية.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2.5 rounded-xl border border-orange-200 bg-orange-50 hover:bg-orange-100 text-red-700 text-xs font-bold transition-all shadow-xs flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>الطلبات ({{ $metrics['pending_orders'] ?? 0 }} قيد الانتظار)</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" 
               class="px-4 py-2.5 rounded-xl border border-orange-200 bg-white hover:bg-orange-50 text-gray-800 text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <span>🏷️</span>
                <span>+ قسم جديد</span>
            </a>
            <a href="{{ route('admin.deals.create') }}" 
               class="px-4 py-2.5 rounded-xl border border-red-200 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <span>🔥</span>
                <span>+ عرض وتخفيض</span>
            </a>
            <a href="{{ route('admin.products.create') }}" 
               class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                <span>+</span>
                <span>إضافة صنف جديد</span>
            </a>
        </div>
    </div>

    {{-- 4 Stat Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Orders --}}
        <div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">إجمالي الطلبات</span>
                <h3 class="text-2xl font-black text-gray-900 tabular-nums">{{ number_format($metrics['total_orders'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md">
                    طلبات اليوم: {{ $metrics['today_orders'] ?? 0 }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 border border-orange-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">إجمالي المبيعات</span>
                <h3 class="text-2xl font-black text-red-600 tabular-nums">
                    {{ format_currency($metrics['total_revenue'] ?? 0) }}
                </h3>
                <span class="inline-block text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                    مبيعات معتمدة
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 border border-red-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- WhatsApp Checkout Clicks --}}
        <div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">نقرات طلبات واتساب</span>
                <h3 class="text-2xl font-black text-gray-900 tabular-nums">{{ number_format($metrics['checkout_clicks'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                    اليوم: {{ $metrics['today_whatsapp_clicks'] ?? 0 }} نقرة
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                </svg>
            </div>
        </div>

        {{-- Abandoned Carts --}}
        <div class="bg-white border border-orange-100 rounded-2xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">سلات تسوق متروكة</span>
                <h3 class="text-2xl font-black text-rose-600 tabular-nums">{{ number_format($metrics['abandoned_carts_count'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                    القيمة: {{ format_currency($metrics['abandoned_carts_total'] ?? 0) }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Content Row: Top Products & Quick Links --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Top Products --}}
        <div class="lg:col-span-8 bg-white border border-orange-100 rounded-3xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-black text-gray-900">الأصناف والمنتجات الأكثر مبيعاً</h2>
                    <p class="text-xs text-gray-500">المنتجات الأكثر طلباً في ألبان وسوبرماركت التوحيد</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-red-600 hover:text-orange-600 transition-colors">
                    عرض كل المنتجات ←
                </a>
            </div>

            @if(count($topProducts) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-xs">
                        <thead>
                            <tr class="border-b border-orange-100 text-gray-400 font-bold">
                                <th class="pb-3 text-right">المنتج</th>
                                <th class="pb-3 text-center">الكمية المباعة</th>
                                <th class="pb-3 text-center">إجمالي الإيراد</th>
                                <th class="pb-3 text-left">إجراء</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-50">
                            @foreach($topProducts as $item)
                                <tr class="hover:bg-orange-50/40 transition-colors">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item['image'] ?? asset('assets/site/img/product/product-1.jpg') }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="w-10 h-10 object-cover rounded-xl border border-orange-100 bg-canvas-base p-0.5">
                                            <span class="font-bold text-gray-900">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="px-2.5 py-1 rounded-full bg-orange-100 text-orange-800 font-black text-[11px]">
                                            {{ $item['total_sold'] }} صنف
                                        </span>
                                    </td>
                                    <td class="py-3 text-center font-black text-red-600 tabular-nums">
                                        {{ format_currency($item['total_revenue']) }}
                                    </td>
                                    <td class="py-3 text-left">
                                        <a href="{{ route('admin.products.edit', $item['product_id']) }}" 
                                           class="px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-orange-100 text-gray-700 hover:text-red-700 font-bold text-[11px] transition-colors">
                                            تعديل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-gray-400">
                    <p class="text-xs">لا توجد مبيعات مسجلة حتى الآن.</p>
                </div>
            @endif
        </div>

        {{-- Quick Management Tools --}}
        <div class="lg:col-span-4 bg-white border border-orange-100 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h2 class="text-base font-black text-gray-900 mb-1">إجراءات سريعة</h2>
                <p class="text-xs text-gray-500 mb-4">أدوات الإدارة والتحكم الأكثر استخداماً</p>
                
                <div class="space-y-2.5">
                    <a href="{{ route('admin.bundles.create') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-orange-50/60 hover:bg-orange-100/80 border border-orange-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">إنشاء عرض مجمع (Bundle)</strong>
                            <span class="text-[11px] text-gray-500 font-medium">ادمج عدة أصناف بلدية بسعر مخفض</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.deals.create') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-red-50/60 hover:bg-red-100/80 border border-red-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">إضافة خصم أو عرض اليوم</strong>
                            <span class="text-[11px] text-gray-500 font-medium">تحديد نسبة الخصم وتاريخ الانتهاء</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.abandoned-carts.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-amber-50/60 hover:bg-amber-100/80 border border-amber-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">متابعة السلات المتروكة</strong>
                            <span class="text-[11px] text-gray-500 font-medium">التواصل الفوري مع العملاء بالعامرية</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.settings.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-gray-50 hover:bg-gray-100 border border-gray-200 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-gray-800 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">إعدادات المتجر العامة</strong>
                            <span class="text-[11px] text-gray-500 font-medium">اسم المتجر، أرقام الفروع، ورسوم التوصيل</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
