@extends('layouts.admin')

@section('title', 'ملف العميل: ' . $client->name)

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.clients.index') }}" class="hover:text-[#2B2118] transition-colors">العملاء</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">{{ $client->name }}</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">{{ $client->name }}</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">الملف التعريفي وسجل المشتريات والطلبات السابقة</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.clients.edit', $client->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span>تعديل البيانات</span>
            </a>
            <a href="{{ route('admin.clients.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#6B5642] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>العودة</span>
            </a>
        </div>
    </div>

    <!-- Client Info & Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Profile Details Card -->
        <div class="bg-white rounded-2xl border border-[#EADFC8] p-6 shadow-sm space-y-6">
            <div class="flex items-center gap-4 border-b border-[#EADFC8] pb-4">
                <div class="w-14 h-14 rounded-2xl bg-[#F3E9D2] border border-[#EADFC8] flex items-center justify-center text-[#2B2118] font-serif font-bold text-xl">
                    {{ mb_substr($client->name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-serif font-bold text-lg text-[#2B2118]">{{ $client->name }}</h3>
                    <p class="text-xs text-[#8F7B63] mt-0.5">عميل منذ {{ $client->created_at ? $client->created_at->format('Y/m/d') : '—' }}</p>
                </div>
            </div>

            <div class="space-y-4 text-sm">
                <div class="flex items-start gap-3">
                    <span class="text-[#8F7B63] w-20 flex-shrink-0 text-xs font-bold pt-0.5">رقم الهاتف:</span>
                    <a href="tel:{{ $client->phone }}" class="text-[#2B2118] font-medium hover:text-[#A9552F] dir-ltr text-right transition-colors">
                        {{ $client->phone }}
                    </a>
                </div>

                <div class="flex items-start gap-3">
                    <span class="text-[#8F7B63] w-20 flex-shrink-0 text-xs font-bold pt-0.5">البريد:</span>
                    <a href="mailto:{{ $client->email }}" class="text-[#2B2118] hover:text-[#A9552F] transition-colors break-all">
                        {{ $client->email }}
                    </a>
                </div>

                <div class="flex items-start gap-3">
                    <span class="text-[#8F7B63] w-20 flex-shrink-0 text-xs font-bold pt-0.5">العنوان:</span>
                    <span class="text-[#2B2118] leading-relaxed">
                        {{ $client->address ?: 'لم يتم تسجيل عنوان محدد' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Metrics Overview -->
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Orders -->
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-[#8F7B63]">إجمالي الطلبات</p>
                    <p class="text-3xl font-serif font-bold text-[#2B2118] mt-2">{{ $client->orders->count() }}</p>
                </div>
                <div class="mt-4 pt-3 border-t border-[#F3E9D2] text-[11px] text-[#6B5642]">
                    طلبات مسجلة باسم هذا العميل
                </div>
            </div>

            <!-- Total Spent -->
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-[#8F7B63]">إجمالي المشتريات</p>
                    <p class="text-3xl font-serif font-bold text-[#2B2118] mt-2">
                        {{ number_format($client->orders->sum('total_price'), 2) }}
                        <span class="text-sm font-normal text-[#8F7B63]">ج.م</span>
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-[#F3E9D2] text-[11px] text-[#6B5642]">
                    القيمة الإجمالية لطلبات العميل
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="bg-white rounded-2xl border border-[#EADFC8] p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <p class="text-xs font-bold text-[#8F7B63]">متوسط قيمة الطلب</p>
                    <p class="text-3xl font-serif font-bold text-[#2B2118] mt-2">
                        @php
                            $avg = $client->orders->count() > 0 ? ($client->orders->sum('total_price') / $client->orders->count()) : 0;
                        @endphp
                        {{ number_format($avg, 2) }}
                        <span class="text-sm font-normal text-[#8F7B63]">ج.م</span>
                    </p>
                </div>
                <div class="mt-4 pt-3 border-t border-[#F3E9D2] text-[11px] text-[#6B5642]">
                    معدل إنفاق العميل للطلب الواحد
                </div>
            </div>
        </div>
    </div>

    <!-- Orders History Table -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm overflow-hidden">
        <div class="p-5 border-b border-[#EADFC8] flex items-center justify-between">
            <h2 class="text-base font-serif font-bold text-[#2B2118]">سجل طلبات العميل</h2>
            <span class="text-xs text-[#8F7B63] font-medium">{{ $client->orders->count() }} طلب</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-[#FBF6EA] border-b border-[#EADFC8] text-xs font-bold text-[#6B5642]">
                        <th class="py-3 px-4">رقم الطلب</th>
                        <th class="py-3 px-4">تاريخ الطلب</th>
                        <th class="py-3 px-4">حالة الطلب</th>
                        <th class="py-3 px-4">إجمالي المبلغ</th>
                        <th class="py-3 px-4 text-center">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFC8] text-sm text-[#2B2118]">
                    @forelse($client->orders as $order)
                        <tr class="hover:bg-[#FDFBF7] transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-xs text-[#2B2118]">
                                #{{ $order->id }}
                            </td>
                            <td class="py-3.5 px-4 text-xs text-[#6B5642]">
                                {{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @php
                                    $statusMap = [
                                        'pending' => ['bg' => 'bg-[#F3E9D2]', 'text' => 'text-[#6B5642]', 'label' => 'قيد الانتظار'],
                                        'processing' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'label' => 'قيد التجهيز'],
                                        'shipped' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'label' => 'تم الشحن'],
                                        'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'label' => 'مكتمل ومستلم'],
                                        'cancelled' => ['bg' => 'bg-red-50', 'text' => 'text-[#A9552F]', 'label' => 'ملغي'],
                                    ];
                                    $st = $statusMap[$order->status] ?? ['bg' => 'bg-[#F3E9D2]', 'text' => 'text-[#2B2118]', 'label' => $order->status];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $st['bg'] }} {{ $st['text'] }}">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-xs text-[#2B2118]">
                                {{ number_format($order->total_price, 2) }} ج.م
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium transition-colors">
                                    <span>تفاصيل الطلب</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-[#8F7B63]">
                                لا توجد طلبات سابقة مسجلة لهذا العميل حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
