@extends('layouts.admin')

@section('title', 'إضافة كوبون خصم جديد')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-[#EADFC8] pb-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#8F7B63] mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-[#2B2118] transition-colors">لوحة التحكم</a>
                <span class="text-[#CDB78F]">/</span>
                <a href="{{ route('admin.coupons.index') }}" class="hover:text-[#2B2118] transition-colors">الكوبونات</a>
                <span class="text-[#CDB78F]">/</span>
                <span class="text-[#2B2118] font-medium">إضافة كوبون</span>
            </div>
            <h1 class="text-2xl font-serif font-bold text-[#2B2118]">إضافة كوبون خصم جديد</h1>
            <p class="text-sm text-[#6B5642] mt-0.5">إنشاء كود ترويجي لتطبيق خصومات على فواتير المتجر</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-[#EADFC8] text-[#2B2118] hover:bg-[#FBF6EA] transition-all text-sm font-medium shadow-sm w-fit">
            <svg class="w-4 h-4 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>العودة للكوبونات</span>
        </a>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl bg-red-50/50 border border-[#A9552F]/30 text-[#A9552F] text-xs space-y-1">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-[#EADFC8] shadow-sm p-6 sm:p-8">
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Coupon Code -->
                <div class="space-y-1.5">
                    <label for="code" class="block text-xs font-bold text-[#2B2118]">
                        رمز الكوبون (الكود) <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                           placeholder="مثال: SUMMER25"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm font-mono uppercase text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                </div>

                <!-- Type -->
                <div class="space-y-1.5">
                    <label for="type" class="block text-xs font-bold text-[#2B2118]">
                        نوع الخصم <span class="text-[#A9552F]">*</span>
                    </label>
                    <select name="type" id="type" required
                            class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all">
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>مبلغ مالي ثابت (ج.م)</option>
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>نسبة مئوية (%)</option>
                    </select>
                </div>

                <!-- Value -->
                <div class="space-y-1.5">
                    <label for="value" class="block text-xs font-bold text-[#2B2118]">
                        قيمة الخصم <span class="text-[#A9552F]">*</span>
                    </label>
                    <input type="number" step="0.01" min="0.01" name="value" id="value" value="{{ old('value') }}" required
                           placeholder="مثال: 50 للمبلغ أو 15 للنسبة"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                </div>

                <!-- Min Order Amount -->
                <div class="space-y-1.5">
                    <label for="min_order_amount" class="block text-xs font-bold text-[#2B2118]">
                        الحد الأدنى لقيمة الطلب (ج.م)
                    </label>
                    <input type="number" step="0.01" min="0" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount') }}"
                           placeholder="مثال: 500 (اتركه فارغاً إن لم يكن هناك حد)"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                </div>

                <!-- Max Discount Amount (For percentage) -->
                <div class="space-y-1.5">
                    <label for="max_discount_amount" class="block text-xs font-bold text-[#2B2118]">
                        أقصى خصم مسموح به (للنسب المئوية)
                    </label>
                    <input type="number" step="0.01" min="0" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}"
                           placeholder="مثال: 200 ج.م"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                </div>

                <!-- Usage Limit -->
                <div class="space-y-1.5">
                    <label for="usage_limit" class="block text-xs font-bold text-[#2B2118]">
                        الحد الأقصى لمرات الاستخدام الإجمالية
                    </label>
                    <input type="number" min="1" name="usage_limit" id="usage_limit" value="{{ old('usage_limit') }}"
                           placeholder="مثال: 100 مرة (أو اتركه فارغاً للاستخدام اللامحدود)"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] placeholder-[#CDB78F] transition-all dir-ltr text-right">
                </div>

                <!-- Starts At -->
                <div class="space-y-1.5">
                    <label for="starts_at" class="block text-xs font-bold text-[#2B2118]">
                        تاريخ بدء سريان الكوبون
                    </label>
                    <input type="datetime-local" name="starts_at" id="starts_at" value="{{ old('starts_at') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all">
                </div>

                <!-- Expires At -->
                <div class="space-y-1.5">
                    <label for="expires_at" class="block text-xs font-bold text-[#2B2118]">
                        تاريخ انتهاء الصلاحية
                    </label>
                    <input type="datetime-local" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-[#EADFC8] focus:border-[#2B2118] focus:ring-1 focus:ring-[#2B2118] bg-white text-sm text-[#2B2118] transition-all">
                </div>
            </div>

            <!-- Active Status Toggle -->
            <div class="pt-2 border-t border-[#EADFC8]">
                <label class="relative inline-flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-[#EADFC8] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#382416]"></div>
                    <span class="text-xs font-bold text-[#2B2118]">تفعيل الكوبون فور الإنشاء</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#EADFC8]">
                <a href="{{ route('admin.coupons.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-[#EADFC8] text-[#6B5642] hover:bg-[#FBF6EA] transition-all text-sm font-medium">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[#382416] hover:bg-[#2B2118] text-[#FBF6EA] transition-all text-sm font-medium shadow-sm">
                    حفظ الكوبون
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
