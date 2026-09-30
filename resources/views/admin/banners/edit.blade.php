@extends('layouts.admin')

@section('title', __('تعديل البانر'))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('تعديل البانر') }} <span class="text-red-600">#{{ $banner->id }}</span></h2>
            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ __('تحديث بيانات وموضع البانر الترويجي') }}</p>
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
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('صورة البانر') }}</label>
                    @if($banner->image_url)
                        <div class="mb-3 flex items-center gap-3 p-2 bg-canvas-base rounded-2xl border border-orange-100">
                            <img src="{{ $banner->image_url }}" alt="Current Banner" class="h-20 w-auto rounded-xl object-cover border border-orange-200">
                            <div>
                                <span class="text-xs font-bold text-gray-800 block">الصورة الحالية</span>
                                <span class="text-[11px] text-gray-400 font-medium">اترك الحقل فارغاً للإبقاء على الصورة الحالية</span>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-3.5 py-2 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 text-xs focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-900 file:text-white hover:file:bg-red-600 cursor-pointer">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('الموضع / المربع (Slot)') }} <span class="text-red-500">*</span></label>
                    <select name="position" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" required>
                        <option value="1" {{ $banner->position == 1 ? 'selected' : '' }}>{{ __('الموضع 1 (البانر الرئيسي / سلايدر أول)') }}</option>
                        <option value="2" {{ $banner->position == 2 ? 'selected' : '' }}>{{ __('الموضع 2 (بانر فرعي أيسر)') }}</option>
                        <option value="3" {{ $banner->position == 3 ? 'selected' : '' }}>{{ __('الموضع 3 (بانر فرعي أوسط)') }}</option>
                        <option value="4" {{ $banner->position == 4 ? 'selected' : '' }}>{{ __('الموضع 4 (بانر فرعي أيمن)') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('العنوان الرئيسي (اختياري)') }}</label>
                    <input type="text" name="title" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all" value="{{ old('title', $banner->title) }}">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('رابط التوجيه عند النقر (Target URL)') }}</label>
                    <input type="url" name="url" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold transition-all dir-ltr text-right" value="{{ old('url', $banner->url) }}">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('الوصف الترويجي (اختياري)') }}</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-orange-100 bg-canvas-base text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-medium transition-all">{{ old('description', $banner->description) }}</textarea>
            </div>

            <div class="p-4 rounded-2xl bg-canvas-base border border-orange-100">
                <label class="block text-xs font-bold text-gray-900 mb-2">{{ __('حالة البانر') }}</label>
                <select name="status" class="w-full px-4 py-2 rounded-xl border border-orange-100 bg-white text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 focus:outline-none text-xs font-bold">
                    <option value="1" {{ $banner->status == 1 ? 'selected' : '' }}>{{ __('مفعّل (نشط بالمتجر)') }}</option>
                    <option value="0" {{ $banner->status == 0 ? 'selected' : '' }}>{{ __('معطّل (مخفي)') }}</option>
                </select>
            </div>

            <div class="pt-4 border-t border-orange-100 flex items-center justify-end gap-3">
                <a href="{{ route('banners.index') }}" class="px-5 py-2.5 rounded-xl border border-orange-200 bg-canvas-base hover:bg-orange-50 text-gray-800 text-xs font-bold transition-colors">
                    {{ __('إلغاء') }}
                </a>
                <button type="submit" class="px-7 py-3 rounded-2xl bg-gradient-to-r from-red-600 to-orange-500 hover:from-red-700 hover:to-orange-600 text-white text-xs font-black shadow-md shadow-red-500/20 transition-all cursor-pointer">
                    {{ __('تحديث البانر') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
