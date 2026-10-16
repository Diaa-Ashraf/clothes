@extends('layouts.admin')

@section('title', 'عروض التوفير المجمعة (Bundles)')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">العروض المجمعة</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">عروض التوفير المجمعة</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">تجميع عدة قطع أزياء متناسقة معاً بسعر مخفض جذاب لرفع متوسط الفاتورة</p>
        </div>
        <a href="{{ route('admin.bundles.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>إنشاء عرض مجمع جديد</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#CDB78F] text-[#2B2118] text-xs font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Bundles Table Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <thead>
                    <tr class="bg-[#FBF6EA] border-b border-[#EADFC8] text-[#6B5642] font-bold">
                        <th class="py-3.5 px-5">اسم العرض المجمع</th>
                        <th class="py-3.5 px-4">عدد القطع المشمولة</th>
                        <th class="py-3.5 px-4">السعر الأصلي</th>
                        <th class="py-3.5 px-4">سعر الباقة</th>
                        <th class="py-3.5 px-4">نسبة التوفير</th>
                        <th class="py-3.5 px-4">فترة السريان</th>
                        <th class="py-3.5 px-4">الحالة</th>
                        <th class="py-3.5 px-5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                    @forelse($bundles as $bundle)
                        <tr class="hover:bg-[#FDFBF7] transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-[#F3E9D2] border border-[#EADFC8] p-0.5 shrink-0 flex items-center justify-center overflow-hidden">
                                        @if($bundle->image_url)
                                            <img src="{{ $bundle->image_url }}" alt="{{ $bundle->name }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <span class="text-xl">🛍️</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-xs text-[#2B2118] block">{{ $bundle->name }}</span>
                                        <span class="text-[11px] text-[#8F7B63] block truncate max-w-xs">{{ Str::limit($bundle->description, 45) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium">
                                    <span>{{ $bundle->products_count }}</span>
                                    <span>قطع مشمولة</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#8F7B63] line-through tabular-nums font-medium">
                                {{ number_format($bundle->original_total, 2) }} ج.م
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#A9552F] tabular-nums text-sm">
                                {{ number_format($bundle->bundle_price, 2) }} ج.م
                            </td>
                            <td class="py-3.5 px-4">
                                @if($bundle->discount_percent > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-red-50 text-[#A9552F] border border-red-200 text-xs font-bold">
                                        وفر {{ $bundle->discount_percent }}%
                                    </span>
                                @else
                                    <span class="text-[#8F7B63]">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[11px] text-[#6B5642]">
                                @if($bundle->starts_at || $bundle->ends_at)
                                    <div>من: {{ $bundle->starts_at ? $bundle->starts_at->format('Y-m-d') : 'الآن' }}</div>
                                    <div>إلى: {{ $bundle->ends_at ? $bundle->ends_at->format('Y-m-d') : 'مفتوح' }}</div>
                                @else
                                    <span class="text-emerald-700 font-medium">مستمر وغير محدد</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if(!$bundle->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-[#F3E9D2] text-[#8F7B63] border border-[#EADFC8]">
                                        معطل
                                    </span>
                                @elseif($bundle->starts_at && $bundle->starts_at->isFuture())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                        لم يبدأ بعد
                                    </span>
                                @elseif($bundle->ends_at && $bundle->ends_at->isPast())
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
                                    <a href="{{ route('admin.bundles.edit', $bundle->id) }}" 
                                       class="px-2.5 py-1 rounded-lg bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium transition-colors">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.bundles.destroy', $bundle->id) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا العرض المجمع؟')">
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
                            <td colspan="8" class="text-center py-10 text-sm text-[#8F7B63]">
                                لا توجد عروض مجمعة مضافة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bundles->hasPages())
            <div class="p-4 border-t border-[#EADFC8] bg-[#FBF6EA]/50">
                {{ $bundles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
