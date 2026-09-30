@extends('layouts.admin')

@section('title', __('إضافة بانر جديد'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('إضافة بانر ترويجي جديد') }}</h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('تحكم في الإعلانات وشاشات العرض الترويجية بواجهة المتجر') }}</p>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-900 text-xs font-bold transition-colors">
            <span>&rarr;</span>
            <span>{{ __('العودة للبانرات') }}</span>
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-bold">
            <div class="mb-1">{{ __('يرجى تصحيح الأخطاء التالية:') }}</div>
            <ul class="list-disc list-inside space-y-1 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-orange-100 rounded-3xl p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('صورة البانر') }} <span class="text-red-500">*</span></label>
                    <input type="file" name="image" required accept="image/*"
                           class="w-full px-3.5 py-2 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-red-600 cursor-pointer">
                    <small class="text-[11px] text-gray-400 mt-1 block font-medium">الأبعاد المقترحة تعتمد على موضع البانر (يفضل دقة عالية)</small>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('الموضع / المربع (Slot)') }} <span class="text-red-500">*</span></label>
                    <select name="position" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" required>
                        <option value="1">{{ __('الموضع 1 (البانر الرئيسي / سلايدر أول)') }}</option>
                        <option value="2">{{ __('الموضع 2 (بانر فرعي أيسر)') }}</option>
                        <option value="3">{{ __('الموضع 3 (بانر فرعي أوسط)') }}</option>
                        <option value="4">{{ __('الموضع 4 (بانر فرعي أيمن)') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('العنوان الرئيسي (اختياري)') }}</label>
                    <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" placeholder="مثال: خصومات كبرى بمناسبة الموسم" value="{{ old('title') }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('رابط التوجيه عند النقر (Target URL)') }}</label>
                    <input type="url" name="url" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all dir-ltr text-right" placeholder="https://..." value="{{ old('url') }}">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('الوصف الترويجي (اختياري)') }}</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-medium transition-all" placeholder="نص توضيحي يظهر فوق البانر...">{{ old('description') }}</textarea>
            </div>

            <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100">
                <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('حالة البانر') }}</label>
                <select name="status" class="w-full px-4 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold">
                    <option value="1">{{ __('مفعّل (نشط بالمتجر)') }}</option>
                    <option value="0">{{ __('معطّل (مخفي)') }}</option>
                </select>
            </div>

            <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
                <a href="{{ route('banners.index') }}" class="px-5 py-2.5 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-800 text-xs font-bold transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all cursor-pointer">
                    {{ __('حفظ البانر') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
