@extends('layouts.admin')

@section('title', __('التقارير الشاملة وتحليلات أداء الفروع والمبيعات'))

@section('content')
<div class="space-y-6 sm:space-y-8" x-data="{ viewMode: 'chart', activeBranchTab: 'all' }">

    {{-- 1. Top Header Bar & Period Filter --}}
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    <span>لوحة التقارير المالية ومبيعات فروع العامرية</span>
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                {{ __('التقارير المالية وتحليلات أداء الفروع') }}
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 font-medium mt-1">
                {{ __('إيرادات كل فرع، مبيعات اليوم، حركة الطلبات، ونشاط مندوبي التوصيل ومجلة العروض.') }}
            </p>
        </div>

        {{-- Period Filters --}}
        <div class="flex items-center gap-1.5 bg-canvas-base border border-orange-200 p-1.5 rounded-2xl shrink-0 overflow-x-auto no-scrollbar shadow-xs">
            <a href="{{ route('admin.reports.index', ['period' => 'today']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $period === 'today' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white' }}">
                {{ __('اليوم') }}
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'week']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $period === 'week' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white' }}">
                {{ __('هذا الأسبوع') }}
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'month']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $period === 'month' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white' }}">
                {{ __('هذا الشهر') }}
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'year']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $period === 'year' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white' }}">
                {{ __('هذا العام') }}
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'all']) }}" 
               class="px-4 py-2 rounded-xl text-xs font-black transition-all whitespace-nowrap {{ $period === 'all' ? 'bg-red-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-white' }}">
                {{ __('الكل') }}
            </a>
        </div>
    </div>

    {{-- 2. Master KPI Summary Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Total Sales Revenue -->
        <div class="bg-white border border-orange-100 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500">{{ __('إجمالي المبيعات المحققة') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-lg shadow-xs">
                    💰
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-gray-900 font-sans">
                    {{ number_format($reports['total_sales'], 2) }} ج.م
                </div>
                <div class="mt-2 text-[11px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-xl inline-flex items-center gap-1 font-bold">
                    <span>✓</span>
                    <span>{{ __('الطلبات المعتمدة وغير الملغاة') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Orders Count -->
        <div class="bg-white border border-orange-100 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500">{{ __('عدد الطلبات المسجلة') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-orange-50 border border-orange-200 text-orange-600 flex items-center justify-center text-lg shadow-xs">
                    📦
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-gray-900 font-sans">
                    {{ number_format($reports['total_orders']) }} طلب
                </div>
                <div class="mt-2 text-[11px] text-gray-600 flex items-center justify-between font-bold bg-canvas-base px-2.5 py-1 rounded-xl border border-orange-100">
                    <span>{{ __('مسلّمة:') }} <strong class="text-emerald-700">{{ $reports['delivered_orders'] }}</strong></span>
                    <span>•</span>
                    <span>{{ __('ملغاة:') }} <strong class="text-rose-600">{{ $reports['cancelled_orders'] }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Average Order Value (AOV) -->
        <div class="bg-white border border-orange-100 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500">{{ __('متوسط قيمة الطلب (AOV)') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg shadow-xs">
                    📊
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-red-600 font-sans">
                    {{ number_format($reports['avg_order_value'], 2) }} ج.م
                </div>
                <div class="mt-2 text-[11px] text-gray-500 font-medium">
                    <span>{{ __('معدل الإنفاق لكل زبون في الفترة') }}</span>
                </div>
            </div>
        </div>

        <!-- WhatsApp Funnel Conversion -->
        <div class="bg-white border border-orange-100 rounded-3xl p-5 sm:p-6 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500">{{ __('معدل إتمام طلبات واتساب') }}</span>
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-lg shadow-xs">
                    💬
                </div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black text-emerald-700 font-sans">
                    {{ $reports['whatsapp']['conversion_rate'] }}%
                </div>
                <div class="mt-2 text-[11px] text-gray-500 flex items-center justify-between font-medium">
                    <span>{{ __('نقرات إتمام:') }} <strong class="text-gray-900">{{ $reports['whatsapp']['checkout'] }}</strong></span>
                    <span>{{ __('استفسار:') }} <strong class="text-gray-900">{{ $reports['whatsapp']['inquiry'] }}</strong></span>
                </div>
            </div>
        </div>

    </div>

    {{-- 3. BRAND NEW: Detailed Branch Performance Breakdown (فروع ألبان وسوبرماركت التوحيد بالعامرية) --}}
    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-orange-100 pb-5">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-black text-red-600 uppercase tracking-wider">🏢 أداء الفروع الأربعة بالعامرية</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-black text-gray-900">
                    {{ __('تقرير مبيعات وإيرادات كل فرع') }}
                </h2>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    {{ __('متابعة دقيقة لمبيعات اليوم وإجمالي إيرادات الفترة لكل فرع من فروع العامرية.') }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="px-3.5 py-1.5 rounded-xl bg-orange-50 border border-orange-200 text-orange-800 text-xs font-black">
                    {{ count($reports['branch_metrics'] ?? []) }} فروع مسجلة
                </span>
            </div>
        </div>

        {{-- 4 Master Branch Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @forelse($reports['branch_metrics'] ?? [] as $index => $bm)
                <div class="p-5 rounded-3xl border transition-all flex flex-col justify-between space-y-4 relative overflow-hidden {{ $index === 0 && $bm['total_revenue'] > 0 ? 'bg-gradient-to-b from-orange-50/70 to-white border-orange-300 ring-2 ring-orange-400/20 shadow-sm' : 'bg-canvas-base/40 border-orange-100 hover:border-orange-200 hover:bg-white shadow-2xs' }}">
                    
                    @if($index === 0 && $bm['total_revenue'] > 0)
                        <div class="absolute top-3 end-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-orange-500 text-white uppercase tracking-wider shadow-2xs">
                                🥇 الأعلى مبيعاً
                            </span>
                        </div>
                    @endif

                    <div>
                        <!-- Branch Title & Address -->
                        <div class="flex items-center gap-2.5 mb-2">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-red-600 to-orange-500 text-white flex items-center justify-center text-base font-black shadow-xs shrink-0">
                                🏬
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-black text-gray-900 truncate" title="{{ $bm['name'] }}">
                                    {{ $bm['name'] }}
                                </h4>
                                <span class="text-[10px] text-gray-500 block truncate">{{ $bm['phone'] ?: 'بدون هاتف' }}</span>
                            </div>
                        </div>

                        <!-- Today Sales Highlight Pill -->
                        <div class="p-3 rounded-2xl bg-white border border-orange-100 shadow-2xs mb-3 space-y-1">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-gray-500 font-bold">مبيعات الفرع اليوم:</span>
                                <span class="font-black text-emerald-700 font-sans text-sm">{{ number_format($bm['today_revenue'], 2) }} ج.م</span>
                            </div>
                            <div class="flex justify-between items-center text-[10px] text-gray-400">
                                <span>طلبات اليوم:</span>
                                <span class="font-bold text-gray-700">{{ $bm['today_orders'] }} طلب</span>
                            </div>
                        </div>

                        <!-- Period Sales & Total Orders -->
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-500 font-medium">مبيعات الفترة ({{ $period }}):</span>
                                <span class="font-black text-gray-900 font-sans text-sm">{{ number_format($bm['total_revenue'], 2) }} ج.م</span>
                            </div>

                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-gray-500">إجمالي الطلبات:</span>
                                <span class="font-bold text-gray-800">{{ $bm['total_orders'] }} (مسلّم: {{ $bm['delivered_count'] }})</span>
                            </div>

                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-gray-500">توصيل / استلام فرع:</span>
                                <span class="font-bold text-gray-700">{{ $bm['delivery_orders'] }} 🛵 / {{ $bm['pickup_orders'] }} 🛍️</span>
                            </div>

                            <div class="flex justify-between items-center text-[11px]">
                                <span class="text-gray-500">متوسط الطلب (AOV):</span>
                                <span class="font-bold text-red-600 font-sans">{{ number_format($bm['avg_order_value'], 2) }} ج.م</span>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Share Progress Bar -->
                    <div class="pt-3 border-t border-orange-100/70 space-y-1.5">
                        <div class="flex justify-between items-center text-[10px] font-bold">
                            <span class="text-gray-500">الحصة من مبيعات المتجر:</span>
                            <span class="text-red-700 font-sans">{{ $bm['sales_share'] }}%</span>
                        </div>
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-red-600 to-orange-500 rounded-full transition-all duration-500"
                                 style="width: {{ min(100, max(4, $bm['sales_share'])) }}%"></div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-4 p-8 text-center text-gray-500">
                    لا توجد بيانات فروع مسجلة حالياً.
                </div>
            @endforelse
        </div>

        {{-- Detailed Branches Table --}}
        <div class="border border-orange-100 rounded-2xl overflow-hidden">
            <div class="bg-canvas-base px-5 py-3 border-b border-orange-100 flex items-center justify-between">
                <span class="text-xs font-black text-gray-900">جدول مقارنة أداء الفروع التفصيلي</span>
                <span class="text-[11px] text-gray-500 font-medium">محدّث لحظياً</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs">
                    <thead class="bg-white text-gray-500 uppercase text-[11px] border-b border-orange-100">
                        <tr>
                            <th class="py-3 px-5 text-start font-bold">الفرع</th>
                            <th class="py-3 px-4 text-start font-bold">مبيعات اليوم</th>
                            <th class="py-3 px-4 text-start font-bold">مبيعات الفترة</th>
                            <th class="py-3 px-4 text-start font-bold">الطلبات الكلية</th>
                            <th class="py-3 px-4 text-start font-bold">الطلبات المسلّمة</th>
                            <th class="py-3 px-4 text-start font-bold">متوسط الطلب</th>
                            <th class="py-3 px-5 text-start font-bold">الحصة من المبيعات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-orange-100">
                        @foreach($reports['branch_metrics'] ?? [] as $bm)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="py-3.5 px-5 font-black text-gray-900 flex items-center gap-2">
                                    <span class="text-sm">🏬</span>
                                    <span>{{ $bm['name'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-black text-emerald-700 font-sans">
                                    {{ number_format($bm['today_revenue'], 2) }} ج.م
                                </td>
                                <td class="py-3.5 px-4 font-black text-gray-900 font-sans">
                                    {{ number_format($bm['total_revenue'], 2) }} ج.م
                                </td>
                                <td class="py-3.5 px-4 font-bold text-gray-700">
                                    {{ $bm['total_orders'] }} طلب
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold">
                                        {{ $bm['delivered_count'] }} طلب
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-red-600 font-sans">
                                    {{ number_format($bm['avg_order_value'], 2) }} ج.م
                                </td>
                                <td class="py-3.5 px-5 font-bold text-gray-800">
                                    <span class="px-2.5 py-1 rounded-full bg-orange-50 border border-orange-200 text-orange-800 text-[11px] font-black">
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
        
        {{-- Revenue & Orders Trend (Interactive Chart + Detailed Table) --}}
        <div class="lg:col-span-2 bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-5 flex flex-col justify-between">
            <div>
                {{-- Chart Header with Switcher --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-orange-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-orange-50 border border-orange-200 text-red-600 flex items-center justify-center text-lg shrink-0 shadow-xs">
                            📈
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-base leading-tight">{{ __('حركة المبيعات والطلبات اليومية') }}</h3>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ __('متابعة نمو الإيرادات وحجم الطلبات على مدار الأيام') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="flex items-center bg-canvas-base border border-orange-200 p-1 rounded-xl text-xs font-black">
                            <button type="button" 
                                    @click="viewMode = 'chart'"
                                    :class="viewMode === 'chart' ? 'bg-red-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer">
                                {{ __('رسم بياني') }}
                            </button>
                            <button type="button" 
                                    @click="viewMode = 'table'"
                                    :class="viewMode === 'table' ? 'bg-red-600 text-white shadow-xs' : 'text-gray-600 hover:text-gray-900'"
                                    class="px-3 py-1 rounded-lg transition-all cursor-pointer">
                                {{ __('جدول تفصيلي') }}
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Trend Highlights summary strip --}}
                @php
                    $trendRevenueTotal = array_sum(array_column($reports['daily_trend'] ?? [], 'daily_revenue'));
                    $trendOrdersTotal = array_sum(array_column($reports['daily_trend'] ?? [], 'orders_count'));
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 my-4">
                    <div class="p-3 bg-canvas-base border border-orange-100 rounded-2xl flex items-center gap-3">
                        <span class="text-xl">💰</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-gray-500 font-bold block">{{ __('إجمالي إيراد الفترة:') }}</span>
                            <span class="text-xs sm:text-sm font-black text-gray-900 font-sans block truncate">{{ number_format($trendRevenueTotal, 2) }} ج.م</span>
                        </div>
                    </div>
                    <div class="p-3 bg-canvas-base border border-orange-100 rounded-2xl flex items-center gap-3">
                        <span class="text-xl">📦</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-gray-500 font-bold block">{{ __('إجمالي الطلبات:') }}</span>
                            <span class="text-xs sm:text-sm font-black text-gray-900 font-sans block truncate">{{ number_format($trendOrdersTotal) }} {{ __('طلب') }}</span>
                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 p-3 bg-canvas-base border border-orange-100 rounded-2xl flex items-center gap-3">
                        <span class="text-xl">🏪</span>
                        <div class="min-w-0">
                            <span class="text-[10px] text-gray-500 font-bold block">{{ __('المبيعات حسب الفروع:') }}</span>
                            <span class="text-xs sm:text-sm font-black text-red-600 block truncate">{{ count($reports['branch_metrics'] ?? []) }} فروع</span>
                        </div>
                    </div>
                </div>

                {{-- View 1: Chart.js Area Chart --}}
                <div x-show="viewMode === 'chart'" class="relative w-full h-72 sm:h-80 pt-2">
                    <canvas id="salesTrendChart" class="w-full h-full"></canvas>
                </div>

                {{-- View 2: Detailed Day-by-Day Table --}}
                <div x-show="viewMode === 'table'" class="overflow-x-auto max-h-80 overflow-y-auto border border-orange-100 rounded-2xl">
                    <table class="w-full text-start text-xs">
                        <thead class="bg-canvas-base text-gray-500 sticky top-0 uppercase text-[11px] border-b border-orange-100">
                            <tr>
                                <th class="py-2.5 px-4 text-start font-bold">{{ __('التاريخ') }}</th>
                                <th class="py-2.5 px-4 text-center font-bold">{{ __('عدد الطلبات') }}</th>
                                <th class="py-2.5 px-4 text-end font-bold">{{ __('المبيعات اليومية') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-100">
                            @foreach(array_reverse($reports['daily_trend'] ?? []) as $day)
                                <tr class="hover:bg-orange-50/30 transition-colors">
                                    <td class="py-2.5 px-4 font-bold text-gray-900 font-sans">
                                        {{ $day['date'] }}
                                    </td>
                                    <td class="py-2.5 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded-full {{ $day['orders_count'] > 0 ? 'bg-orange-100 text-orange-800 font-bold' : 'text-gray-400' }}">
                                            {{ $day['orders_count'] }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-4 text-end font-black text-gray-900 font-sans">
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
        <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs flex flex-col justify-between space-y-4">
            <div>
                <div class="flex items-center gap-2.5 border-b border-orange-100 pb-4">
                    <div class="w-10 h-10 rounded-2xl bg-orange-50 border border-orange-200 text-orange-600 flex items-center justify-center text-lg shadow-xs">
                        🎯
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-base leading-tight">{{ __('توزيع حالات الطلبات') }}</h3>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ __('مراحل الطلبات المسجلة خلال الفترة') }}</p>
                    </div>
                </div>

                <div class="relative w-full h-52 my-3">
                    <canvas id="orderStatusChart" class="w-full h-full"></canvas>
                </div>

                {{-- Status Pills list --}}
                <div class="space-y-2 pt-2 border-t border-orange-100 text-xs">
                    @php
                        $statusMeta = [
                            'delivered' => ['label' => 'تم التسليم', 'color' => 'bg-emerald-500', 'textColor' => 'text-emerald-700'],
                            'confirmed' => ['label' => 'تم التأكيد', 'color' => 'bg-blue-500', 'textColor' => 'text-blue-700'],
                            'processing' => ['label' => 'قيد التجهيز', 'color' => 'bg-amber-500', 'textColor' => 'text-amber-700'],
                            'pending' => ['label' => 'قيد الانتظار', 'color' => 'bg-orange-500', 'textColor' => 'text-orange-700'],
                            'cancelled' => ['label' => 'ملغي', 'color' => 'bg-rose-500', 'textColor' => 'text-rose-700'],
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
                                <span class="font-bold text-gray-700">{{ $sVal['label'] }}:</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="font-black text-gray-900">{{ $count }} طلب</span>
                                <span class="text-[11px] text-gray-400 font-sans">({{ number_format($amt, 1) }} ج.م)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- 5. Bottom Grid: Top Selling Products & Stock Alerts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Top Selling Products -->
        <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-orange-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-2xl bg-red-50 border border-red-200 text-red-600 flex items-center justify-center text-lg shadow-xs">
                        🥛
                    </div>
                    <div>
                        <h3 class="font-black text-gray-900 text-base leading-tight">{{ __('أكثر المنتجات طلباً ومبيعاً') }}</h3>
                        <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ __('المنتجات الأعلى مبيعاً في فروع التوحيد') }}</p>
                    </div>
                </div>

                <a href="{{ route('admin.products.index') }}" class="text-xs text-red-600 font-bold hover:underline">
                    {{ __('عرض الكتالوج ←') }}
                </a>
            </div>

            <div class="space-y-3">
                @forelse($reports['top_products'] ?? [] as $index => $prod)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-canvas-base/50 border border-orange-100 hover:border-orange-200 transition-all">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-xl bg-orange-100 text-orange-900 flex items-center justify-center font-black text-xs">
                                {{ $index + 1 }}
                            </span>
                            <div class="w-11 h-11 rounded-xl bg-white border border-orange-100 p-1 flex items-center justify-center overflow-hidden">
                                @if(!empty($prod['image']))
                                    <img src="{{ $prod['image'] }}" alt="" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <span class="text-lg">🥛</span>
                                @endif
                            </div>
                            <div>
                                <h5 class="font-black text-xs text-gray-900">{{ $prod['name'] }}</h5>
                                <span class="text-[11px] text-gray-500">تم بيع: <strong class="text-gray-800">{{ $prod['total_sold'] }}</strong> وحدة</span>
                            </div>
                        </div>

                        <div class="text-end">
                            <span class="font-black text-red-600 font-sans text-xs block">
                                {{ number_format($prod['total_revenue'], 2) }} ج.م
                            </span>
                            <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-bold">إيراد محقق</span>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-500 text-xs font-medium">
                        {{ __('لا توجد مبيعات مسجلة حتى الآن.') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Inventory Alerts & Abandoned Carts -->
        <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4 flex flex-col justify-between">
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-orange-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-lg shadow-xs">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="font-black text-gray-900 text-base leading-tight">{{ __('تنبيهات المخزون والسلات') }}</h3>
                            <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ __('متابعة الأصناف منخفضة الكمية وفرص الاسترداد') }}</p>
                        </div>
                    </div>

                    <a href="{{ route('admin.abandoned-carts.index') }}" class="text-xs text-red-600 font-bold hover:underline">
                        {{ __('السلات المتروكة ←') }}
                    </a>
                </div>

                <!-- Stock Alerts Strip -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200 space-y-1">
                        <span class="text-[11px] font-bold text-rose-800">أصناف نفدت تماماً:</span>
                        <div class="text-2xl font-black text-rose-700 font-sans">
                            {{ $reports['stock_metrics']['out_of_stock'] }} صنف
                        </div>
                    </div>
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200 space-y-1">
                        <span class="text-[11px] font-bold text-amber-800">سلات قابلة للاسترداد:</span>
                        <div class="text-2xl font-black text-amber-700 font-sans">
                            {{ $reports['abandoned']['total'] - $reports['abandoned']['recovered'] }} سلة
                        </div>
                    </div>
                </div>

                <!-- Low Stock Products List -->
                <div class="space-y-2">
                    <span class="text-xs font-black text-gray-700 block">أصناف أوشكت على النفاد (5 قطع أو أقل):</span>
                    @forelse($reports['low_stock_products'] ?? [] as $low)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-canvas-base border border-orange-100 text-xs">
                            <span class="font-bold text-gray-800">{{ $low->name }}</span>
                            <span class="px-2 py-0.5 rounded-lg bg-rose-100 text-rose-800 font-black text-[11px]">
                                المتبقي: {{ $low->stock_quantity ?? $low->quantity }} قطع
                            </span>
                        </div>
                    @empty
                        <div class="p-3 text-center text-xs text-emerald-700 font-bold bg-emerald-50 rounded-xl border border-emerald-200">
                            ✓ جميع الأصناف بالمخزون بحالة ممتازة وكافية.
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
                            borderColor: '#DC2626',
                            backgroundColor: 'rgba(220, 38, 38, 0.08)',
                            borderWidth: 3,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#DC2626',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'عدد الطلبات',
                            data: ordersData,
                            borderColor: '#F97316',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            tension: 0.35,
                            pointBackgroundColor: '#F97316',
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
                        legend: { position: 'top', rtl: true, labels: { font: { family: 'Cairo', size: 11, weight: 'bold' } } },
                        tooltip: {
                            rtl: true,
                            titleFont: { family: 'Cairo', weight: 'bold' },
                            bodyFont: { family: 'Cairo' }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { family: 'Cairo', size: 10 } } },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            ticks: { font: { family: 'Cairo', size: 10 } },
                            grid: { color: '#F3F4F6' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            grid: { drawOnChartArea: false },
                            ticks: { precision: 0, font: { family: 'Cairo', size: 10 } }
                        }
                    }
                }
            });
        }

        // 2. Order Status Doughnut Chart
        const ordersByStatus = @json($reports['orders_by_status'] ?? []);
        const statusKeys = ['delivered', 'confirmed', 'processing', 'pending', 'cancelled'];
        const statusLabels = ['تم التسليم', 'تم التأكيد', 'قيد التجهيز', 'قيد الانتظار', 'ملغي'];
        const statusColors = ['#10B981', '#3B82F6', '#F59E0B', '#F97316', '#EF4444'];
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
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush
@endsection
