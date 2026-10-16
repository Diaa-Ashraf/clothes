@extends('layouts.admin')

@section('title', 'كوبونات وقسائم الخصم')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">كوبونات الخصم</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">كوبونات وقسائم الخصم</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">إدارة الرموز الترويجية، ونسب الخصم على الفواتير، وفترات الصلاحية</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>إضافة كوبون جديد</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#FBF6EA] border border-[#CDB78F] text-[#2B2118] text-xs font-bold flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Coupons Table Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse text-xs">
                <thead>
                    <tr class="bg-[#FBF6EA] border-b border-[#EADFC8] text-[#6B5642] font-bold">
                        <th class="py-3.5 px-5">رمز الكوبون</th>
                        <th class="py-3.5 px-4">نوع الخصم</th>
                        <th class="py-3.5 px-4">القيمة</th>
                        <th class="py-3.5 px-4">الحد الأدنى للطلب</th>
                        <th class="py-3.5 px-4">مرات الاستخدام</th>
                        <th class="py-3.5 px-4">الصلاحية</th>
                        <th class="py-3.5 px-4">الحالة</th>
                        <th class="py-3.5 px-5 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EADFC8] text-[#2B2118]">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-[#FDFBF7] transition-colors">
                            <td class="py-3.5 px-5">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-[#F3E9D2] border border-[#EADFC8] font-mono font-bold text-xs text-[#2B2118]">
                                    <svg class="w-3.5 h-3.5 text-[#8F7B63]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <span>{{ $coupon->code }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-medium text-[#6B5642]">
                                {{ $coupon->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ نقدي ثابت' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#A9552F]">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}%
                                    @if($coupon->max_discount_amount)
                                        <span class="text-[10px] text-[#8F7B63] block font-normal">(بحد أقصى {{ number_format($coupon->max_discount_amount, 2) }} ج.م)</span>
                                    @endif
                                @else
                                    {{ number_format($coupon->value, 2) }} ج.م
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-[#6B5642]">
                                {{ $coupon->min_order_amount ? number_format($coupon->min_order_amount, 2) . ' ج.م' : 'بدون حد أدنى' }}
                            </td>
                            <td class="py-3.5 px-4 font-medium">
                                <span class="text-[#2B2118] font-bold">{{ $coupon->used_count }}</span>
                                <span class="text-[#8F7B63]">/ {{ $coupon->usage_limit ?: 'غير محدود' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-[11px] text-[#6B5642]">
                                @if($coupon->expires_at)
                                    <div>حتى: <span class="font-medium text-[#2B2118]">{{ $coupon->expires_at->format('Y-m-d') }}</span></div>
                                    @if($coupon->expires_at->isPast())
                                        <span class="text-[#A9552F] font-bold text-[10px]">منتهي الصلاحية</span>
                                    @endif
                                @else
                                    <span class="text-emerald-700 font-medium">دائم وغير محدد</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($coupon->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        مفعل
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-[#F3E9D2] text-[#8F7B63] border border-[#EADFC8]">
                                        معطل
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                       class="px-2.5 py-1 rounded-lg bg-[#FBF6EA] hover:bg-[#F3E9D2] text-[#2B2118] border border-[#EADFC8] text-xs font-medium transition-colors">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا الكوبون؟')">
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
                                لا توجد كوبونات خصم مسجلة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-[#EADFC8] bg-[#FBF6EA]/50">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
