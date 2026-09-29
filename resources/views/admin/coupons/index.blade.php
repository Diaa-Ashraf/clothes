@extends('layouts.admin')

@section('title', 'كوبونات الخصم وقسائم التوفير')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="bg-white border border-orange-100 rounded-2xl p-6 sm:p-8 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Coupons & Promo Codes</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                كوبونات الخصم وقسائم الشراء
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                إدارة أكواد الخصم الترويجية، وتحديد نسب الخصم والحدود الدنيا وتواريخ الصلاحية.
            </p>
        </div>

        <a href="{{ route('admin.coupons.create') }}" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all flex items-center gap-2 shrink-0 cursor-pointer">
            <span>+</span>
            <span>إضافة كوبون جديد</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Coupons Table -->
    <div class="bg-white border border-orange-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-orange-50/50 text-gray-600 uppercase text-[11px] border-b border-orange-100">
                    <tr>
                        <th class="py-3.5 px-6 text-start font-bold">الكود</th>
                        <th class="py-3.5 px-4 text-start font-bold">النوع</th>
                        <th class="py-3.5 px-4 text-start font-bold">قيمة الخصم</th>
                        <th class="py-3.5 px-4 text-start font-bold">الحد الأدنى للطلب</th>
                        <th class="py-3.5 px-4 text-start font-bold">مرات الاستخدام</th>
                        <th class="py-3.5 px-4 text-start font-bold">الصلاحية</th>
                        <th class="py-3.5 px-4 text-start font-bold">الحالة</th>
                        <th class="py-3.5 px-6 text-end font-bold">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-orange-100">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="py-4 px-6 font-black text-gray-900">
                                <span class="bg-gray-100 text-gray-800 px-2.5 py-1 rounded-lg font-mono text-xs border border-gray-200">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-700">
                                {{ $coupon->type === 'percentage' ? 'نسبة مئوية' : 'مبلغ ثابت' }}
                            </td>
                            <td class="py-4 px-4 font-black text-red-600 tabular-nums">
                                {{ $coupon->type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 2) . ' ج.م' }}
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-600 tabular-nums">
                                {{ $coupon->min_order_amount ? number_format($coupon->min_order_amount, 2) . ' ج.م' : 'بدون حد أدنى' }}
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-700 tabular-nums">
                                {{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}
                            </td>
                            <td class="py-4 px-4 text-gray-500 text-[11px]">
                                @if($coupon->expires_at)
                                    <span>ينتهي: {{ $coupon->expires_at->format('Y-m-d') }}</span>
                                @else
                                    <span class="text-emerald-700 font-bold">دائم</span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($coupon->is_active)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">مفعل</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-700 border border-gray-200">معطل</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-end space-x-2 space-x-reverse">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-indigo-600 hover:text-indigo-800 font-bold">تعديل</a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline-block" onsubmit="return confirm('هل تريد بالتأكيد حذف هذا الكوبون؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:text-red-800 font-bold cursor-pointer">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500">
                                لا توجد كوبونات خصم مضافة حالياً.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-4 border-t border-orange-100">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
