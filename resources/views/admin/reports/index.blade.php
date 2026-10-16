@extends('layouts.admin')

@section('title', 'التقارير الشاملة وتحليلات المبيعات وصالات العرض')

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ viewMode: 'chart', activeBranchTab: 'all' }">

    {{-- 1. Top Header Bar & Period Filter --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">التقارير والمبيعات</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-serif font-bold text-[#2B2118] tracking-tight">
                التقارير المالية وتحليلات المبيعات
            </h1>
            <p class="text-xs sm:text-sm text-[#6B5642] mt-1">
                إيرادات صالات العرض، مبيعات اليوم، حركة طلبات الشحن والتوصيل، ومعدلات نمو السلات.
            </p>
        </div>

        {{-- Period Filters --}}
        <div class="flex items-center gap-1.5 bg-[#FBF6EA] border border-[#EADFC8] p-1.5 rounded-2xl shrink-0 overflow-x-auto shadow-sm">
            <a href="{{ route('admin.reports.index', ['period' => 'today']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-medium transition-all whitespace-nowrap {{ $period === 'today' ? 'bg-[#382416] text-[#FBF6EA] shadow-sm' : 'text-[#6B5642] hover:text-[#2B2118] hover:bg-white' }}">
                اليوم
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'week']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-medium transition-all whitespace-nowrap {{ $period === 'week' ? 'bg-[#382416] text-[#FBF6EA] shadow-sm' : 'text-[#6B5642] hover:text-[#2B2118] hover:bg-white' }}">
                هذا الأسبوع
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'month']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-medium transition-all whitespace-nowrap {{ $period === 'month' ? 'bg-[#382416] text-[#FBF6EA] shadow-sm' : 'text-[#6B5642] hover:text-[#2B2118] hover:bg-white' }}">
                هذا الشهر
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'year']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-medium transition-all whitespace-nowrap {{ $period === 'year' ? 'bg-[#382416] text-[#FBF6EA] shadow-sm' : 'text-[#6B5642] hover:text-[#2B2118] hover:bg-white' }}">
                هذا العام
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'all']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-medium transition-all whitespace-nowrap {{ $period === 'all' ? 'bg-[#382416] text-[#FBF6EA] shadow-sm' : 'text-[#6B5642] hover:text-[#2B2118] hover:bg-white' }}">
                الكل
            </a>
        </div>
    </div>

    {{-- 2. Master KPI Summary Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Total Sales Revenue -->
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-[#8F7B63]">إجمالي المبيعات المحققة</span>
                <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg">
                    💰
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-serif font-bold text-[#2B2118] tabular-nums">
                    {{ number_format($reports['total_sales'], 2) }} <span class="text-xs font-normal text-[#8F7B63]">ج.م</span>
                </div>
                <div class="mt-2 text-[11px] text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-xl inline-flex items-center gap-1 font-medium">
                    <span>✓</span>
                    <span>الطلبات المعتمدة وغير الملغاة</span>
                </div>
            </div>
        </div>

        <!-- Total Orders Count -->
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-[#8F7B63]">عدد الطلبات المسجلة</span>
                <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg">
                    🛍️
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-serif font-bold text-[#2B2118] tabular-nums">
                    {{ number_format($reports['total_orders']) }} <span class="text-xs font-normal text-[#8F7B63]">طلب</span>
                </div>
                <div class="mt-2 text-[11px] text-[#6B5642] flex items-center justify-between font-medium bg-[#FBF6EA] px-2.5 py-1 rounded-xl border border-[#EADFC8]">
                    <span>مكتملة: <strong class="text-emerald-800">{{ $reports['delivered_orders'] }}</strong></span>
                    <span>•</span>
                    <span>ملغاة: <strong class="text-[#A9552F]">{{ $reports['cancelled_orders'] }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Average Order Value (AOV) -->
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-[#8F7B63]">متوسط قيمة الفاتورة (AOV)</span>
                <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg">
                    📊
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-serif font-bold text-[#A9552F] tabular-nums">
                    {{ number_format($reports['avg_order_value'], 2) }} <span class="text-xs font-normal text-[#8F7B63]">ج.م</span>
                </div>
                <div class="mt-2 text-[11px] text-[#8F7B63] font-medium">
                    <span>معدل إنفاق الزبون لكل طلب خلال الفترة</span>
                </div>
            </div>
        </div>

        <!-- WhatsApp Funnel Conversion -->
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-[#8F7B63]">معدل إتمام طلبات الواتساب</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center text-lg">
                    💬
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-serif font-bold text-emerald-800 tabular-nums">
                    {{ $reports['whatsapp']['conversion_rate'] }}%
                </div>
                <div class="mt-2 text-[11px] text-[#6B5642] flex items-center justify-between font-medium">
                    <span>نقرات الطلب: <strong class="text-[#2B2118]">{{ $reports['whatsapp']['checkout'] }}</strong></span>
                    <span>استفسارات: <strong class="text-[#2B2118]">{{ $reports['whatsapp']['inquiry'] }}</strong></span>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. Detailed Branch Performance Breakdown --}}
    <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[#EADFC8] pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-bold text-[#A9552F] uppercase tracking-wider">🏛️ أداء صالات العرض ومنافذ البيع</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-serif font-bold text-[#2B2118]">
                    تقرير إيرادات ومبيعات الفروع
                </h2>
                <p class="text-xs text-[#6B5642] mt-0.5">
                    متابعة حركة المبيعات اليومية وتوزيع الإيرادات المحققة لكل فرع وبوتيك
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-xl bg-[#FBF6EA] border border-[#EADFC8] text-[#2B2118] text-xs font-medium">
                    {{ count($reports['branch_metrics'] ?? []) }} فروع وصالات مسجلة
                </span>
            </div>
        </div>

        {{-- Branch Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @forelse($reports['branch_metrics'] ?? [] as $index => $bm)
                <div class="p-5 rounded-2xl border transition-all flex flex-col justify-between space-y-4 relative overflow-hidden {{ $index === 0 && $bm['total_revenue'] > 0 ? 'bg-[#FBF6EA] border-[#CDB78F] shadow-sm' : 'bg-white border-[#EADFC8] hover:border-[#CDB78F]' }}">
                    
                    @if($index === 0 && $bm['total_revenue'] > 0)
                        <div class="absolute top-3 end-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#382416] text-[#FBF6EA] uppercase tracking-wider">
                                🥇 الأعلى مبيعاً
                            </span>
                        </div>
                    @endif

                    <div>
                        <!-- Branch Title & Address -->
                        <div class="flex items-center gap-2.5 mb-2">
                            <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-base font-bold shrink-0">
                                🏬
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-bold text-[#2B2118] truncate" title="{{ $bm['name'] }}">
                                    {{ $bm['name'] }}
                                </h4>
                                <span class="text-[10px] text-[#8F7B63] block truncate">{{ $bm['phone'] ?: 'بدون هاتف' }}</span>
                            </div>
                        </div>

                        <!-- Today Sales Highlight Pill -->
                        <div class="p-3 rounded-xl bg-white border border-[#EADFC8] mb-3 space-y-1">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-[#8F7B63] font-medium">مبيعات اليوم:</span>
                                <span class="font-bold text-emerald-800 tabular-nums text-sm">{{ number_format($bm['today_revenue'], 2) }} ج.م</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] text-[#8F7B63]">
                                <span>طلبات اليوم:</span>
                                <span class="font-bold text-[#2B2118]">{{ $bm['today_orders'] }} طلب</span>
                            </div>
                        </div>

                        <!-- Period Sales & Total Orders -->
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-[#8F7B63]">مبيعات الفترة:</span>
                                <span class="font-bold text-[#2B2118] tabular-nums text-sm">{{ number_format($bm['total_revenue'], 2) }} ج.م</span>
                            </div>

                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-[#8F7B63]">إجمالي الطلبات:</span>
                                <span class="font-bold text-[#2B2118]">{{ $bm['total_orders'] }} (مسلّم: {{ $bm['delivered_count'] }})</span>
                            </div>

                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-[#8F7B63]">توصيل شحن / استلام صالة:</span>
                                <span class="font-medium text-[#6B5642]">{{ $bm['delivery_orders'] }} 📦 / {{ $bm['pickup_orders'] }} 🛍️</span>
                            </div>

                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-[#8F7B63]">متوسط الفاتورة:</span>
                                <span class="font-bold text-[#A9552F] tabular-nums">{{ number_format($bm['avg_order_value'], 2) }} ج.م</span>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Share Progress Bar -->
                    <div class="pt-3 border-t border-[#EADFC8] space-y-1.5">
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="text-[#8F7B63]">الحصة من إجمالي المبيعات:</span>
                            <span class="text-[#2B2118] tabular-nums">{{ $bm['sales_share'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-[#F3E9D2] rounded-full overflow-hidden">
                            <div class="h-full bg-[#382416] rounded-full transition-all duration-500"
                                 style="width: {{ min(100, max(4, $bm['sales_share'])) }}%"></div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-4 p-8 text-center text-[#8F7B63]">
                    لا توجد بيانات صالات عرض مسجلة حالياً.
                </div>
            @endforelse
        </div>

        {{-- Detailed Branches Table --}}
        <div class="border border-[#EADFC8] rounded-xl overflow-hidden">
            <div class="bg-[#FBF6EA] px-5 py-3 border-b border-[#EADFC8] flex items-center justify-between">
                <span class="text-xs font-bold text-[#2B2118]">جدول مقارنة أداء صالات العرض التفصيلي</span>
                <span class="text-[11px] text-[#8F7B63] font-medium">محدّث لحظياً</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-right text-xs">
                    <thead class="bg-white text-[#6B5642] border-b border-[#EADFC8]">
                        <tr>
                            <th class="py-3 px-5 font-bold">الفرع</th>
                            <th class="py-3 px-4 font-bold">مبيعات اليوم</th>
                            <th class="py-3 px-4 font-bold">مبيعات الفترة</th>
                            <th class="py-3 px-4 font-bold">الطلبات الكلية</th>
                            <th class="py-3 px-4 font-bold">الطلبات المسلّمة</th>
                            <th class="py-3 px-4 font-bold">متوسط الفاتورة</th>
                            <th class="py-3 px-5 font-bold">الحصة من المبيعات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                        @foreach($reports['branch_metrics'] ?? [] as $bm)
                            <tr class="hover:bg-[#FDFBF7] transition-colors">
                                <td class="py-3.5 px-5 font-bold flex items-center gap-2">
                                    <span class="text-sm">🏬</span>
                                    <span>{{ $bm['name'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-emerald-800 tabular-nums">
                                    {{ number_format($bm['today_revenue'], 2) }} ج.م
                                </td>
                                <td class="py-3.5 px-4 font-bold tabular-nums">
                                    {{ number_format($bm['total_revenue'], 2) }} ج.م
                                </td>
                                <td class="py-3.5 px-4 font-medium text-[#6B5642]">
                                    {{ $bm['total_orders'] }} طلب
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 text-[11px] font-medium border border-emerald-200">
                                        {{ $bm['delivered_count'] }} طلب
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-[#A9552F] tabular-nums">
                                    {{ number_format($bm['avg_order_value'], 2) }} ج.م
                                </td>
                                <td class="py-3.5 px-5">
                                    <span class="px-2.5 py-1 rounded-full bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] text-[11px] font-bold">
                                        {{ $bm['sales_share'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- 4. Visual Charts & Sales Trend Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Revenue & Orders Trend --}}
        <div class="lg:col-span-2 bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm space-y-5 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EADFC8] pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg shrink-0">
                            📈
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-[#2B2118] text-base leading-tight">حركة المبيعات والطلبات اليومية</h3>
                            <p class="text-[11px] text-[#8F7B63] mt-0.5">تتبع نمو الإيرادات وحجم المشتريات على مدار الأيام</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex items-center bg-[#FBF6EA] border border-[#EADFC8] p-1 rounded-xl text-xs font-medium">
                            <button type="button" 
                                    @click="viewMode = 'chart'"
                                    :class="viewMode === 'chart' ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'text-[#6B5642] hover:text-[#2B2118]'"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer">
                                رسم بياني
                            </button>
                            <button type="button" 
                                    @click="viewMode = 'table'"
                                    :class="viewMode === 'table' ? 'bg-[#382416] text-[#FBF6EA] shadow-xs' : 'text-[#6B5642] hover:text-[#2B2118]'"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer">
                                جدول تفصيلي
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Highlights Strip --}}
                @php
                    $trendRevenueTotal = array_sum(array_column($reports['daily_trend'] ?? [], 'daily_revenue'));
                    $trendOrdersTotal = array_sum(array_column($reports['daily_trend'] ?? [], 'orders_count'));
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 my-4">
                    <div class="p-3 bg-[#FBF6EA] border border-[#EADFC8] rounded-xl flex items-center gap-3">
                        <span class="text-xl">💰</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-[#8F7B63] font-medium block">إجمالي إيراد الفترة:</span>
                            <span class="text-xs sm:text-sm font-bold text-[#2B2118] tabular-nums block truncate">{{ number_format($trendRevenueTotal, 2) }} ج.م</span>
                        </div>
                    </div>
                    <div class="p-3 bg-[#FBF6EA] border border-[#EADFC8] rounded-xl flex items-center gap-3">
                        <span class="text-xl">🛍️</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-[#8F7B63] font-medium block">إجمالي الطلبات:</span>
                            <span class="text-xs sm:text-sm font-bold text-[#2B2118] tabular-nums block truncate">{{ number_format($trendOrdersTotal) }} طلب</span>
                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 p-3 bg-[#FBF6EA] border border-[#EADFC8] rounded-xl flex items-center gap-3">
                        <span class="text-xl">🏛️</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-[#8F7B63] font-medium block">الفروع النشطة:</span>
                            <span class="text-xs sm:text-sm font-bold text-[#A9552F] block truncate">{{ count($reports['branch_metrics'] ?? []) }} فروع</span>
                        </div>
                    </div>
                </div>

                {{-- View 1: Chart.js Area Chart --}}
                <div x-show="viewMode === 'chart'" class="relative w-full h-72 sm:h-80 pt-2">
                    <canvas id="salesTrendChart" class="w-full h-full"></canvas>
                </div>

                {{-- View 2: Detailed Table --}}
                <div x-show="viewMode === 'table'" class="overflow-x-auto max-h-80 overflow-y-auto border border-[#EADFC8] rounded-xl">
                    <table class="w-full text-right text-xs">
                        <thead class="bg-[#FBF6EA] text-[#6B5642] sticky top-0 border-b border-[#EADFC8]">
                            <tr>
                                <th class="py-2.5 px-4 font-bold">التاريخ</th>
                                <th class="py-2.5 px-4 text-center font-bold">عدد الطلبات</th>
                                <th class="py-2.5 px-4 text-left font-bold">المبيعات اليومية</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                            @foreach(array_reverse($reports['daily_trend'] ?? []) as $day)
                                <tr class="hover:bg-[#FDFBF7] transition-colors">
                                    <td class="py-2.5 px-4 font-medium">
                                        {{ $day['date'] }}
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full {{ $day['orders_count'] > 0 ? 'bg-[#F3E9D2] text-[#2B2118] font-bold' : 'text-[#8F7B63]' }}">
                                            {{ $day['orders_count'] }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 text-left font-bold tabular-nums">
                                        {{ number_format($day['daily_revenue'], 2) }} ج.م
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        {{-- Order Status Donut Breakdown --}}
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center gap-2.5 border-b border-[#EADFC8] pb-4">
                    <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg">
                        🎯
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-[#2B2118] text-base leading-tight">توزيع حالات الطلبات</h3>
                        <p class="text-[11px] text-[#8F7B63] mt-0.5">مراحل الطلبات المسجلة خلال الفترة</p>
                    </div>
                </div>

                <div class="relative w-full h-52 my-3">
                    <canvas id="orderStatusChart" class="w-full h-full"></canvas>
                </div>

                {{-- Status Pills list --}}
                <div class="space-y-2 pt-2 border-t border-[#EADFC8] text-xs">
                    @php
                        $statusMeta = [
                            'delivered' => ['label' => 'تم التسليم', 'color' => 'bg-emerald-600'],
                            'confirmed' => ['label' => 'تم التأكيد', 'color' => 'bg-[#2B2118]'],
                            'processing' => ['label' => 'قيد التجهيز', 'color' => 'bg-[#A9552F]'],
                            'pending' => ['label' => 'قيد الانتظار', 'color' => 'bg-[#CDB78F]'],
                            'cancelled' => ['label' => 'ملغي ومسترجع', 'color' => 'bg-red-500'],
                        ];
                    @endphp

                    @foreach($statusMeta as $sKey => $sVal)
                        @php
                            $count = $reports['orders_by_status'][$sKey]['count'] ?? 0;
                            $amt = $reports['orders_by_status'][$sKey]['total_amount'] ?? 0;
                        @endphp
                        <div class="flex items-center justify-between text-xs py-1">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $sVal['color'] }}"></span>
                                <span class="font-medium text-[#6B5642]">{{ $sVal['label'] }}:</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-[#2B2118]">{{ $count }} طلب</span>
                                <span class="text-[11px] text-[#8F7B63]">({{ number_format($amt, 1) }} ج.م)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- 5. Bottom Grid: Top Selling Fashion Products & Inventory Alerts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top Selling Products -->
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#EADFC8] pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg">
                        👗
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-[#2B2118] text-base leading-tight">أكثر قطع الأزياء طلباً ومبيعاً</h3>
                        <p class="text-[11px] text-[#8F7B63] mt-0.5">المنتجات الأكثر رواجاً ومبيعاً في المتجر وصالات العرض</p>
                    </div>
                </div>

                <a href="{{ route('admin.products.index') }}" class="text-xs text-[#A9552F] font-bold hover:underline">
                    عرض الكتالوج ←
                </a>
            </div>

            <div class="space-y-3">
                @forelse($reports['top_products'] ?? [] as $index => $prod)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-[#FBF6EA] border border-[#EADFC8] hover:border-[#CDB78F] transition-all">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-lg bg-[#EADFC8] text-[#2B2118] flex items-center justify-center font-bold text-xs">
                                {{ $index + 1 }}
                            </span>
                            <div class="w-11 h-11 rounded-lg bg-white border border-[#EADFC8] p-0.5 flex items-center justify-center overflow-hidden">
                                @if(!empty($prod['image']))
                                    <img src="{{ $prod['image'] }}" alt="" class="w-full h-full object-cover rounded">
                                @else
                                    <span class="text-lg">👗</span>
                                @endif
                            </div>
                            <div>
                                <h5 class="font-bold text-xs text-[#2B2118]">{{ $prod['name'] }}</h5>
                                <span class="text-[11px] text-[#8F7B63]">تم بيع: <strong class="text-[#2B2118]">{{ $prod['total_sold'] }}</strong> قطعة</span>
                            </div>
                        </div>

                        <div class="text-left">
                            <span class="font-bold text-[#A9552F] tabular-nums text-xs block">
                                {{ number_format($prod['total_revenue'], 2) }} ج.م
                            </span>
                            <span class="text-[10px] text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded font-medium">إيراد محقق</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-[#8F7B63] text-xs">
                        لا توجد مبيعات مسجلة حتى الآن.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Inventory Alerts & Abandoned Carts -->
        <div class="bg-white border border-[#EADFC8] rounded-2xl p-6 sm:p-8 shadow-sm space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-[#EADFC8] pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] text-[#2B2118] flex items-center justify-center text-lg">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="font-serif font-bold text-[#2B2118] text-base leading-tight">تنبيهات المخزون والسلات المتروكة</h3>
                            <p class="text-[11px] text-[#8F7B63] mt-0.5">متابعة القطع منخفضة الرصيد وفرص استرداد السلات</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.abandoned-carts.index') }}" class="text-xs text-[#A9552F] font-bold hover:underline">
                        السلات المتروكة ←
                    </a>
                </div>

                <!-- Stock Alerts Strip -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-4 rounded-xl bg-red-50/50 border border-red-200/60 space-y-1">
                        <span class="text-[11px] font-medium text-[#A9552F]">قطع نفدت تماماً:</span>
                        <div class="text-2xl font-serif font-bold text-[#A9552F] tabular-nums">
                            {{ $reports['stock_metrics']['out_of_stock'] }} صنف
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-200/60 space-y-1">
                        <span class="text-[11px] font-medium text-amber-800">سلات قابلة للاسترداد:</span>
                        <div class="text-2xl font-serif font-bold text-amber-900 tabular-nums">
                            {{ $reports['abandoned']['total'] - $reports['abandoned']['recovered'] }} سلة
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products List -->
                <div class="space-y-2">
                    <span class="text-xs font-bold text-[#2B2118] block">قطع أوشكت على النفاد (5 قطع أو أقل):</span>
                    @forelse($reports['low_stock_products'] ?? [] as $low)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-[#FBF6EA] border border-[#EADFC8] text-xs">
                            <span class="font-medium text-[#2B2118]">{{ $low->name }}</span>
                            <span class="px-2 py-0.5 rounded-lg bg-red-50 text-[#A9552F] font-bold text-[11px]">
                                المتبقي: {{ $low->stock_quantity ?? $low->quantity }} قطع
                            </span>
                        </div>
                    @empty
                        <div class="p-3 text-center text-xs text-emerald-800 font-medium bg-emerald-50 rounded-xl border border-emerald-200">
                            ✓ جميع قطع الأزياء بالمخزون متوفرة بحالة جيدة.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Daily Sales & Orders Area Chart
        const dailyTrendData = @json($reports['daily_trend'] ?? []);
        const labels = dailyTrendData.map(d => d.date);
        const revenueData = dailyTrendData.map(d => d.daily_revenue);
        const ordersData = dailyTrendData.map(d => d.orders_count);

        const ctxTrend = document.getElementById('salesTrendChart');
        if (ctxTrend) {
            new Chart(ctxTrend.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'إجمالي المبيعات (ج.م)',
                            data: revenueData,
                            borderColor: '#382416',
                            backgroundColor: 'rgba(56, 36, 22, 0.08)',
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#382416',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'عدد الطلبات',
                            data: ordersData,
                            borderColor: '#A9552F',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [4, 4],
                            tension: 0.35,
                            pointBackgroundColor: '#A9552F',
                            pointRadius: 3,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', rtl: true, labels: { font: { family: 'Cairo', size: 11, weight: 'bold' }, color: '#2B2118' } },
                        tooltip: {
                            rtl: true,
                            titleFont: { family: 'Cairo', weight: 'bold' },
                            bodyFont: { family: 'Cairo' }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Cairo', size: 10 }, color: '#8F7B63' } },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            ticks: { font: { family: 'Cairo', size: 10 }, color: '#8F7B63' },
                            grid: { color: '#EADFC8' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { drawOnChartArea: false },
                            ticks: { precision: 0, font: { family: 'Cairo', size: 10 }, color: '#8F7B63' }
                        }
                    }
                }
            });
        }

        // 2. Order Status Doughnut Chart
        const ordersByStatus = @json($reports['orders_by_status'] ?? []);
        const statusKeys = ['delivered', 'confirmed', 'processing', 'pending', 'cancelled'];
        const statusLabels = ['تم التسليم', 'تم التأكيد', 'قيد التجهيز', 'قيد الانتظار', 'ملغي ومسترجع'];
        const statusColors = ['#15803d', '#2B2118', '#A9552F', '#CDB78F', '#dc2626'];
        const statusValues = statusKeys.map(k => ordersByStatus[k] ? ordersByStatus[k].count : 0);

        const ctxStatus = document.getElementById('orderStatusChart');
        if (ctxStatus) {
            new Chart(ctxStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusValues,
                        backgroundColor: statusColors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '72%'
                }
            });
        }
    });
</script>
@endpush
@endsection
