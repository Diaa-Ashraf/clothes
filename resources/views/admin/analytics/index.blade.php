@extends('layouts.admin')

@section('content')
<div class="space-y-6">
 {{-- Header --}}
 <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
 <div>
 <h2 class="text-2xl font-bold text-[#1F2937] tracking-tight"> تحليلات واتساب | WhatsApp Analytics</h2>
 <p class="text-sm text-gray-500 mt-0.5">تتبع شامل وفوري لنقرات الشراء وإتمام الطلب والاستفسار عن المنتجات عبر تطبيق واتساب.</p>
 </div>
 </div>

 {{-- 3 Summary Cards --}}
 <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 shadow-sm flex items-center justify-between">
 <div>
 <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">إجمالي نقرات واتساب</span>
 <h3 class="text-3xl font-black text-[#1F2937] mt-1">{{ number_format($metrics['total_whatsapp_clicks'] ?? 0) }}</h3>
 <span class="inline-flex items-center gap-1.5 mt-2 text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
 <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
 جميع التفاعلات المسجلة
 </span>
 </div>
 <div class="w-12 h-12 rounded-xl bg-[bg-canvas-base] border border-[border-orange-100] text-[#DC2626] flex items-center justify-center text-xl">
 
 </div>
 </div>

 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 shadow-sm flex items-center justify-between">
 <div>
 <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">إتمام الطلب (Checkout)</span>
 <h3 class="text-3xl font-black text-emerald-600 mt-1">{{ number_format($metrics['checkout_clicks'] ?? 0) }}</h3>
 <span class="inline-flex items-center gap-1.5 mt-2 text-xs font-medium text-gray-500 bg-[bg-canvas-base] border border-[border-orange-100] px-2 py-0.5 rounded-full">
 زبائن بطلب معبأ للسلة
 </span>
 </div>
 <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center text-xl">
 
 </div>
 </div>

 <div class="bg-white border border-[border-orange-100] rounded-2xl p-6 shadow-sm flex items-center justify-between">
 <div>
 <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block">نقرات اليوم</span>
 <h3 class="text-3xl font-black text-[#DC2626] mt-1">{{ number_format($metrics['today_whatsapp_clicks'] ?? 0) }}</h3>
 <span class="inline-flex items-center gap-1.5 mt-2 text-xs font-medium text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">
 نشاط الـ 24 ساعة الماضية
 </span>
 </div>
 <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-xl">
 
 </div>
 </div>
 </div>

 {{-- Table of Recent Clicks with Bulk Selection & Delete Actions --}}
 <div class="bg-white border border-[border-orange-100] rounded-2xl shadow-sm overflow-hidden"
 x-data="{
 selected: [],
 selectAll: false,
 toggleSelectAll() {
 if (this.selectAll) {
 this.selected = Array.from(document.querySelectorAll('.click-checkbox')).map(el => el.value);
 } else {
 this.selected = [];
 }
 }
 }">
 <div class="p-4 sm:p-5 border-b border-[border-orange-100] flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-[#FAF8F5]">
 <div class="flex items-center gap-3">
 <h3 class="font-bold text-[#1F2937] text-base flex items-center gap-2">
 <span></span>
 <span>سجل آخر نقرات واتساب المسجلة</span>
 </h3>
 <span class="text-xs text-gray-500 font-medium bg-white px-2.5 py-0.5 rounded-full border border-gray-200">
 ({{ $recentClicks->total() ?? count($recentClicks) }} سجل)
 </span>
 </div>

 {{-- Bulk Actions Toolbar --}}
 <div class="flex items-center gap-2 flex-wrap">
 {{-- Delete Selected Button --}}
 <form action="{{ route('admin.analytics.bulk-destroy') }}" method="POST" data-confirm data-confirm-message="هل أنت متأكد من رغبتك في حذف السجلات المحددة نهائياً؟" x-show="selected.length > 0" style="display: none;">
 @csrf
 <template x-for="id in selected" :key="id">
 <input type="hidden" name="ids[]" :value="id">
 </template>
 <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-xs transition-colors cursor-pointer">
 <span></span>
 <span>حذف المحدد (<span x-text="selected.length"></span>)</span>
 </button>
 </form>

 {{-- Clear All Records Button --}}
 @if($recentClicks->total() > 0)
 <form action="{{ route('admin.analytics.bulk-destroy') }}" method="POST" data-confirm data-confirm-message="تحذير: هل أنت متأكد من رغبتك في مسح وتفريغ كامل سجلات النقرات نهائياً؟ لا يمكن التراجع عن هذا الإجراء.">
 @csrf
 <input type="hidden" name="delete_all" value="1">
 <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-colors cursor-pointer" title="حذف كامل السجلات">
 <span></span>
 <span>تفريغ السجل بالكامل</span>
 </button>
 </form>
 @endif
 </div>
 </div>

 <div class="overflow-x-auto">
 <table class="w-full text-right border-collapse">
 <thead>
 <tr class="border-b border-[border-orange-100] bg-[bg-canvas-base]/70 text-gray-500 text-xs uppercase font-semibold">
 <th class="py-3.5 px-4 w-10 text-center">
 <input type="checkbox" 
 x-model="selectAll" 
 @change="toggleSelectAll()" 
 class="w-4 h-4 rounded text-[#DC2626] focus:ring-[#DC2626] border-gray-300 cursor-pointer">
 </th>
 <th class="py-3.5 px-4">النوع</th>
 <th class="py-3.5 px-4">بيانات العميل والتواصل</th>
 <th class="py-3.5 px-4">تفاصيل الطلب / المنتجات</th>
 <th class="py-3.5 px-4">الإجمالي</th>
 <th class="py-3.5 px-4">التاريخ والوقت</th>
 <th class="py-3.5 px-4 text-center">الإجراءات</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-[#F0ECE1] text-sm text-[#1F2937]">
 @forelse($recentClicks as $click)
 @php
 $orderInfo = $click->order_data ?? [];
 $customerName = $orderInfo['customer_name'] ?? $click->user?->name ?? 'زائر / عميل';
 $customerPhone = $orderInfo['customer_phone'] ?? $click->user?->phone ?? null;
 $customerAddress = $orderInfo['customer_address'] ?? null;
 $orderNumber = $orderInfo['order_number'] ?? null;
 $totalAmount = $orderInfo['total'] ?? null;
 @endphp
 <tr class="hover:bg-[bg-canvas-base]/40 transition-colors" :class="selected.includes('{{ $click->id }}') ? 'bg-[#FAF8F5]' : ''">
 <td class="py-4 px-4 align-top text-center">
 <input type="checkbox" 
 value="{{ $click->id }}" 
 x-model="selected" 
 class="click-checkbox w-4 h-4 rounded text-[#DC2626] focus:ring-[#DC2626] border-gray-300 cursor-pointer">
 </td>
 <td class="py-4 px-4 align-top">
 @if($click->click_type === 'checkout')
 <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
 إتمام طلب
 </span>
 @if($orderNumber)
 <span class="block text-[11px] font-mono text-gray-500 mt-1 font-bold">{{ $orderNumber }}</span>
 @endif
 @elseif($click->click_type === 'product_inquiry')
 <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200">
 استفسار منتج
 </span>
 @else
 <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-[bg-canvas-base] text-gray-500 border border-[border-orange-100]">
 تواصل عام
 </span>
 @endif
 </td>
 <td class="py-4 px-4 align-top">
 <div class="font-bold text-[#1F2937] flex items-center gap-1.5">
 <span></span>
 <span>{{ $customerName }}</span>
 </div>
 @if($customerPhone)
 <div class="mt-1 flex items-center gap-2">
 <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customerPhone) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:underline font-mono">
 <span></span>
 <span>{{ $customerPhone }}</span>
 </a>
 </div>
 @endif
 @if($customerAddress)
 <span class="text-[11px] text-gray-500 block mt-1 line-clamp-2" title="{{ $customerAddress }}">
 {{ $customerAddress }}
 </span>
 @endif
 </td>
 <td class="py-4 px-4 align-top text-xs">
 @if(!empty($orderInfo['items']))
 <div class="space-y-1">
 @foreach($orderInfo['items'] as $it)
 <div class="flex items-center justify-between gap-2 text-[11px] bg-white border border-[border-orange-100]/60 px-2 py-1 rounded-lg">
 <span class="font-medium text-[#1F2937] truncate max-w-[180px]">{{ $it['name'] }}</span>
 <span class="font-bold text-gray-500">×{{ $it['quantity'] }}</span>
 </div>
 @endforeach
 </div>
 @elseif(!empty($orderInfo['product_name']))
 <span class="font-bold text-[#1F2937] block">{{ $orderInfo['product_name'] }}</span>
 @else
 <span class="text-gray-400 text-xs">{{ $click->source_page ?? 'صفحة عامة' }}</span>
 @endif
 </td>
 <td class="py-4 px-4 align-top font-bold text-sm text-[#DC2626] font-sans whitespace-nowrap">
 @if(!empty($orderInfo['formatted_total']))
 {{ $orderInfo['formatted_total'] }}
 @elseif(!is_null($totalAmount))
 ${{ number_format((float)$totalAmount, 2) }}
 @else
 -
 @endif
 </td>
 <td class="py-4 px-4 align-top text-gray-500 text-xs whitespace-nowrap">
 <span class="font-medium">{{ $click->created_at?->format('Y-m-d') }}</span>
 <span class="block text-[10px] text-gray-400">{{ $click->created_at?->format('h:i A') }}</span>
 </td>
 <td class="py-4 px-4 align-top text-center">
 <form action="{{ route('admin.analytics.destroy', $click->id) }}" method="POST" data-confirm-message="هل أنت متأكد من رغبتك في حذف هذا السجل؟">
 @csrf
 @method('DELETE')
 <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="حذف هذا السجل">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
 </svg>
 </button>
 </form>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="py-12 text-center text-gray-500">
 لا توجد نقرات مسجلة حتى الآن.
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>

 @if(method_exists($recentClicks, 'hasPages') && $recentClicks->hasPages())
 <div class="p-4 border-t border-[border-orange-100] bg-[bg-canvas-base]/30">
 {{ $recentClicks->links() }}
 </div>
 @endif
 </div>
</div>
@endsection
