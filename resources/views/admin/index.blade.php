@extends('layouts.admin')

@section('title', 'الرئيسية والإحصائيات')

@section('content')
<div class="space-y-6 sm:space-y-8">
    {{-- Header --}}
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    <span>ألبان وسوبرماركت التوحيد بالعامرية (4 فروع)</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                لوحة المتابعة الشاملة والإحصائيات
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                متابعة حركة الطلبات حسب الفروع، تحويلات إنستاباي، مجلة العروض، والتقارير المالية.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2.5 rounded-2xl border border-orange-200 bg-orange-50/70 hover:bg-orange-100 text-red-700 text-xs font-bold transition-all shadow-xs flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>الطلبات ({{ $metrics['pending_orders'] ?? 0 }} معلقة)</span>
            </a>
            <a href="{{ route('admin.categories.create') }}" 
               class="px-4 py-2.5 rounded-2xl border border-orange-200 bg-white hover:bg-orange-50 text-gray-800 text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <span>🏷️</span>
                <span>+ قسم جديد</span>
            </a>
            <a href="{{ route('admin.deals.create') }}" 
               class="px-4 py-2.5 rounded-2xl border border-red-200 bg-red-50/70 hover:bg-red-100 text-red-700 text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 cursor-pointer">
                <span>🔥</span>
                <span>+ عرض وتخفيض</span>
            </a>
            <a href="{{ route('admin.products.create') }}" 
               class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                <span>+</span>
                <span>إضافة صنف / منتج جديد</span>
            </a>
        </div>
    </div>

    {{-- 4 Stat Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        {{-- Total Orders --}}
        <div class="bg-white border border-orange-100 rounded-3xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">إجمالي الطلبات المسجلة</span>
                <h3 class="text-2xl font-black text-gray-900 tabular-nums">{{ number_format($metrics['total_orders'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-bold text-orange-700 bg-orange-50 px-2.5 py-0.5 rounded-lg border border-orange-200">
                    اليوم: {{ $metrics['today_orders'] ?? 0 }} طلب
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 border border-orange-200 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="bg-white border border-orange-100 rounded-3xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">إجمالي المبيعات</span>
                <h3 class="text-2xl font-black text-red-600 tabular-nums">
                    {{ format_currency($metrics['total_revenue'] ?? 0) }}
                </h3>
                <span class="inline-block text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200">
                    مبيعات معتمدة
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 border border-red-200 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- WhatsApp Checkout Clicks --}}
        <div class="bg-white border border-orange-100 rounded-3xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">نقرات واتساب للطلب</span>
                <h3 class="text-2xl font-black text-gray-900 tabular-nums">{{ number_format($metrics['checkout_clicks'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-lg border border-emerald-200">
                    اليوم: {{ $metrics['today_whatsapp_clicks'] ?? 0 }} نقرة
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.073.376-.044.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                </svg>
            </div>
        </div>

        {{-- Abandoned Carts --}}
        <div class="bg-white border border-orange-100 rounded-3xl p-5 shadow-xs flex items-center justify-between hover:shadow-md transition-all">
            <div class="space-y-1">
                <span class="text-xs font-bold text-gray-500">سلات تسوق متروكة</span>
                <h3 class="text-2xl font-black text-rose-600 tabular-nums">{{ number_format($metrics['abandoned_carts_count'] ?? 0) }}</h3>
                <span class="inline-block text-[11px] font-bold text-gray-600 bg-gray-100 px-2.5 py-0.5 rounded-lg border border-gray-200">
                    القيمة: {{ format_currency($metrics['abandoned_carts_total'] ?? 0) }}
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Branch Performance Section (فروع العامرية الأربعة ومتابعة طلبات كل فرع) --}}
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-7 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-orange-100">
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h2 class="font-black text-base sm:text-lg text-gray-900">متابعة أداء ومبيعات فروع العامرية (4 فروع)</h2>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">تفاصيل الطلبات والاستلام وتوزيع المبيعات على كل فرع من فروع المتجر</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.branches.index') }}" class="px-3.5 py-1.5 rounded-xl bg-orange-50 hover:bg-orange-100 border border-orange-200 text-xs font-bold text-red-700 transition-colors">
                    إدارة الفروع ←
                </a>
                <a href="{{ route('admin.reports.index') }}" class="px-3.5 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-xs font-bold text-red-700 transition-colors">
                    التقارير الشاملة ←
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($branchStats ?? [] as $bStat)
                <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100 flex flex-col justify-between space-y-3 hover:shadow-sm hover:border-orange-300 transition-all">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5">
                            <h3 class="font-black text-sm text-gray-900 truncate">{{ $bStat['name'] }}</h3>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                {{ $bStat['active_count'] }} طلب نشط
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 truncate">📍 {{ $bStat['address'] }}</p>
                    </div>

                    <div class="pt-2 border-t border-orange-200/60 space-y-1.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">إجمالي الطلبات:</span>
                            <span class="font-bold text-gray-900 tabular-nums">{{ $bStat['total_orders'] }} طلب</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">الطلبات المسلمة:</span>
                            <span class="font-bold text-emerald-700 tabular-nums">{{ $bStat['delivered_count'] }} طلب</span>
                        </div>
                        <div class="flex items-center justify-between pt-1 border-t border-orange-100">
                            <span class="text-gray-500">مبيعات الفرع:</span>
                            <span class="font-black text-red-600 tabular-nums font-mono">{{ format_currency($bStat['total_revenue']) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 py-6 text-center text-xs text-gray-400">
                    لا توجد فروع مسجلة حتى الآن.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Content Row: Top Products & Quick Links --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Top Products --}}
        <div class="lg:col-span-8 bg-white border border-orange-100 rounded-3xl p-6 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-black text-gray-900">الأصناف والمنتجات الأكثر طلباً ومبيعاً</h2>
                    <p class="text-xs text-gray-500">الألبان، الأجبان، والسلع الغذائية الأكثر طلباً بالعامرية</p>
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
                                <th class="pb-3 text-right">المنتج / الصنف</th>
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
                                            {{ $item['total_sold'] }} قطعة / وزن
                                        </span>
                                    </td>
                                    <td class="py-3 text-center font-black text-red-600 tabular-nums font-mono">
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

        {{-- Quick Management Tools (مجلة العروض، الفروع، التقارير) --}}
        <div class="lg:col-span-4 bg-white border border-orange-100 rounded-3xl p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h2 class="text-base font-black text-gray-900 mb-1">إجراءات وروابط سريعة</h2>
                <p class="text-xs text-gray-500 mb-4">أدوات إدارة متجر وفروع ألبان التوحيد</p>
                
                <div class="space-y-2.5">
                    <a href="{{ route('admin.deals.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-red-50/60 hover:bg-red-100/80 border border-red-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-red-600 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">مجلة عروض اليوم والتخفيضات</strong>
                            <span class="text-[11px] text-gray-500 font-medium">إدارة تخفيضات اليوم ونسب الخصم</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.branches.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-orange-50/60 hover:bg-orange-100/80 border border-orange-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">فروع العامرية (4 فروع)</strong>
                            <span class="text-[11px] text-gray-500 font-medium">تحديث أرقام ومواعيد عمل الفروع والموقع</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-emerald-50/60 hover:bg-emerald-100/80 border border-emerald-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-emerald-700 transition-colors">التقارير الشاملة والمبيعات</strong>
                            <span class="text-[11px] text-gray-500 font-medium">تحليلات الأداء والفترات وتفاصيل الإيرادات</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.bundles.create') }}" 
                       class="flex items-start gap-3 p-3 rounded-2xl bg-amber-50/60 hover:bg-amber-100/80 border border-amber-200/70 transition-all group">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-gray-900 block group-hover:text-red-600 transition-colors">إنشاء عرض مجمع (Bundle)</strong>
                            <span class="text-[11px] text-gray-500 font-medium">دمج عدة أصناف بلدية بسعر عائلي مخفض</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection