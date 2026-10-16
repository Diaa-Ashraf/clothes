@extends('layouts.admin')

@section('title', 'العروض والتخفيضات الخاصة')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">العروض الخاصة</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">العروض والتخفيضات المباشرة</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تحديد خصومات مباشرة على قطع الأزياء وشارات التخفيض الحصرية</p>
        </div>
        <a href="{{ route('admin.deals.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>إضافة عرض خاص</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#CDB78F] text-[#2B2118] text-xs font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Deals Table Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <thead>
                    <tr class="bg-[#FBF6EA] border-b border-[#EADFC8] text-[#6B5642] font-bold">
                        <th class="py-3.5 px-5" style="width: 60px;">#</th>
                        <th class="py-3.5 px-4">قطعة الأزياء (المنتج)</th>
                        <th class="py-3.5 px-4">السعر الأصلي</th>
                        <th class="py-3.5 px-4">سعر العرض</th>
                        <th class="py-3.5 px-4">نسبة الخصم</th>
                        <th class="py-3.5 px-4">شارة العرض</th>
                        <th class="py-3.5 px-4">فترة السريان</th>
                        <th class="py-3.5 px-4">الحالة</th>
                        <th class="py-3.5 px-5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                    @forelse($deals as $deal)
                        <tr class="hover:bg-[#FDFBF7] transition-colors">
                            <td class="py-3.5 px-5 font-mono text-xs text-[#8F7B63]">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] p-0.5 shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($deal->product && $deal->product->image)
                                            <img src="{{ asset('storage/' . $deal->product->image) }}" 
                                                 alt="{{ $deal->product->name }}" 
                                                 class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <span class="text-base text-[#8F7B63]">👗</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ $deal->product ? route('admin.products.edit', $deal->product->id) : '#' }}" 
                                           class="font-bold text-xs text-[#2B2118] hover:text-[#A9552F] transition-colors line-clamp-1">
                                            {{ $deal->product?->name ?? 'منتج غير محدد' }}
                                        </a>
                                        <span class="text-[10px] text-[#8F7B63]">معرّف المنتج: #{{ $deal->product_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-[#8F7B63] line-through tabular-nums font-medium">
                                {{ number_format($deal->original_price, 2) }} ج.م
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#A9552F] tabular-nums text-sm">
                                {{ number_format($deal->deal_price, 2) }} ج.م
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-[#A9552F] border border-red-200 text-xs font-bold">
                                    -{{ $deal->discount_percent }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-[11px] font-medium">
                                    {{ $deal->badge_text }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[11px] text-[#6B5642]">
                                @if($deal->starts_at || $deal->ends_at)
                                    <div>من: {{ $deal->starts_at ? $deal->starts_at->format('Y-m-d') : 'الآن' }}</div>
                                    <div>إلى: {{ $deal->ends_at ? $deal->ends_at->format('Y-m-d') : 'مفتوح' }}</div>
                                @else
                                    <span class="text-emerald-700 font-medium">مستمر وغير محدد</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if(!$deal->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-[#F3E9D2] text-[#8F7B63] border border-[#EADFC8]">
                                        معطل
                                    </span>
                                @elseif($deal->starts_at && $deal->starts_at->isFuture())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                        لم يبدأ بعد
                                    </span>
                                @elseif($deal->ends_at && $deal->ends_at->isPast())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-[#A9552F] border border-red-200">
                                        منتهي
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        نشط
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.deals.edit', $deal->id) }}" 
                                       class="px-2.5 py-1 rounded-lg bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium transition-colors">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.deals.destroy', $deal->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا العرض الترويجي؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-[#A9552F] text-xs font-medium transition-colors">
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-10 text-sm text-[#8F7B63]">
                                لا توجد عروض وتخفيضات خاصة مسجلة حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($deals->hasPages())
            <div class="p-4 border-t border-[#EADFC8] bg-[#FBF6EA]/50">
                {{ $deals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
