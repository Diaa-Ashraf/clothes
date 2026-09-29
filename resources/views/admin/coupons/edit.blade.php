@extends('layouts.admin')

@section('title', 'تعديل كوبون الخصم: ' . $coupon->code)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white border border-orange-100 rounded-2xl p-6 sm:p-8 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">تعديل كوبون الخصم: {{ $coupon->code }}</h1>
            <p class="text-xs text-gray-500 mt-1">تحديث إعدادات وشروط الكوبون الترويجي.</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900">← العودة للكوبونات</a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="bg-white border border-orange-100 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Coupon Code -->
            <div>
                <label for="code" class="block text-xs font-bold text-gray-700 mb-1.5">رمز الكوبون (الكود) <span class="text-red-500">*</span></label>
                <input type="text" name="code" id="code" value="{{ old('code', $coupon->code) }}" required class="w-full uppercase font-mono bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-xs font-bold text-gray-700 mb-1.5">نوع الخصم <span class="text-red-500">*</span></label>
                <select name="type" id="type" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>مبلغ مالي ثابت (ج.م)</option>
                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                </select>
            </div>

            <!-- Value -->
            <div>
                <label for="value" class="block text-xs font-bold text-gray-700 mb-1.5">قيمة الخصم <span class="text-red-500">*</span></label>
                <input type="number" step="0.01" name="value" id="value" value="{{ old('value', $coupon->value) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
            </div>

            <!-- Min Order Amount -->
            <div>
                <label for="min_order_amount" class="block text-xs font-bold text-gray-700 mb-1.5">الحد الأدنى للطلب (اختياري)</label>
                <input type="number" step="0.01" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
            </div>

            <!-- Max Discount Amount (For percentage) -->
            <div>
                <label for="max_discount_amount" class="block text-xs font-bold text-gray-700 mb-1.5">أقصى حد لقيمة الخصم (للنسب المئوية)</label>
                <input type="number" step="0.01" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
            </div>

            <!-- Usage Limit -->
            <div>
                <label for="usage_limit" class="block text-xs font-bold text-gray-700 mb-1.5">العدد الأقصى للاستخدام (فارغ = غير محدود)</label>
                <input type="number" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
                <span class="text-[10px] text-gray-500 mt-1 block">تم استخدامه حتى الآن: {{ $coupon->used_count }} مرة</span>
            </div>

            <!-- Starts At -->
            <div>
                <label for="starts_at" class="block text-xs font-bold text-gray-700 mb-1.5">تاريخ بدء السريان</label>
                <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d\TH:i')) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
            </div>

            <!-- Expires At -->
            <div>
                <label for="expires_at" class="block text-xs font-bold text-gray-700 mb-1.5">تاريخ انتهاء الكوبون</label>
                <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d\TH:i')) }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-xs text-gray-900 outline-none focus:border-red-500">
            </div>
        </div>

        <!-- Is Active -->
        <div class="flex items-center gap-2 pt-2">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
            <label for="is_active" class="text-xs font-bold text-gray-800">تفعيل الكوبون للاستخدام</label>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.coupons.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-red-600 to-orange-500 text-white text-xs font-black hover:from-red-700 hover:to-orange-600 shadow-md cursor-pointer">تحديث الكوبون</button>
        </div>
    </form>
</div>
@endsection
