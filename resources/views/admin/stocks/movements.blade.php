@extends('layouts.admin')

@section('title', 'سجل حركات المخزون والتدقيق')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-6 sm:p-8 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-action/10 text-action">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </span>
                <span class="text-xs font-semibold text-clay uppercase tracking-wider">سجل التدقيق والمراقبة</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-espresso">
                سجل حركات وتدفقات المخزون
            </h1>
            <p class="text-xs text-mocha mt-1">
                سجل غير قابل للحذف (Append-Only) يوثق كل إضافة، خصم، أو تسوية جردية مع تفاصيل الموظف والتاريخ.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.stocks.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-action text-action-ink hover:bg-espresso text-xs font-semibold shadow-sm transition-all cursor-pointer">
                &larr; العودة للوحة جرد المخزون
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-cream border border-sand/40 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('admin.stocks.movements') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            <div class="lg:col-span-5">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث برقم الإذن، اسم الموديل، رمز SKU..." 
                       class="w-full px-3.5 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso placeholder-taupe focus:outline-none focus:border-action">
            </div>

            <div class="lg:col-span-4">
                <select name="type" class="w-full px-3 py-2 text-xs rounded-xl bg-vanilla/30 border border-sand/60 text-espresso focus:outline-none focus:border-action">
                    <option value="">-- كل أنواع الحركات المخزنية --</option>
                    <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>توريد جديد (شراء/تشغيل)</option>
                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>تسوية جردية</option>
                    <option value="return" {{ request('type') == 'return' ? 'selected' : '' }}>مرتجع</option>
                    <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>خصم بيع</option>
                    <option value="damaged" {{ request('type') == 'damaged' ? 'selected' : '' }}>تالف / هالك</option>
                </select>
            </div>

            <div class="lg:col-span-3 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2 px-4 rounded-xl bg-action hover:bg-espresso text-action-ink text-xs font-bold transition-all shadow-xs cursor-pointer">
                    تصفية السجل
                </button>
                @if(request()->hasAny(['search', 'type']))
                    <a href="{{ route('admin.stocks.movements') }}" class="px-2.5 py-2 text-xs text-mocha hover:text-espresso underline">إلغاء</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Movements Table --}}
    <div class="bg-cream border border-sand/40 rounded-2xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs border-collapse">
                <thead class="bg-linen/60 text-mocha font-bold border-b border-sand/40 text-[11px] uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-right">التاريخ والوقت</th>
                        <th class="py-3 px-4 text-right">القطعة / التنويع</th>
                        <th class="py-3 px-4 text-center">نوع الحركة</th>
                        <th class="py-3 px-4 text-center">مقدار التعديل</th>
                        <th class="py-3 px-4 text-center">الرصيد بعد الحركة</th>
                        <th class="py-3 px-4 text-right">المستودع / الفرع</th>
                        <th class="py-3 px-4 text-right">السبب / الملاحظات</th>
                        <th class="py-3 px-4 text-right">الموظف المسئول</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand/30">
                    @forelse($movements as $m)
                        <tr class="hover:bg-vanilla/30 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-[11px] text-taupe whitespace-nowrap">
                                <div>{{ $m->created_at->format('Y-m-d') }}</div>
                                <div class="text-[10px] text-taupe/80">{{ $m->created_at->format('h:i A') }}</div>
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2.5">
                                    @if($m->product && $m->product->image_url)
                                        <img src="{{ $m->product->image_url }}" alt="{{ $m->product->name }}" class="w-8 h-10 object-cover rounded-md border border-sand/50 shrink-0">
                                    @endif
                                    <div>
                                        <div class="font-bold text-espresso">{{ $m->product?->name ?? 'منتج محذوف' }}</div>
                                        @if($m->variant)
                                            <div class="flex items-center gap-1.5 mt-0.5 text-[10px] text-mocha font-mono">
                                                <span>{{ $m->variant->sku }}</span>
                                                @php
                                                    $labels = $m->variant->attributeValues->pluck('value')->implode(' / ');
                                                @endphp
                                                @if($labels)
                                                    <span class="text-taupe">({{ $labels }})</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $m->type_badge_class }}">
                                    {{ $m->type_name }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-bold text-xs">
                                @if($m->quantity_delta > 0)
                                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                        +{{ $m->quantity_delta }}
                                    </span>
                                @else
                                    <span class="text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                                        {{ $m->quantity_delta }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-mono font-bold text-xs text-espresso">
                                {{ $m->quantity_after }} قطعة
                            </td>

                            <td class="py-3.5 px-4 text-mocha text-[11px]">
                                {{ $m->branch?->name ?? 'المستودع الرئيسي' }}
                            </td>

                            <td class="py-3.5 px-4 text-espresso text-xs max-w-xs truncate">
                                {{ $m->reason ?: '—' }}
                            </td>

                            <td class="py-3.5 px-4 text-taupe text-[11px] whitespace-nowrap">
                                {{ $m->creator?->name ?? 'النظام الآلي' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-taupe">
                                لا توجد حركات مخزنية مسجلة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="p-4 border-t border-sand/40 bg-linen/20">
                {{ $movements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
